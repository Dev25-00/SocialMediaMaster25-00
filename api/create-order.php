<?php
/**
 * SMM Mastery - API Create Order (AJAX)
 * Date: 13 Octobre 2025
 * Version: 1.0
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/SMMFollowsAPI.php';
require_once __DIR__ . '/AutoCreditSystem.php';
require_once __DIR__ . '/../includes/EmailManager.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Récupérer les données JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validation des données
if (!$data || !isset($data['service_id'], $data['link'], $data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$user = getCurrentUser($pdo);
$service_id = intval($data['service_id']);
$link = trim($data['link']);
$quantity = intval($data['quantity']);
$dripfeed = isset($data['dripfeed']) && $data['dripfeed'] == 1;
$dripfeed_runs = isset($data['dripfeed_runs']) ? intval($data['dripfeed_runs']) : null;
$dripfeed_interval = isset($data['dripfeed_interval']) ? intval($data['dripfeed_interval']) : null;

try {
    // Récupérer le service
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        echo json_encode(['success' => false, 'message' => 'Service not found']);
        exit;
    }
    
    // Validation de la quantité
    if ($quantity < $service['min_quantity']) {
        echo json_encode([
            'success' => false, 
            'message' => "Minimum quantity: " . number_format($service['min_quantity'])
        ]);
        exit;
    }
    
    if ($quantity > $service['max_quantity']) {
        echo json_encode([
            'success' => false, 
            'message' => "Maximum quantity: " . number_format($service['max_quantity'])
        ]);
        exit;
    }
    
    // Validation de l'URL
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid URL format']);
        exit;
    }
    
    // Calculer le prix
    $cost_amount = ($quantity / 1000) * $service['cost_price'];
    $sell_amount = ($quantity / 1000) * $service['sell_price'];
    $profit = $sell_amount - $cost_amount;
    
    // Vérifier le solde
    if ($user['balance'] < $sell_amount) {
        echo json_encode([
            'success' => false, 
            'message' => 'Insufficient balance. Please add funds to your account.'
        ]);
        exit;
    }
    
    // Démarrer une transaction
    $pdo->beginTransaction();
    
    // Déduire le solde
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$sell_amount, $user['id']]);
    
    // Créer la commande
    $order_number = generateOrderNumber();
    
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            user_id, service_id, order_number, link, quantity,
            cost_amount, sell_amount, profit, status, 
            dripfeed, dripfeed_runs, dripfeed_interval,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $user['id'],
        $service_id,
        $order_number,
        $link,
        $quantity,
        $cost_amount,
        $sell_amount,
        $profit,
        $dripfeed ? 1 : 0,
        $dripfeed_runs,
        $dripfeed_interval
    ]);
    
    $order_id = $pdo->lastInsertId();
    
    // Ajouter la transaction
    addTransaction($pdo, $user['id'], 'order', $sell_amount, null, "Order #$order_number");
    
    // Valider la transaction BDD
    $pdo->commit();
    
    // Traiter la commande avec le système auto-crédit
    $message = "Order created successfully! Order #$order_number";
    
    if (AUTO_CREDIT_ENABLED) {
        $smmfollows = new SMMFollowsAPI();
        $autoCreditSystem = new AutoCreditSystem($pdo, $smmfollows);
        
        // Préparer les paramètres drip-feed si activés
        $orderParams = [];
        if ($dripfeed && $dripfeed_runs && $dripfeed_interval) {
            $orderParams['runs'] = $dripfeed_runs;
            $orderParams['interval'] = $dripfeed_interval;
        }
        
        // Traiter la commande
        $result = $autoCreditSystem->processOrder($order_id, $orderParams);
        
        if ($result['success']) {
            $message = "Order placed successfully! Order #$order_number";
            
            // Envoyer email de confirmation
            if (SEND_ORDER_CONFIRMATION) {
                try {
                    $emailManager = new EmailManager($pdo);
                    $emailManager->sendOrderConfirmation([
                        'id' => $order_id,
                        'order_number' => $order_number,
                        'link' => $link,
                        'quantity' => $quantity,
                        'sell_amount' => $sell_amount
                    ], $service, $user);
                } catch (Exception $e) {
                    error_log("Email notification error: " . $e->getMessage());
                }
            }
        } elseif (isset($result['queued']) && $result['queued']) {
            $message = "Order created! Order #$order_number. Processing will start shortly.";
        } else {
            $message = "Order created but processing error: " . ($result['message'] ?? 'Unknown error');
        }
    } else {
        // Mode legacy (sans auto-crédit)
        try {
            $api_key = getSetting($pdo, 'smmfollows_api_key', SMMFOLLOWS_API_KEY);
            
            if (!empty($api_key)) {
                $api = new SMMFollowsAPI($api_key);
                
                // Créer la commande avec ou sans drip-feed
                if ($dripfeed && $dripfeed_runs && $dripfeed_interval) {
                    $result = $api->createOrder(
                        $service['provider_id'], 
                        $link, 
                        $quantity,
                        ['runs' => $dripfeed_runs, 'interval' => $dripfeed_interval]
                    );
                } else {
                    $result = $api->createOrder($service['provider_id'], $link, $quantity);
                }
                
                if (isset($result['order'])) {
                    $stmt = $pdo->prepare("
                        UPDATE orders 
                        SET provider_order_id = ?, status = 'processing'
                        WHERE id = ?
                    ");
                    $stmt->execute([$result['order'], $order_id]);
                    
                    $message = "Order placed successfully! Order #$order_number";
                }
            }
        } catch (Exception $e) {
            error_log("Error sending order to SMMFollows: " . $e->getMessage());
            $message = "Order created but provider communication error.";
        }
    }
    
    // Réponse succès
    echo json_encode([
        'success' => true,
        'message' => $message,
        'order_id' => $order_id,
        'order_number' => $order_number
    ]);
    
} catch (Exception $e) {
    // Rollback en cas d'erreur
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Order creation error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while creating the order. Please try again.'
    ]);
}
