<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../api/SMMFollowsAPI.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$result = null;
$error = '';

// Si POST, lancer la synchronisation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Vérifier l'API key
        $api_key = getSetting($pdo, 'smmfollows_api_key', '');
        
        if (empty($api_key)) {
            throw new Exception('API Key non configurée');
        }
        
        // Initialiser l'API
        $api = new SMMFollowsAPI($api_key);
        
        // Récupérer tous les services depuis SMMFollows
        $services = $api->getServices();
        
        if (empty($services)) {
            throw new Exception('Aucun service retourné par l\'API');
        }
        
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
                $rate = floatval($service['rate']);
                $min = intval($service['min']);
                $max = intval($service['max']);
                
                // Extraire les informations
                $platform = SMMFollowsAPI::extractPlatform($name);
                $category = SMMFollowsAPI::mapCategory($name, $type);
                $tier = SMMFollowsAPI::determineTier($rate);
                $sell_price = SMMFollowsAPI::calculateSellPrice($rate, $tier);
                $drop_rate = SMMFollowsAPI::determineDropRate($name);
                $refill_days = SMMFollowsAPI::extractRefillDays($name);
                
                $description = "Service de qualité $tier pour $platform";
                $speed = 'Variable';
                
                // Vérifier si le service existe
                $check_stmt->execute([$provider_id]);
                $existing = $check_stmt->fetch();
                
                if ($existing) {
                    // Mettre à jour
                    $update_stmt->execute([
                        $name, $min, $max, $rate, $sell_price, $provider_id
                    ]);
                    $stats['updated']++;
                } else {
                    // Insérer nouveau service
                    $insert_stmt->execute([
                        $provider_id, 'SMMFollows', $category, $platform, $name, $description,
                        $tier, $min, $max, $rate, $sell_price,
                        $drop_rate, $refill_days, $speed
                    ]);
                    $stats['new']++;
                }
                
            } catch (Exception $e) {
                $stats['errors']++;
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
        
        // Sauvegarder les stats
        updateSetting($pdo, 'last_sync', date('Y-m-d H:i:s'));
        updateSetting($pdo, 'last_sync_stats', json_encode($stats));
        
        $result = $stats;
        
    } catch (Exception $e) {
        $error = 'Erreur : ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synchronisation Services - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/fixes.css">
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>🔄 Synchronisation des Services</h1>
            </div>
            <div class="top-bar-right">
                <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result): ?>
            <div class="alert alert-success">
                <strong>✅ Synchronisation terminée avec succès !</strong>
            </div>
            
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>📊 Résumé de la Synchronisation</h2>
                </div>
                <div style="padding: 30px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div style="text-align: center; padding: 20px; background: #d1fae5; border-radius: 10px;">
                            <div style="font-size: 36px; font-weight: 700; color: #065f46;">
                                <?php echo $result['new']; ?>
                            </div>
                            <div style="color: #065f46; margin-top: 5px;">Nouveaux services</div>
                        </div>
                        <div style="text-align: center; padding: 20px; background: #dbeafe; border-radius: 10px;">
                            <div style="font-size: 36px; font-weight: 700; color: #1e40af;">
                                <?php echo $result['updated']; ?>
                            </div>
                            <div style="color: #1e40af; margin-top: 5px;">Services mis à jour</div>
                        </div>
                        <div style="text-align: center; padding: 20px; background: #fef3c7; border-radius: 10px;">
                            <div style="font-size: 36px; font-weight: 700; color: #92400e;">
                                <?php echo $result['disabled']; ?>
                            </div>
                            <div style="color: #92400e; margin-top: 5px;">Services désactivés</div>
                        </div>
                        <div style="text-align: center; padding: 20px; background: #fee2e2; border-radius: 10px;">
                            <div style="font-size: 36px; font-weight: 700; color: #991b1b;">
                                <?php echo $result['errors']; ?>
                            </div>
                            <div style="color: #991b1b; margin-top: 5px;">Erreurs</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; text-align: center;">
                        <a href="../services/index.php" class="btn btn-primary" target="_blank">
                            Voir les services →
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div style="max-width: 800px;">
            
            <div class="card">
                <div style="padding: 40px; text-align: center;">
                    <?php if (!$result): ?>
                        <div style="font-size: 80px; margin-bottom: 20px;">🔄</div>
                        <h2 style="margin-bottom: 15px;">Synchroniser les services</h2>
                        <p style="color: #6b7280; margin-bottom: 30px;">
                            Cette action va récupérer tous les services disponibles sur SMMFollows<br>
                            et mettre à jour votre base de données.
                        </p>
                        
                        <div style="background: #dbeafe; padding: 20px; border-radius: 12px; margin-bottom: 30px; text-align: left;">
                            <strong style="color: #1e40af;">📋 Ce qui va être fait :</strong>
                            <ul style="color: #1e40af; margin-top: 10px; padding-left: 20px;">
                                <li>Récupération de tous les services SMMFollows</li>
                                <li>Ajout des nouveaux services</li>
                                <li>Mise à jour des prix et quantités</li>
                                <li>Désactivation des services indisponibles</li>
                                <li>Calcul automatique des marges</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <button type="submit" class="btn btn-primary btn-lg" style="padding: 15px 40px; font-size: 18px;">
                                🚀 Lancer la synchronisation
                            </button>
                        </form>
                        
                        <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
                            ⏱️ Durée estimée : 1-2 minutes
                        </p>
                    <?php else: ?>
                        <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
                        <h2 style="margin-bottom: 15px;">Synchronisation terminée !</h2>
                        <p style="color: #6b7280; margin-bottom: 30px;">
                            Vos services sont maintenant à jour.
                        </p>
                        <a href="dashboard.php" class="btn btn-primary btn-lg">
                            Retour au dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$result): ?>
            <div class="card">
                <div class="card-header">
                    <h2>💡 Conseils</h2>
                </div>
                <div style="padding: 30px;">
                    <ul style="color: #6b7280; line-height: 1.8;">
                        <li>✅ Lancez cette synchronisation au moins 1 fois par jour</li>
                        <li>✅ Vous pouvez aussi la programmer en CRON (automatique)</li>
                        <li>✅ La synchronisation ne supprime jamais les services, elle les désactive seulement</li>
                        <li>✅ Les prix de vente sont calculés automatiquement selon les tiers</li>
                    </ul>
                    
                    <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <strong style="color: #92400e;">⚠️ Important :</strong>
                        <p style="color: #92400e; margin-top: 8px; font-size: 14px;">
                            Assurez-vous que votre API Key SMMFollows est correctement configurée avant de lancer la synchronisation.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
