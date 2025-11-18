<?php
/**
 * SMM Mastery - Vérification des plateformes disponibles
 * Date: 13 Octobre 2025
 * 
 * Affiche toutes les plateformes avec leur nombre de services actifs
 */

require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

// Récupérer toutes les plateformes avec comptage
$query = "
    SELECT 
        platform, 
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive
    FROM services 
    GROUP BY platform 
    ORDER BY total DESC
";

$platforms = $pdo->query($query)->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateformes - SMM Mastery</title>
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <style>
        .platforms-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        .platform-card {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        .platform-card:hover {
            transform: translateY(-5px);
            border-color: rgba(102, 126, 234, 0.5);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        .platform-name {
            font-size: 1.1em;
            font-weight: bold;
            color: #fff;
            margin-bottom: 10px;
        }
        .platform-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
        }
        .stat {
            text-align: center;
        }
        .stat-number {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .stat-label {
            font-size: 0.8em;
            color: #9ca3af;
            text-transform: uppercase;
        }
        .stat.total { color: #3b82f6; }
        .stat.active { color: #10b981; }
        .stat.inactive { color: #ef4444; }
        .new-badge {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.7em;
            font-weight: bold;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .summary {
            background: rgba(59, 130, 246, 0.1);
            border: 2px solid #3b82f6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        .summary-stat {
            text-align: center;
        }
        .summary-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #3b82f6;
        }
        .summary-label {
            color: #9ca3af;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    
    <div class="platforms-container">
        <h1 style="text-align: center; color: #fff; margin-bottom: 10px;">
            🌍 Plateformes Disponibles
        </h1>
        <p style="text-align: center; color: #9ca3af; margin-bottom: 30px;">
            Liste complète des plateformes avec services actifs
        </p>

        <div class="summary">
            <h3 style="color: #3b82f6; margin-top: 0;">📊 Résumé</h3>
            <div class="summary-grid">
                <div class="summary-stat">
                    <div class="summary-number"><?= count($platforms) ?></div>
                    <div class="summary-label">Plateformes</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-number" style="color: #10b981;">
                        <?= array_sum(array_column($platforms, 'active')) ?>
                    </div>
                    <div class="summary-label">Services actifs</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-number" style="color: #f59e0b;">
                        <?= array_sum(array_column($platforms, 'total')) ?>
                    </div>
                    <div class="summary-label">Total services</div>
                </div>
            </div>
        </div>

        <div class="platforms-grid">
            <?php 
            // Nouvelles plateformes V2 à mettre en avant
            $new_platforms = ['Kick', 'Rumble', 'BlueSky', 'Kwai', 'Truth Social', 'Audiomack', 
                             'Chzzk', 'Square', 'Rutube', 'Apple Music'];
            
            foreach ($platforms as $p): 
                $is_new = in_array($p['platform'], $new_platforms);
            ?>
                <div class="platform-card">
                    <div class="platform-name">
                        <?= htmlspecialchars($p['platform']) ?>
                        <?php if ($is_new): ?>
                            <span class="new-badge">NEW</span>
                        <?php endif; ?>
                    </div>
                    <div class="platform-stats">
                        <div class="stat total">
                            <div class="stat-number"><?= $p['total'] ?></div>
                            <div class="stat-label">Total</div>
                        </div>
                        <div class="stat active">
                            <div class="stat-number"><?= $p['active'] ?></div>
                            <div class="stat-label">Actifs</div>
                        </div>
                        <?php if ($p['inactive'] > 0): ?>
                        <div class="stat inactive">
                            <div class="stat-number"><?= $p['inactive'] ?></div>
                            <div class="stat-label">Inactifs</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="sync-services.php" style="display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: bold;">
                🔄 Synchroniser les services
            </a>
            <a href="services.php" style="display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: bold; margin-left: 15px;">
                📋 Gérer les services
            </a>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" style="color: #94a3b8;">← Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
