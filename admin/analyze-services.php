<?php
/**
 * INCLUSION: Sidebar admin navigation
 * FICHIER: sidebar.php
 * DOCUMENTATION: DOCS_DEV_TO_PROD/ARCHITECTURE_INCLUDES_VISUELLE.md
 */
/**
 * SMM Mastery - Analyse des services
 * Script pour analyser toutes les valeurs distinctes dans la BDD
 * Date: 12 Octobre 2025
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyse Services - SMM Mastery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .stat-card h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin: 20px 0;
        }
        .value-list {
            list-style: none;
        }
        .value-item {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .value-item:hover {
            background: #e9ecef;
        }
        .value-name {
            font-weight: 600;
            color: #495057;
        }
        .value-count {
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .null-value {
            color: #dc3545;
            font-style: italic;
        }
        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0,0,0,0.3);
        }
    </style>
   
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">

    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <div class="main-content">
    <div class="container">
        <a href="dashboard.php" class="back-btn">← Retour Dashboard</a>
        <h1>📊 Analyse des Services</h1>
        
        <div class="stats-grid">
            <!-- Total services -->
            <div class="stat-card">
                <h2>📦 Total Services</h2>
                <?php
                $total = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
                ?>
                <div class="stat-value"><?php echo number_format($total); ?></div>
            </div>
            
            <!-- Plateformes -->
            <div class="stat-card">
                <h2>🌐 Plateformes</h2>
                <?php
                $platforms = $pdo->query("
                    SELECT platform, COUNT(*) as count 
                    FROM services 
                    WHERE is_active = 1 
                    GROUP BY platform 
                    ORDER BY count DESC
                ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <?php foreach ($platforms as $item): ?>
                        <li class="value-item">
                            <span class="value-name"><?php echo htmlspecialchars($item['platform']); ?></span>
                            <span class="value-count"><?php echo number_format($item['count']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Tiers -->
            <div class="stat-card">
                <h2>⭐ Qualités (Tiers)</h2>
                <?php
                $tiers = $pdo->query("
                    SELECT tier, COUNT(*) as count 
                    FROM services 
                    WHERE is_active = 1 
                    GROUP BY tier 
                    ORDER BY 
                        CASE tier
                            WHEN 'Budget' THEN 1
                            WHEN 'Standard' THEN 2
                            WHEN 'Premium' THEN 3
                            WHEN 'Ultimate' THEN 4
                            ELSE 5
                        END
                ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <?php foreach ($tiers as $item): ?>
                        <li class="value-item">
                            <span class="value-name"><?php echo htmlspecialchars($item['tier']); ?></span>
                            <span class="value-count"><?php echo number_format($item['count']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Drop Rate -->
            <div class="stat-card">
                <h2>🛡️ Drop Rate</h2>
                <?php
                $drops = $pdo->query("
                    SELECT 
                        CASE 
                            WHEN drop_rate IS NULL OR drop_rate = '' THEN 'Non spécifié'
                            ELSE drop_rate
                        END as drop_rate,
                        COUNT(*) as count 
                    FROM services 
                    WHERE is_active = 1 
                    GROUP BY drop_rate 
                    ORDER BY count DESC
                ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <?php foreach ($drops as $item): ?>
                        <li class="value-item">
                            <span class="value-name <?php echo $item['drop_rate'] === 'Non spécifié' ? 'null-value' : ''; ?>">
                                <?php echo htmlspecialchars($item['drop_rate']); ?>
                            </span>
                            <span class="value-count"><?php echo number_format($item['count']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Refill Days - IMPORTANT -->
            <div class="stat-card">
                <h2>🔄 Refill Days (TOUTES LES VALEURS)</h2>
                <?php
                $refills = $pdo->query("
                    SELECT 
                        CASE 
                            WHEN refill_days IS NULL THEN 'NULL (Sans refill)'
                            ELSE CAST(refill_days AS CHAR)
                        END as refill_display,
                        refill_days,
                        COUNT(*) as count 
                    FROM services 
                    WHERE is_active = 1 
                    GROUP BY refill_days 
                    ORDER BY 
                        CASE 
                            WHEN refill_days IS NULL THEN -1
                            ELSE refill_days
                        END ASC
                ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <?php foreach ($refills as $item): ?>
                        <li class="value-item">
                            <span class="value-name <?php echo $item['refill_days'] === null ? 'null-value' : ''; ?>">
                                <?php 
                                if ($item['refill_days'] === null) {
                                    echo '❌ Sans refill';
                                } elseif ($item['refill_days'] == 0) {
                                    echo '❌ 0 jour (Sans refill)';
                                } else {
                                    echo '✅ ' . htmlspecialchars($item['refill_days']) . ' jours';
                                }
                                ?>
                            </span>
                            <span class="value-count"><?php echo number_format($item['count']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Categories (Actions) -->
            <div class="stat-card">
                <h2>🎯 Catégories (Actions)</h2>
                <?php
                $categories = $pdo->query("
                    SELECT 
                        CASE 
                            WHEN category IS NULL OR category = '' THEN 'Non catégorisé'
                            ELSE category
                        END as category,
                        COUNT(*) as count 
                    FROM services 
                    WHERE is_active = 1 
                    GROUP BY category 
                    ORDER BY count DESC
                    LIMIT 20
                ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <?php foreach ($categories as $item): ?>
                        <li class="value-item">
                            <span class="value-name <?php echo $item['category'] === 'Non catégorisé' ? 'null-value' : ''; ?>">
                                <?php echo htmlspecialchars($item['category']); ?>
                            </span>
                            <span class="value-count"><?php echo number_format($item['count']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Prix Min/Max -->
            <div class="stat-card">
                <h2>💰 Fourchette Prix</h2>
                <?php
                $prices = $pdo->query("
                    SELECT 
                        MIN(sell_price) as min_price,
                        MAX(sell_price) as max_price,
                        AVG(sell_price) as avg_price
                    FROM services 
                    WHERE is_active = 1
                ")->fetch(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <li class="value-item">
                        <span class="value-name">Prix minimum</span>
                        <span class="value-count">$<?php echo number_format($prices['min_price'], 2); ?></span>
                    </li>
                    <li class="value-item">
                        <span class="value-name">Prix maximum</span>
                        <span class="value-count">$<?php echo number_format($prices['max_price'], 2); ?></span>
                    </li>
                    <li class="value-item">
                        <span class="value-name">Prix moyen</span>
                        <span class="value-count">$<?php echo number_format($prices['avg_price'], 2); ?></span>
                    </li>
                </ul>
            </div>
            
            <!-- Quantités -->
            <div class="stat-card">
                <h2>📊 Quantités Min/Max</h2>
                <?php
                $quantities = $pdo->query("
                    SELECT 
                        MIN(min_quantity) as min_min,
                        MAX(min_quantity) as max_min,
                        MIN(max_quantity) as min_max,
                        MAX(max_quantity) as max_max
                    FROM services 
                    WHERE is_active = 1
                ")->fetch(PDO::FETCH_ASSOC);
                ?>
                <ul class="value-list">
                    <li class="value-item">
                        <span class="value-name">Min quantity (plus petit)</span>
                        <span class="value-count"><?php echo number_format($quantities['min_min']); ?></span>
                    </li>
                    <li class="value-item">
                        <span class="value-name">Min quantity (plus grand)</span>
                        <span class="value-count"><?php echo number_format($quantities['max_min']); ?></span>
                    </li>
                    <li class="value-item">
                        <span class="value-name">Max quantity (plus petit)</span>
                        <span class="value-count"><?php echo number_format($quantities['min_max']); ?></span>
                    </li>
                    <li class="value-item">
                        <span class="value-name">Max quantity (plus grand)</span>
                        <span class="value-count"><?php echo number_format($quantities['max_max']); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
