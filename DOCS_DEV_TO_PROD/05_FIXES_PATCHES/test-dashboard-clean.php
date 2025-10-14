<!DOCTYPE html>
<html>
<head>
    <title>🧪 Test Dashboard - Status</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { color: #10b981; background: #ecfdf5; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { color: #ef4444; background: #fef2f2; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { color: #3b82f6; background: #eff6ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .test-link { display: inline-block; margin: 10px 0; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; }
        .test-link:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Dashboard SMM Mastery</h1>
        
        <div class="info">
            <strong>Correctif appliqué :</strong> Suppression des fichiers CSS conflictuels
        </div>
        
        <h2>📊 Status des fichiers</h2>
        
        <?php
        $dashboard_header = file_get_contents(__DIR__ . '/../../includes/dashboard-header.php');
        $has_dashboard_fixes = strpos($dashboard_header, 'dashboard-fixes.css') !== false;
        $has_fixes = strpos($dashboard_header, 'fixes.css') !== false;
        ?>
        
        <ul>
            <li>dashboard-fixes.css : <?php echo $has_dashboard_fixes ? '<span style="color:red">❌ PRÉSENT</span>' : '<span style="color:green">✅ ABSENT</span>'; ?></li>
            <li>fixes.css : <?php echo $has_fixes ? '<span style="color:red">❌ PRÉSENT</span>' : '<span style="color:green">✅ ABSENT</span>'; ?></li>
        </ul>
        
        <div class="<?php echo (!$has_dashboard_fixes && !$has_fixes) ? 'success' : 'error'; ?>">
            <?php if (!$has_dashboard_fixes && !$has_fixes): ?>
                ✅ <strong>CONFIGURATION PROPRE</strong> - Aucun CSS conflictuel détecté
            <?php else: ?>
                ❌ <strong>PROBLÈME DÉTECTÉ</strong> - Des CSS conflictuels sont encore présents
            <?php endif; ?>
        </div>
        
        <h2>🔗 Tests Dashboard</h2>
        <p>Cliquez sur les liens ci-dessous pour tester le dashboard :</p>
        
        <a href="http://localhost/smm/dashboard/index.php" target="_blank" class="test-link">
            🏠 Dashboard Principal
        </a>
        
        <a href="http://localhost/smm/dashboard/balance.php" target="_blank" class="test-link">
            💰 Mon Solde
        </a>
        
        <a href="http://localhost/smm/dashboard/profile.php" target="_blank" class="test-link">
            👤 Mon Profil
        </a>
        
        <h2>✅ Ce qui devrait fonctionner maintenant</h2>
        <ul>
            <li>✅ Layout dashboard correct (sidebar + contenu principal)</li>
            <li>✅ Navigation fonctionnelle</li>
            <li>✅ Tous les liens de menu</li>
            <li>✅ Responsive naturel</li>
        </ul>
        
        <div class="info">
            <strong>Note :</strong> Le dashboard utilise maintenant uniquement ses CSS originaux sans interference.
        </div>
        
        <h2>📝 Prochaines étapes</h2>
        <ol>
            <li>Tester le dashboard principal</li>
            <li>Vérifier la navigation</li>
            <li>Confirmer que tout fonctionne</li>
            <li>Réappliquer les corrections mobiles si nécessaire</li>
        </ol>
        
    </div>
</body>
</html>
