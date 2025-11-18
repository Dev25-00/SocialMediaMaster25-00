<?php
/**
 * INCLUSION: Sidebar admin navigation
 * FICHIER: sidebar.php
 * DOCUMENTATION: DOCS_DEV_TO_PROD/ARCHITECTURE_INCLUDES_VISUELLE.md
 */
/**
 * Vérification des plateformes disponibles
 * Pour debug des filtres
 */

require_once __DIR__ . '/../config.php';

echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Plateformes - Admin</title><link rel=\"stylesheet\" href=\"../assets/css/global/main.css\"><link rel=\"stylesheet\" href=\"../assets/css/dashboard/dashboard.css\"><link rel=\"stylesheet\" href=\"../assets/css/global/fixes.css\"><link rel=\"stylesheet\" href=\"/smm/assets/css/admin/admin-dashboard.css\"></head><body>";
require_once __DIR__ . '/sidebar.php';
echo "<div class='main-content' style='padding:20px;'>";
echo "<h2>Plateformes dans la BDD :</h2>";

$platforms = $pdo->query("
    SELECT platform, 
           COUNT(*) as count,
           SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active
    FROM services 
    GROUP BY platform 
    ORDER BY count DESC
")->fetchAll();

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Plateforme</th><th>Total Services</th><th>Actifs</th><th>Inactifs</th></tr>";

foreach ($platforms as $p) {
    $inactive = $p['count'] - $p['active'];
    echo "<tr>";
    echo "<td><strong>{$p['platform']}</strong></td>";
    echo "<td>{$p['count']}</td>";
    echo "<td style='color: green;'>{$p['active']}</td>";
    echo "<td style='color: red;'>{$inactive}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Total : " . count($platforms) . " plateformes</h3>";

// Nouvelles plateformes spécifiques
$new_platforms = ['Kick', 'Rumble', 'BlueSky', 'Kwai', 'Truth Social', 'Audiomack', 
                  'Quora', 'Tumblr', 'SoundCloud', 'Medium', 'Rutube', 'Apple Music', 
                  'Chzzk', 'Square', 'Website', 'Mobile', 'Worldwide'];

echo "<h2>Nouvelles plateformes V2 :</h2>";
echo "<ul>";
foreach ($new_platforms as $np) {
    $check = $pdo->query("SELECT COUNT(*) as count FROM services WHERE platform = '$np' AND is_active = 1")->fetch();
    $status = $check['count'] > 0 ? "✅ {$check['count']} services" : "❌ Aucun service";
    echo "<li><strong>$np</strong> : $status</li>";
}
echo "</ul>";
echo "</div><script src=\"../assets/js/mobile-menu.js\"></script></body></html>";
?>
