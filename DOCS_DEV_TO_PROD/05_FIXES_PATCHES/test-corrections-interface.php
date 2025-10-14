<?php
/**
 * SMM Mastery - Test des corrections CSS/JS
 * Vérification que tous les fichiers ont bien les corrections appliquées
 */

echo "<h1>🧪 Test des corrections CSS/JS - SMM Mastery</h1>\n";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>\n";

// Fichiers à tester
$files_to_test = [
    'admin/dashboard.php',
    'admin/users.php', 
    'admin/orders.php',
    'admin/services.php',
    'admin/settings.php',
    'admin/tickets.php',
    'admin/sync-services.php',
    'admin/api-test.php',
    'orders/history.php',
    'orders/tracking.php',
    'support/tickets.php',
    'support/new-ticket.php',
    'support/view-ticket.php',
    'services/index.php'
];

$total_files = count($files_to_test);
$success_count = 0;
$error_count = 0;

echo "<h2>📊 Résultats des tests</h2>\n";
echo "<table border='1' style='border-collapse:collapse; width:100%;'>\n";
echo "<tr><th>Fichier</th><th>fixes.css</th><th>mobile-menu.js</th><th>Statut</th></tr>\n";

foreach ($files_to_test as $file) {
    $full_path = __DIR__ . '/../../' . $file;
    
    if (!file_exists($full_path)) {
        echo "<tr><td>$file</td><td colspan='3' class='error'>❌ Fichier non trouvé</td></tr>\n";
        $error_count++;
        continue;
    }
    
    $content = file_get_contents($full_path);
    
    $has_fixes_css = strpos($content, 'fixes.css') !== false;
    $has_mobile_js = strpos($content, 'mobile-menu.js') !== false;
    
    $fixes_status = $has_fixes_css ? "<span class='success'>✅</span>" : "<span class='error'>❌</span>";
    $mobile_status = $has_mobile_js ? "<span class='success'>✅</span>" : "<span class='error'>❌</span>";
    
    if ($has_fixes_css && $has_mobile_js) {
        $overall_status = "<span class='success'>✅ Complet</span>";
        $success_count++;
    } else {
        $overall_status = "<span class='warning'>⚠️ Partiel</span>";
    }
    
    echo "<tr><td>$file</td><td>$fixes_status</td><td>$mobile_status</td><td>$overall_status</td></tr>\n";
}

echo "</table>\n";

echo "<h2>📈 Statistiques</h2>\n";
echo "<ul>\n";
echo "<li><strong>Total fichiers testés:</strong> $total_files</li>\n";
echo "<li><strong>Corrections complètes:</strong> <span class='success'>$success_count</span></li>\n";
echo "<li><strong>Erreurs:</strong> <span class='error'>$error_count</span></li>\n";
echo "<li><strong>Taux de réussite:</strong> " . round(($success_count / $total_files) * 100, 1) . "%</li>\n";
echo "</ul>\n";

// Test des includes
echo "<h2>🔍 Test des includes</h2>\n";

$includes_to_test = [
    'includes/dashboard-header.php',
    'includes/dashboard-footer.php'
];

foreach ($includes_to_test as $include) {
    $full_path = __DIR__ . '/../../' . $include;
    
    if (file_exists($full_path)) {
        $content = file_get_contents($full_path);
        $has_fixes = strpos($content, 'fixes.css') !== false;
        $has_mobile = strpos($content, 'mobile') !== false; // JavaScript mobile dans footer
        
        echo "<p><strong>$include:</strong> fixes.css " . ($has_fixes ? "✅" : "❌") . 
             " | Mobile JS " . ($has_mobile ? "✅" : "❌") . "</p>\n";
    }
}

if ($success_count == $total_files) {
    echo "<h2 class='success'>🎉 SUCCÈS COMPLET !</h2>\n";
    echo "<p class='success'>Toutes les corrections CSS/JS sont correctement appliquées.</p>\n";
} else {
    echo "<h2 class='warning'>⚠️ Corrections partielles</h2>\n";
    echo "<p class='warning'>Certains fichiers nécessitent encore des corrections.</p>\n";
}

echo "<hr><p><em>Test effectué le " . date('d/m/Y à H:i:s') . "</em></p>\n";
?>
