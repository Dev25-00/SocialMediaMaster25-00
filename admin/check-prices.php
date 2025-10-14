<?php
/**
 * Script de vérification des services - Trouver les prix anormaux
 */
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    die('Accès non autorisé');
}

echo "<h1>🔍 Analyse des services - Prix anormaux</h1>";

// Chercher les services avec des prix > 1000$
$stmt = $pdo->query("
    SELECT 
        id,
        provider_id,
        name,
        platform,
        tier,
        sell_price,
        cost_price,
        min_quantity,
        max_quantity,
        drop_rate,
        refill_days,
        is_active
    FROM services
    WHERE sell_price > 1000
    ORDER BY sell_price DESC
    LIMIT 50
");

$expensive_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($expensive_services)) {
    echo "<p>✅ Aucun service avec un prix > 1000$ trouvé.</p>";
} else {
    echo "<p>⚠️ <strong>" . count($expensive_services) . " service(s)</strong> avec prix > 1000$ :</p>";
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>ID</th>";
    echo "<th>Provider ID</th>";
    echo "<th>Platform</th>";
    echo "<th>Tier</th>";
    echo "<th>Prix vente</th>";
    echo "<th>Prix coût</th>";
    echo "<th>Min-Max</th>";
    echo "<th>Drop</th>";
    echo "<th>Refill</th>";
    echo "<th>Actif</th>";
    echo "</tr>";
    
    foreach ($expensive_services as $service) {
        $price_color = $service['sell_price'] > 10000 ? 'red' : ($service['sell_price'] > 5000 ? 'orange' : 'blue');
        
        echo "<tr>";
        echo "<td>{$service['id']}</td>";
        echo "<td>{$service['provider_id']}</td>";
        echo "<td><strong>{$service['platform']}</strong></td>";
        echo "<td><span style='padding: 2px 8px; background: #667eea; color: white; border-radius: 4px;'>{$service['tier']}</span></td>";
        echo "<td style='color: {$price_color}; font-weight: bold;'>" . number_format($service['sell_price'], 2) . " $</td>";
        echo "<td>" . number_format($service['cost_price'], 2) . " $</td>";
        echo "<td>{$service['min_quantity']} - {$service['max_quantity']}</td>";
        echo "<td>{$service['drop_rate']}</td>";
        echo "<td>{$service['refill_days']} j</td>";
        echo "<td>" . ($service['is_active'] ? '✅' : '❌') . "</td>";
        echo "</tr>";
        
        // Afficher le nom du service dans une ligne séparée
        echo "<tr>";
        echo "<td colspan='10' style='background: #f9f9f9; font-size: 12px;'>";
        echo "<strong>Nom :</strong> " . htmlspecialchars($service['name']);
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo "<hr>";
echo "<h2>📊 Statistiques globales</h2>";

$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'actifs' => $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn(),
    'prix_moyen' => $pdo->query("SELECT AVG(sell_price) FROM services")->fetchColumn(),
    'prix_max' => $pdo->query("SELECT MAX(sell_price) FROM services")->fetchColumn(),
    'prix_min' => $pdo->query("SELECT MIN(sell_price) FROM services WHERE sell_price > 0")->fetchColumn(),
];

echo "<ul>";
echo "<li><strong>Total services :</strong> {$stats['total']}</li>";
echo "<li><strong>Services actifs :</strong> {$stats['actifs']}</li>";
echo "<li><strong>Prix moyen :</strong> " . number_format($stats['prix_moyen'], 2) . " $</li>";
echo "<li><strong>Prix max :</strong> <span style='color: red; font-weight: bold;'>" . number_format($stats['prix_max'], 2) . " $</span></li>";
echo "<li><strong>Prix min :</strong> " . number_format($stats['prix_min'], 2) . " $</li>";
echo "</ul>";

echo "<hr>";
echo "<p><a href='sync-services.php' style='padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>🔄 Synchroniser les services</a></p>";
echo "<p><a href='services.php' style='padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px;'>📋 Voir tous les services</a></p>";
?>
