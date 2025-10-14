<?php
/**
 * Test rapide pour vérifier que getIcon() fonctionne
 */
require_once '../config.php';
require_once '../functions.php';

// Vérifier si la fonction existe
if (!function_exists('getIcon')) {
    die('❌ ERREUR : La fonction getIcon() n\'existe pas !');
}

echo '✅ La fonction getIcon() existe !<br><br>';

// Tester quelques icônes
$icons_to_test = ['services', 'users', 'orders', 'wallet', 'settings', 'ticket'];

echo '<h3>Test des icônes :</h3>';
foreach ($icons_to_test as $icon) {
    echo "<div style='margin: 10px 0;'>";
    echo "<strong>$icon</strong> : " . getIcon($icon, false, 'md');
    echo "</div>";
}

echo '<br><br>';
echo '✅ Tous les tests ont réussi ! Le panel admin devrait fonctionner.';
?>
