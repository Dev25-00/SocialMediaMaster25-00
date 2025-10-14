<?php
/**
 * SMM Mastery - Synchronisation Services API V2
 * Date: 13 Octobre 2025
 * Version: 2.0
 * 
 * SYNCHRONISATION COMPLÈTE AVEC TOUTES LES DONNÉES API
 * - Parsing des descriptions riches
 * - Stockage de TOUS les champs API (dripfeed, cancel, category, refill)
 * - Extraction des métadonnées (quality, location, speed, etc.)
 * 
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\api\
 */

require_once '../config.php';
require_once '../functions.php';
require_once '../api/SMMFollowsAPI.php';

// ⚡ Augmenter les limites d'exécution
set_time_limit(600); // 10 minutes au lieu de 5
ini_set('memory_limit', '512M'); // Augmenter la mémoire

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$result = null;
$error = '';
$processing = false;

// Si POST, lancer la synchronisation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $processing = true;
    
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
            'total' => count($services),
            'new' => 0,
            'updated' => 0,
            'errors' => 0,
            'details' => []
        ];
        
        // Préparer les requêtes
        $check_stmt = $pdo->prepare("SELECT * FROM services WHERE provider_id = ?");
        
        // INSERT avec TOUS les champs (incluant les nouveaux)
        $insert_stmt = $pdo->prepare("
            INSERT INTO services (
                provider_id, provider_name, category, api_category, platform, name, description,
                tier, quality, location, min_quantity, max_quantity, cost_price, sell_price,
                drop_rate, refill_days, refill_type, speed, average_time,
                dripfeed, cancel, is_active, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
        ");
        
        // UPDATE avec TOUS les champs
        $update_stmt = $pdo->prepare("
            UPDATE services SET
                name = ?,
                api_category = ?,
                description = ?,
                quality = ?,
                location = ?,
                min_quantity = ?,
                max_quantity = ?,
                cost_price = ?,
                sell_price = ?,
                refill_type = ?,
                speed = ?,
                average_time = ?,
                dripfeed = ?,
                cancel = ?,
                is_active = 1,
                updated_at = NOW()
            WHERE provider_id = ?
        ");
        
        // Traiter chaque service
        foreach ($services as $service) {
            try {
                // 1. DONNÉES API BRUTES
                $provider_id = $service['service'];
                $name = $service['name'];
                $type = $service['type'] ?? 'Default';
                $rate = floatval($service['rate']);
                $min = intval($service['min']);
                $max = intval($service['max']);
                $api_category = $service['category'] ?? null; // ✅ NOUVEAU
                $dripfeed = isset($service['dripfeed']) ? (bool)$service['dripfeed'] : false; // ✅ NOUVEAU
                $cancel = isset($service['cancel']) ? (bool)$service['cancel'] : false; // ✅ NOUVEAU
                $refill_api = isset($service['refill']) ? (bool)$service['refill'] : false; // ✅ NOUVEAU
                
                // 2. EXTRACTION PLATEFORME & CATÉGORIE
                $platform = SMMFollowsAPI::extractPlatform($name);
                $category = SMMFollowsAPI::mapCategory($name, $type);
                $tier = SMMFollowsAPI::determineTier($rate);
                $sell_price = SMMFollowsAPI::calculateSellPrice($rate, $tier);
                $drop_rate = SMMFollowsAPI::determineDropRate($name);
                $refill_days = SMMFollowsAPI::extractRefillDays($name);
                
                // 3. PARSING DESCRIPTION RICHE ✅ NOUVEAU
                $parsed = SMMFollowsAPI::parseRichDescription($name);
                $quality = $parsed['quality'];
                $location = $parsed['location'];
                $speed = $parsed['speed'] ?: 'Variable';
                $average_time = $parsed['average_time'];
                $refill_info = $parsed['refill_info'];
                
                // 4. DESCRIPTION INTELLIGENTE
                // Utiliser le nom complet comme description (contient toutes les infos)
                $description = $name;
                
                // 5. REFILL TYPE (button, lifetime, guaranteed, etc.)
                $refill_type = null;
                if ($refill_info) {
                    $refill_type = $refill_info;
                } elseif ($refill_api) {
                    $refill_type = 'Available';
                } elseif ($refill_days > 0) {
                    $refill_type = $refill_days . ' Days';
                }
                
                // Vérifier si le service existe
                $check_stmt->execute([$provider_id]);
                $existing = $check_stmt->fetch();
                
                if ($existing) {
                    // Mettre à jour le service existant
                    $update_stmt->execute([
                        $name,
                        $api_category,
                        $description,
                        $quality,
                        $location,
                        $min,
                        $max,
                        $rate,
                        $sell_price,
                        $refill_type,
                        $speed,
                        $average_time,
                        $dripfeed ? 1 : 0,
                        $cancel ? 1 : 0,
                        $provider_id
                    ]);
                    $stats['updated']++;
                } else {
                    // Insérer nouveau service
                    $insert_stmt->execute([
                        $provider_id,
                        'SMMFollows',
                        $category,
                        $api_category,
                        $platform,
                        $name,
                        $description,
                        $tier,
                        $quality,
                        $location,
                        $min,
                        $max,
                        $rate,
                        $sell_price,
                        $drop_rate,
                        $refill_days,
                        $refill_type,
                        $speed,
                        $average_time,
                        $dripfeed ? 1 : 0,
                        $cancel ? 1 : 0
                    ]);
                    $stats['new']++;
                    
                    // Ajouter aux détails (max 10 exemples)
                    if (count($stats['details']) < 10) {
                        $stats['details'][] = [
                            'platform' => $platform,
                            'tier' => $tier,
                            'quality' => $quality,
                            'location' => $location,
                            'speed' => $speed,
                            'dripfeed' => $dripfeed,
                            'cancel' => $cancel
                        ];
                    }
                }
                
            } catch (Exception $e) {
                $stats['errors']++;
                error_log("Sync service error (ID: $provider_id): " . $e->getMessage());
            }
        }
        
        $result = $stats;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synchronisation Services V2 - SMM Mastery</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .sync-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #fff;
            margin: 0 0 10px 0;
            font-size: 2em;
        }
        .version-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }
        .features-list {
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid #10b981;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .features-list h3 {
            color: #10b981;
            margin-top: 0;
        }
        .features-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .features-list li {
            padding: 8px 0;
            color: #e5e7eb;
        }
        .features-list li::before {
            content: "✅ ";
            margin-right: 10px;
        }
        .sync-button {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .sync-button:hover {
            transform: translateY(-3px);
        }
        .sync-button:disabled {
            background: #4b5563;
            cursor: not-allowed;
        }
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid;
        }
        .stat-card.total { border-color: #3b82f6; }
        .stat-card.new { border-color: #10b981; }
        .stat-card.updated { border-color: #f59e0b; }
        .stat-card.errors { border-color: #ef4444; }
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            color: #9ca3af;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        .details-table {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .details-table th {
            background: rgba(102, 126, 234, 0.3);
            padding: 15px;
            text-align: left;
            color: #fff;
        }
        .details-table td {
            padding: 12px 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #e5e7eb;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge.yes {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        .badge.no {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af;
        }
        .loader {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 30px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .processing-text {
            text-align: center;
            color: #667eea;
            font-size: 1.2em;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="sync-container">
        <div class="header">
            <h1>🔄 Synchronisation Services</h1>
            <span class="version-badge">VERSION 2.0 - SYNCHRONISATION COMPLÈTE</span>
        </div>

        <div class="features-list">
            <h3>🆕 Nouveautés V2.0</h3>
            <ul>
                <li>Parsing des descriptions riches (Quality, Location, Speed)</li>
                <li>Stockage des champs API : dripfeed, cancel, category</li>
                <li>Extraction automatique des métadonnées (refill type, average time)</li>
                <li>Reconnaissance de TOUTES les plateformes (Kick, Rumble, BlueSky, etc.)</li>
                <li>Descriptions complètes au lieu de génériques</li>
                <li>Support des emojis et formatage API</li>
            </ul>
        </div>

        <?php if ($error): ?>
        <div style="background: rgba(239, 68, 68, 0.1); border: 2px solid #ef4444; border-radius: 10px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #ef4444; margin-top: 0;">❌ Erreur</h3>
            <p style="color: #fca5a5;"><?= htmlspecialchars($error) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($processing && !$result): ?>
        <div class="loader"></div>
        <div class="processing-text">⏳ Synchronisation en cours...</div>
        <?php endif; ?>

        <?php if ($result): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 2px solid #10b981; border-radius: 10px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #10b981; margin-top: 0;">✅ Synchronisation terminée</h3>
            
            <div class="results-grid">
                <div class="stat-card total">
                    <div class="stat-label">Total API</div>
                    <div class="stat-number" style="color: #3b82f6;"><?= $result['total'] ?></div>
                </div>
                <div class="stat-card new">
                    <div class="stat-label">Nouveaux</div>
                    <div class="stat-number" style="color: #10b981;"><?= $result['new'] ?></div>
                </div>
                <div class="stat-card updated">
                    <div class="stat-label">Mis à jour</div>
                    <div class="stat-number" style="color: #f59e0b;"><?= $result['updated'] ?></div>
                </div>
                <div class="stat-card errors">
                    <div class="stat-label">Erreurs</div>
                    <div class="stat-number" style="color: #ef4444;"><?= $result['errors'] ?></div>
                </div>
            </div>

            <?php if (!empty($result['details'])): ?>
            <h4 style="color: #fff; margin-top: 30px;">📊 Échantillon de nouveaux services :</h4>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Plateforme</th>
                        <th>Tier</th>
                        <th>Qualité</th>
                        <th>Localisation</th>
                        <th>Vitesse</th>
                        <th>Dripfeed</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result['details'] as $detail): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($detail['platform']) ?></strong></td>
                        <td><?= htmlspecialchars($detail['tier']) ?></td>
                        <td><?= htmlspecialchars($detail['quality'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($detail['location'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($detail['speed']) ?></td>
                        <td>
                            <span class="badge <?= $detail['dripfeed'] ? 'yes' : 'no' ?>">
                                <?= $detail['dripfeed'] ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $detail['cancel'] ? 'yes' : 'no' ?>">
                                <?= $detail['cancel'] ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" class="sync-button" <?= $processing ? 'disabled' : '' ?>>
                <?= $processing ? '⏳ Synchronisation...' : '🚀 Lancer la synchronisation' ?>
            </button>
        </form>

        <div style="text-align: center; margin-top: 30px;">
            <a href="services.php" style="color: #94a3b8; margin-right: 20px;">📋 Voir les services</a>
            <a href="dashboard.php" style="color: #94a3b8;">← Tableau de bord</a>
        </div>
    </div>
</body>
</html>
