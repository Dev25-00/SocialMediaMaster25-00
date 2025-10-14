<?php
/**
 * SMM Mastery - Synchronisation des services
 * À exécuter via CRON toutes les 6 heures
 * 
 * Commande CRON: 0 */6 * * * php /path/to/smm-Mastery/cron/sync-services.php
 */

// Permettre l'exécution uniquement en ligne de commande ou via CRON
if (php_sapi_name() !== 'cli' && !isset($_GET['cron_key'])) {
    die('Accès non autorisé');
}

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/functions.php';
require_once dirname(__DIR__) . '/api/SMMFollowsAPI.php';

echo "[" . date('Y-m-d H:i:s') . "] Début de la synchronisation...\n";

try {
    // Vérifier si l'API key est configurée
    $api_key = getSetting($pdo, 'smmfollows_api_key', SMMFOLLOWS_API_KEY);
    
    if (empty($api_key)) {
        throw new Exception('API Key SMMFollows non configurée');
    }
    
    // Initialiser l'API
    $api = new SMMFollowsAPI($api_key);
    
    // Récupérer tous les services depuis SMMFollows
    echo "Récupération des services depuis SMMFollows...\n";
    $services = $api->getServices();
    
    if (empty($services)) {
        throw new Exception('Aucun service retourné par l\'API');
    }
    
    echo "Services reçus: " . count($services) . "\n";
    
    $stats = [
        'new' => 0,
        'updated' => 0,
        'disabled' => 0,
        'errors' => 0
    ];
    
    // Préparer les requêtes
    $check_stmt = $pdo->prepare("SELECT * FROM services WHERE provider_id = ?");
    $insert_stmt = $pdo->prepare("
        INSERT INTO services (
            provider_id, provider_name, category, platform, name, description,
            tier, min_quantity, max_quantity, cost_price, sell_price,
            drop_rate, refill_days, speed, is_active, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
    ");
    $update_stmt = $pdo->prepare("
        UPDATE services SET
            name = ?, min_quantity = ?, max_quantity = ?,
            cost_price = ?, sell_price = ?, is_active = 1, updated_at = NOW()
        WHERE provider_id = ?
    ");
    
    // Traiter chaque service
    foreach ($services as $service) {
        try {
            $provider_id = $service['service'];
            $name = $service['name'];
            $type = $service['type'] ?? 'Default';
            $rate = floatval($service['rate']); // Prix pour 1000
            $min = intval($service['min']);
            $max = intval($service['max']);
            
            // Extraire les informations
            $platform = SMMFollowsAPI::extractPlatform($name);
            $category = SMMFollowsAPI::mapCategory($name, $type);
            $tier = SMMFollowsAPI::determineTier($rate);
            $sell_price = SMMFollowsAPI::calculateSellPrice($rate, $tier);
            $drop_rate = SMMFollowsAPI::determineDropRate($name);
            $refill_days = SMMFollowsAPI::extractRefillDays($name);
            
            // Description basique
            $description = "Service de qualité $tier pour $platform";
            
            // Speed estimation
            $speed = 'Variable';
            if (stripos($name, 'instant') !== false) {
                $speed = 'Instantané';
            } elseif (stripos($name, 'fast') !== false) {
                $speed = 'Rapide (0-1h)';
            } elseif (stripos($name, 'slow') !== false) {
                $speed = 'Lent (24-48h)';
            }
            
            // Vérifier si le service existe
            $check_stmt->execute([$provider_id]);
            $existing = $check_stmt->fetch();
            
            if ($existing) {
                // Mettre à jour
                $update_stmt->execute([
                    $name, $min, $max, $rate, $sell_price, $provider_id
                ]);
                $stats['updated']++;
                
                if ($stats['updated'] % 10 == 0) {
                    echo "Mis à jour: {$stats['updated']} services\r";
                }
            } else {
                // Insérer nouveau service
                $insert_stmt->execute([
                    $provider_id, 'SMMFollows', $category, $platform, $name, $description,
                    $tier, $min, $max, $rate, $sell_price,
                    $drop_rate, $refill_days, $speed
                ]);
                $stats['new']++;
                echo "Nouveau service ajouté: $name\n";
            }
            
        } catch (Exception $e) {
            $stats['errors']++;
            error_log("Erreur service $provider_id: " . $e->getMessage());
        }
    }
    
    // Désactiver les services qui ne sont plus disponibles
    $provider_ids = array_column($services, 'service');
    if (!empty($provider_ids)) {
        $placeholders = implode(',', array_fill(0, count($provider_ids), '?'));
        $stmt = $pdo->prepare("
            UPDATE services 
            SET is_active = 0, updated_at = NOW()
            WHERE provider_id NOT IN ($placeholders) AND is_active = 1
        ");
        $stmt->execute($provider_ids);
        $stats['disabled'] = $stmt->rowCount();
    }
    
    echo "\n\n=== RÉSUMÉ DE LA SYNCHRONISATION ===\n";
    echo "Nouveaux services: {$stats['new']}\n";
    echo "Services mis à jour: {$stats['updated']}\n";
    echo "Services désactivés: {$stats['disabled']}\n";
    echo "Erreurs: {$stats['errors']}\n";
    echo "===================================\n\n";
    
    // Logger dans la base de données
    updateSetting($pdo, 'last_sync', date('Y-m-d H:i:s'));
    updateSetting($pdo, 'last_sync_stats', json_encode($stats));
    
    echo "[" . date('Y-m-d H:i:s') . "] Synchronisation terminée avec succès!\n";
    
} catch (Exception $e) {
    echo "[ERREUR] " . $e->getMessage() . "\n";
    error_log("CRON sync-services error: " . $e->getMessage());
    exit(1);
}
?>
