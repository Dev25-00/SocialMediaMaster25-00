<?php
/**
 * NOUVELLE COMMANDE - Version 2.0 avec Auto-Crédit
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../includes/config/icons-config.php';
require_once __DIR__ . '/../api/SMMFollowsAPI.php';
require_once __DIR__ . '/../api/AutoCreditSystem.php';
require_once __DIR__ . '/../includes/email/EmailManager.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Récupérer le service
$service_id = $_GET['service'] ?? 0;
$service = null;

if ($service_id) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
}

// Traitement du formulaire avec SYSTÈME AUTO-CRÉDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id'] ?? 0);
    $link = trim($_POST['link'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validation
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'Token de sécurité invalide';
    } elseif (empty($service_id) || empty($link) || empty($quantity)) {
        $error = 'Tous les champs sont obligatoires';
    } else {
        // Récupérer le service
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND is_active = 1");
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();
        
        if (!$service) {
            $error = 'Service introuvable';
        } elseif ($quantity < $service['min_quantity']) {
            $error = "Quantité minimum : " . number_format($service['min_quantity']);
        } elseif ($quantity > $service['max_quantity']) {
            $error = "Quantité maximum : " . number_format($service['max_quantity']);
        } elseif (!isValidURL($link)) {
            $error = 'URL invalide';
        } else {
            // Calculer le prix
            $cost_amount = ($quantity / 1000) * $service['cost_price'];
            $sell_amount = ($quantity / 1000) * $service['sell_price'];
            $profit = $sell_amount - $cost_amount;
            
            // Vérifier le solde
            if ($user['balance'] < $sell_amount) {
                $error = 'Solde insuffisant. Vous devez recharger votre compte.';
            } else {
                try {
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
                            cost_amount, sell_amount, profit, status, created_at
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
                    ");
                    
                    $stmt->execute([
                        $user['id'],
                        $service_id,
                        $order_number,
                        $link,
                        $quantity,
                        $cost_amount,
                        $sell_amount,
                        $profit
                    ]);
                    
                    $order_id = $pdo->lastInsertId();
                    
                    // Ajouter la transaction
                    addTransaction($pdo, $user['id'], 'order', $sell_amount, null, "Commande #$order_number");
                    
                    // Valider la transaction BDD
                    $pdo->commit();
                    
                    /* <?php echo getIcon('star');  */
                   // NOUVEAU: Utiliser le système auto-crédit
                    if (AUTO_CREDIT_ENABLED) {
                        $smmfollows = new SMMFollowsAPI();
                        $autoCreditSystem = new AutoCreditSystem($pdo, $smmfollows);
                        
                        // Traiter la commande avec auto-crédit
                        $result = $autoCreditSystem->processOrder($order_id);
                        
                        if ($result['success']) {
                            $success = "Commande créée avec succès ! N° $order_number";
                            
                            // Envoyer email de confirmation
                            if (SEND_ORDER_CONFIRMATION) {
                                $emailManager = new EmailManager($pdo);
                                $emailManager->sendOrderConfirmation([
                                    'id' => $order_id,
                                    'order_number' => $order_number,
                                    'link' => $link,
                                    'quantity' => $quantity,
                                    'sell_amount' => $sell_amount
                                ], $service, $user);
                            }
                        } elseif (isset($result['queued']) && $result['queued']) {
                            $success = "Commande créée ! N° $order_number. Elle sera traitée dans quelques instants.";
                        } else {
                            $error = "Commande créée mais erreur lors du traitement : " . $result['message'];
                        }
                    } else {
                        // Mode legacy (sans auto-crédit)
                        try {
                            $api_key = getSetting($pdo, 'smmfollows_api_key', SMMFOLLOWS_API_KEY);
                            
                            if (!empty($api_key)) {
                                $api = new SMMFollowsAPI($api_key);
                                $result = $api->createOrder($service['provider_id'], $link, $quantity);
                                
                                if (isset($result['order'])) {
                                    $stmt = $pdo->prepare("
                                        UPDATE orders 
                                        SET provider_order_id = ?, status = 'processing'
                                        WHERE id = ?
                                    ");
                                    $stmt->execute([$result['order'], $order_id]);
                                    
                                    $success = "Commande créée avec succès ! N° $order_number";
                                }
                            }
                        } catch (Exception $e) {
                            error_log("Error sending order to SMMFollows: " . $e->getMessage());
                            $error = "Commande créée mais erreur lors de l'envoi au fournisseur.";
                        }
                    }
                    
                    // Rediriger si succès
                    if ($success) {
                        setFlashMessage('success', $success);
                        redirect(SITE_URL . "/orders/tracking.php?id=$order_id");
                    }
                    
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $error = 'Erreur lors de la création de la commande : ' . $e->getMessage();
                    logError("Order creation error: " . $e->getMessage());
                }
            }
        }
    }
}

// Configuration page
$page_title = "Nouvelle Commande";
$page_title_bar = "Nouvelle Commande";
$include_charts = false;

// Inclure header simple
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px; max-width: 800px; margin: 0 auto;">
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo getIcon('error'); ?>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo getIcon('success'); ?>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (!$service): ?>
            <div class="card">
                <div class="card-body text-center" style="padding: 60px 30px;">
                    <?php echo getIcon('services', true, 'xl'); ?>
                    <h2 style="margin-top: 20px;">Choisir un service</h2>
                    <p style="color: #6b7280; margin: 15px 0 30px;">
                        Veuillez d'abord sélectionner un service pour créer une commande
                    </p>
                    <a href="<?php echo SITE_URL; ?>/services/index.php" class="btn btn-primary btn-lg">
                        <?php echo getIcon('search'); ?>
                        Parcourir les services
                    </a>
                </div>
            </div>
        <?php else: ?>
            
            <!-- Service Info Card -->
            <div class="card service-info-card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>
                        <?php echo getIcon('services'); ?>
                        Service sélectionné
                    </h2>
                    <a href="<?php echo SITE_URL; ?>/services/index.php" class="btn btn-sm btn-secondary">
                        <?php echo getIcon('search', false, 'sm'); ?>
                        Changer
                    </a>
                </div>
                <div class="card-body">
                    <div class="service-info-grid">
                        <div>
                            <div class="service-platform">
                                <?php echo platformIcon($service['platform']); ?>
                            </div>
                            <h3 class="service-name">
                                <?php echo clean($service['name']); ?>
                            </h3>
                            <?php echo tierBadge($service['tier']); ?>
                            
                            <?php if ($service['description']): ?>
                                <p class="service-description"><?php echo clean($service['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="service-price-box">
                            <div class="price-amount">
                                <?php echo formatCurrency($service['sell_price']); ?>
                            </div>
                            <div class="price-unit">par 1000</div>
                        </div>
                    </div>
                    
                    <div class="service-specs">
                        <span>
                            <?php echo getIcon('followers', false, 'sm'); ?>
                            Min: <strong><?php echo number_format($service['min_quantity']); ?></strong>
                        </span>
                        <span>
                            <?php echo getIcon('followers', false, 'sm'); ?>
                            Max: <strong><?php echo number_format($service['max_quantity']); ?></strong>
                        </span>
                        <?php if ($service['drop_rate'] !== 'Unknown'): ?>
                            <span>
                                <?php echo getIcon('chart', false, 'sm'); ?>
                                Drop: <strong><?php echo $service['drop_rate']; ?></strong>
                            </span>
                        <?php endif; ?>
                        <?php if ($service['refill_days']): ?>
                            <span>
                                <?php echo getIcon('shield', false, 'sm'); ?>
                                Refill: <strong><?php echo $service['refill_days']; ?> jours</strong>
                            </span>
                        <?php endif; ?>
                        <?php if ($service['speed']): ?>
                            <span>
                                <?php echo getIcon('rocket', true, 'sm'); ?>
                                <strong><?php echo $service['speed']; ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Order Form -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <?php echo getIcon('edit'); ?>
                        Détails de la commande
                    </h2>
                </div>
                <form method="POST" class="order-form">
                    <div class="card-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                        
                        <div class="form-group">
                            <label for="link">
                                <?php echo getIcon('link', false, 'sm'); ?>
                                Lien cible *
                            </label>
                            <input type="url" 
                                   id="link" 
                                   name="link" 
                                   required 
                                   placeholder="https://instagram.com/username"
                                   value="<?php echo htmlspecialchars($_POST['link'] ?? ''); ?>"
                                   class="form-control">
                            <small class="form-hint">
                                <?php echo getIcon('info', false, 'sm'); ?>
                                Le lien doit être valide et accessible publiquement
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity">
                                <?php echo getIcon('followers', false, 'sm'); ?>
                                Quantité *
                            </label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   required 
                                   min="<?php echo $service['min_quantity']; ?>"
                                   max="<?php echo $service['max_quantity']; ?>"
                                   value="<?php echo $_POST['quantity'] ?? $service['min_quantity']; ?>"
                                   oninput="calculateTotal()"
                                   class="form-control">
                            <small class="form-hint">
                                Min: <?php echo number_format($service['min_quantity']); ?> - 
                                Max: <?php echo number_format($service['max_quantity']); ?>
                            </small>
                        </div>
                        
                        <!-- Price Calculator -->
                        <div class="price-calculator">
                            <div class="calculator-row">
                                <div>
                                    <?php echo getIcon('money', true, 'lg'); ?>
                                    <div>
                                        <div class="calculator-label">Prix total</div>
                                        <div id="totalPrice" class="calculator-value">$0.00</div>
                                    </div>
                                </div>
                                <div>
                                    <?php echo getIcon('wallet', false, 'lg'); ?>
                                    <div>
                                        <div class="calculator-label">Votre solde</div>
                                        <div class="calculator-value">
                                            <?php echo formatCurrency($user['balance']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($user['balance'] < ($service['sell_price'] * $service['min_quantity'] / 1000)): ?>
                            <div class="alert alert-warning">
                                <?php echo getIcon('warning'); ?>
                                <div>
                                    <strong>Solde insuffisant</strong><br>
                                    Vous devez recharger votre compte pour passer cette commande.
                                    <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="alert-link">
                                        Ajouter des fonds <?php echo getIcon('arrow', false, 'sm'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBtn">
                            <?php echo getIcon('rocket', true); ?>
                            Commander maintenant
                        </button>
                        
                        <p class="form-notice">
                            <?php echo getIcon('shield', false, 'sm'); ?>
                            En passant cette commande, vous acceptez nos 
                            <a href="<?php echo SITE_URL; ?>/pages/terms.php">conditions d'utilisation</a>
                        </p>
                    </div>
                </form>
            </div>
            
        <?php endif; ?>
        
    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->

<script>
    const pricePerThousand = <?php echo $service ? $service['sell_price'] : 0; ?>;
    const userBalance = <?php echo $user['balance']; ?>;
    
    function calculateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const total = (quantity / 1000) * pricePerThousand;
        
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
        
        const submitBtn = document.getElementById('submitBtn');
        if (total > userBalance) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<?php echo addslashes(getIcon("error")); ?> Solde insuffisant';
            submitBtn.classList.add('btn-disabled');
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<?php echo addslashes(getIcon("rocket", true)); ?> Commander maintenant';
            submitBtn.classList.remove('btn-disabled');
        }
    }
    
    // Calculer au chargement
    calculateTotal();
</script>

<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>
