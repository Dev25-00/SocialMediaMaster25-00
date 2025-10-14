<?php
/**
 * Test Dashboard Layout - Vérification CSS
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Dashboard CSS</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <h1>🧪 Test Dashboard CSS</h1>
    
    <h2>Fichiers CSS Dashboard :</h2>
    <ul>
        <li>main.css : <?php echo file_exists(__DIR__ . '/../assets/css/main.css') ? '<span class="success">✅ OK</span>' : '<span class="error">❌ Manquant</span>'; ?></li>
        <li>dashboard.css : <?php echo file_exists(__DIR__ . '/../assets/css/dashboard.css') ? '<span class="success">✅ OK</span>' : '<span class="error">❌ Manquant</span>'; ?></li>
        <li>icons.css : <?php echo file_exists(__DIR__ . '/../assets/css/icons.css') ? '<span class="success">✅ OK</span>' : '<span class="error">❌ Manquant</span>'; ?></li>
        <li>dashboard-fixes.css : <?php echo file_exists(__DIR__ . '/../assets/css/dashboard-fixes.css') ? '<span class="success">✅ OK</span>' : '<span class="error">❌ Manquant</span>'; ?></li>
        <li>fixes.css (ancien) : <?php echo file_exists(__DIR__ . '/../assets/css/fixes.css') ? '<span class="warning">⚠️ Présent</span>' : '<span class="success">✅ Non utilisé</span>'; ?></li>
    </ul>
    
    <h2>Test Dashboard Header :</h2>
    <?php
    $header_content = file_get_contents(__DIR__ . '/../includes/dashboard-header.php');
    $uses_dashboard_fixes = strpos($header_content, 'dashboard-fixes.css') !== false;
    $uses_old_fixes = strpos($header_content, 'fixes.css') !== false && !$uses_dashboard_fixes;
    ?>
    <ul>
        <li>Utilise dashboard-fixes.css : <?php echo $uses_dashboard_fixes ? '<span class="success">✅ OK</span>' : '<span class="error">❌ NON</span>'; ?></li>
        <li>Utilise ancien fixes.css : <?php echo $uses_old_fixes ? '<span class="error">❌ PROBLÈME</span>' : '<span class="success">✅ OK</span>'; ?></li>
    </ul>
    
    <h2>URLs de test :</h2>
    <ul>
        <li><a href="http://localhost/smm/dashboard/index.php" target="_blank">Dashboard Principal</a></li>
        <li><a href="http://localhost/smm/dashboard/balance.php" target="_blank">Mon Solde</a></li>
        <li><a href="http://localhost/smm/dashboard/profile.php" target="_blank">Mon Profil</a></li>
    </ul>
    
    <p><strong>Instructions :</strong> Cliquez sur les liens ci-dessus pour tester le dashboard.</p>
    
</body>
</html>
