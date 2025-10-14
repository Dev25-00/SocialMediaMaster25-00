<?php
/**
 * SMM Mastery - Vérification du statut des commandes
 * À exécuter via CRON toutes les 10 minutes
 * 
 * Commande CRON: */10 * * * * php /path/to/smm-Mastery/cron/check-orders.php
 */

// Permettre l'exécution uniquement en ligne de commande ou via CRON
if (php_sapi_name() !== 'cli' && !isset($_GET['cron_key'])) {
    die('Accès non autorisé');
}

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/functions.php';
require_once dirname(__DIR__) . '/api/SMMFollowsAPI.php';

echo "[" . date('Y-m-d H:i:s') . "] Début de la vérification des commandes...\n";

try {
    // Vérifier si l'API key est configurée
    $api_key = getSetting($pdo, 'smmfollows_api_key', SMMFOLLOWS_API_KEY);
    
    if (empty($api_key)) {
        throw new Exception('API Key SMMFollows non configurée');
    }
    
    // Initialiser l'API
    $api = new SMMFollowsAPI($api_key);
    
    // Récupérer les commandes en attente ou en cours
    $stmt = $pdo->query("
        SELECT * FROM orders 
        WHERE status IN ('pending', 'processing') 
        AND provider_order_id IS NOT NULL
        ORDER BY created_at DESC
        LIMIT 100
    ");
    $orders = $stmt->fetchAll();
    
    if (empty($orders)) {
        echo "Aucune commande à vérifier\n";
        exit(0);
    }
    
    echo "Commandes à vérifier: " . count($orders) . "\n";
    
    $stats = [
        'completed' => 0,
        'processing' => 0,
        'partial' => 0,
        'canceled' => 0,
        'errors' => 0
    ];
    
    // Grouper les commandes par lot de 100
    $batches = array_chunk($orders, 100);
    
    foreach ($batches as $batch) {
        // Récupérer les IDs provider
        $provider_ids = array_column($batch, 'provider_order_id');
        
        try {
            // Vérifier le statut de plusieurs commandes à la fois
            $statuses = $api->getMultipleOrderStatus($provider_ids);
            
            foreach ($batch as $order) {
                $provider_id = $order['provider_order_id'];
                
                // Trouver le statut correspondant
                $status_data = null;
                foreach ($statuses as $status) {
                    if (isset($status['order']) && $status['order'] == $provider_id) {
                        $status_data = $status;
                        break;
                    }
                }
                
                if (!$status_data) {
                    echo "Statut introuvable pour commande #{$order['order_number']}\n";
                    continue;
                }
                
                // Mapper le statut
                $new_status = mapProviderStatus($status_data['status'] ?? 'Pending');
                $start_count = $status_data['start_count'] ?? $order['start_count'];
                $remains = $status_data['remains'] ?? 0;
                
                // Mettre à jour la commande si le statut a changé
                if ($new_status !== $order['status']) {
                    $update_stmt = $pdo->prepare("
                        UPDATE orders SET
                            status = ?,
                            start_count = ?,
                            remains = ?,
                            completed_at = CASE WHEN ? = 'completed' THEN NOW() ELSE completed_at END
                        WHERE id = ?
                    ");
                    
                    $update_stmt->execute([
                        $new_status,
                        $start_count,
                        $remains,
                        $new_status,
                        $order['id']
                    ]);
                    
                    $stats[$new_status]++;
                    echo "Commande #{$order['order_number']}: {$order['status']} → $new_status\n";
                    
                    // Envoyer notification email (optionnel)
                    if ($new_status === 'completed') {
                        // sendEmail($user_email, "Commande complétée", "Votre commande #{$order['order_number']} est terminée!");
                    }
                }
            }
            
        } catch (Exception $e) {
            $stats['errors']++;
            error_log("Erreur vérification batch: " . $e->getMessage());
        }
        
        // Pause pour éviter de surcharger l'API
        sleep(1);
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "Complétées: {$stats['completed']}\n";
    echo "En cours: {$stats['processing']}\n";
    echo "Partielles: {$stats['partial']}\n";
    echo "Annulées: {$stats['canceled']}\n";
    echo "Erreurs: {$stats['errors']}\n";
    echo "==============\n\n";
    
    echo "[" . date('Y-m-d H:i:s') . "] Vérification terminée!\n";
    
} catch (Exception $e) {
    echo "[ERREUR] " . $e->getMessage() . "\n";
    error_log("CRON check-orders error: " . $e->getMessage());
    exit(1);
}

/**
 * Mapper le statut du provider vers notre statut
 */
function mapProviderStatus($provider_status) {
    $status_map = [
        'Pending' => 'pending',
        'In progress' => 'processing',
        'Processing' => 'processing',
        'Completed' => 'completed',
        'Partial' => 'partial',
        'Canceled' => 'canceled',
        'Refunded' => 'refunded'
    ];
    
    return $status_map[$provider_status] ?? 'pending';
}
?>
