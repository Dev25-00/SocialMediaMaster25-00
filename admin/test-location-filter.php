<?php
/**
 * SMM Mastery - Test du filtre Location
 * Date: 14 Octobre 2025
 * Version: 1.0
 * 
 * Script de test pour vérifier que le filtre par pays/location fonctionne correctement
 */

require_once '../config.php';

header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test Filtre Location - SMM Mastery</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #2563eb; border-bottom: 3px solid #2563eb; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #2563eb; color: white; font-weight: 600; }
        tr:hover { background: #f9fafb; }
        .stat { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; border-radius: 8px; margin: 5px; font-weight: 600; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .warning { color: #f59e0b; font-weight: bold; }
        .test-section { margin: 30px 0; padding: 20px; background: #f9fafb; border-left: 4px solid #2563eb; border-radius: 8px; }
        .location-badge { padding: 4px 12px; background: #e0e7ff; color: #3730a3; border-radius: 6px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🌍 Test du Filtre Location - SMM Mastery</h1>";

try {
    // 1. Statistiques générales
    echo "<div class='test-section'>";
    echo "<h2>📊 Statistiques Générales</h2>";
    
    $total_query = "SELECT COUNT(*) as total FROM services WHERE is_active = 1";
    $total = $pdo->query($total_query)->fetch(PDO::FETCH_ASSOC)['total'];
    
    $with_location_query = "SELECT COUNT(*) as total FROM services WHERE is_active = 1 AND location IS NOT NULL AND location != ''";
    $with_location = $pdo->query($with_location_query)->fetch(PDO::FETCH_ASSOC)['total'];
    
    $percentage = $total > 0 ? round(($with_location / $total) * 100, 2) : 0;
    
    echo "<span class='stat'>Total Services: {$total}</span>";
    echo "<span class='stat'>Avec Location: {$with_location}</span>";
    echo "<span class='stat'>Pourcentage: {$percentage}%</span>";
    
    if ($with_location > 0) {
        echo "<p class='success'>✅ Des services ont bien des locations définies !</p>";
    } else {
        echo "<p class='error'>❌ Aucun service n'a de location définie !</p>";
    }
    echo "</div>";
    
    // 2. Liste des locations disponibles
    echo "<div class='test-section'>";
    echo "<h2>🌐 Locations Disponibles</h2>";
    
    $locations_query = "SELECT location, COUNT(*) as count FROM services WHERE is_active = 1 AND location IS NOT NULL AND location != '' GROUP BY location ORDER BY count DESC";
    $locations = $pdo->query($locations_query)->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($locations) > 0) {
        echo "<table>";
        echo "<tr><th>Location</th><th>Nombre de Services</th></tr>";
        foreach ($locations as $loc) {
            echo "<tr>";
            echo "<td><span class='location-badge'>" . htmlspecialchars($loc['location']) . "</span></td>";
            echo "<td>{$loc['count']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ Aucune location trouvée</p>";
    }
    echo "</div>";
    
    // 3. Test du filtre API
    echo "<div class='test-section'>";
    echo "<h2>🔧 Test du Filtre API</h2>";
    
    if (count($locations) > 0) {
        // Tester avec la première location trouvée
        $test_location = $locations[0]['location'];
        
        echo "<p><strong>Test avec location:</strong> <span class='location-badge'>{$test_location}</span></p>";
        
        // Simuler la requête API
        $test_query = "SELECT COUNT(*) as count FROM services WHERE is_active = 1 AND LOWER(location) LIKE :location";
        $stmt = $pdo->prepare($test_query);
        $stmt->execute([':location' => '%' . strtolower($test_location) . '%']);
        $test_result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<p>Résultats trouvés: <span class='stat'>{$test_result['count']}</span></p>";
        
        if ($test_result['count'] > 0) {
            echo "<p class='success'>✅ Le filtre fonctionne correctement !</p>";
            
            // Afficher quelques exemples
            $examples_query = "SELECT provider_id, name, platform, location FROM services WHERE is_active = 1 AND LOWER(location) LIKE :location LIMIT 5";
            $stmt = $pdo->prepare($examples_query);
            $stmt->execute([':location' => '%' . strtolower($test_location) . '%']);
            $examples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Exemples de services:</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Nom</th><th>Plateforme</th><th>Location</th></tr>";
            foreach ($examples as $ex) {
                echo "<tr>";
                echo "<td>#{$ex['provider_id']}</td>";
                echo "<td>" . htmlspecialchars(substr($ex['name'], 0, 60)) . "...</td>";
                echo "<td>{$ex['platform']}</td>";
                echo "<td><span class='location-badge'>{$ex['location']}</span></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>❌ Le filtre ne retourne aucun résultat !</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ Impossible de tester : aucune location disponible</p>";
    }
    echo "</div>";
    
    // 4. URL de test
    echo "<div class='test-section'>";
    echo "<h2>🔗 URL de Test</h2>";
    
    if (count($locations) > 0) {
        $test_location = $locations[0]['location'];
        $test_url = "/smm/api/services.php?location=" . urlencode($test_location);
        
        echo "<p>Testez directement l'API avec cette URL:</p>";
        echo "<code style='background:#f3f4f6;padding:10px;display:block;border-radius:6px;margin:10px 0;'>";
        echo htmlspecialchars($test_url);
        echo "</code>";
        
        echo "<a href='{$test_url}' target='_blank' style='display:inline-block;padding:10px 20px;background:#2563eb;color:white;text-decoration:none;border-radius:8px;font-weight:600;'>🚀 Tester l'API</a>";
    }
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur base de données: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "    </div>
</body>
</html>";
?>
