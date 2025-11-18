<?php
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification Configuration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #111827;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
        }
        .info {
            background: #dbeafe;
            color: #1e40af;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
        .config-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin: 10px 0;
            background: #f9fafb;
            border-radius: 8px;
            align-items: center;
        }
        .config-label {
            font-weight: 600;
            color: #374151;
        }
        .config-value {
            font-family: monospace;
            background: #e5e7eb;
            padding: 5px 12px;
            border-radius: 6px;
            color: #111827;
        }
        .test-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 25px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }
        .btn-success {
            background: #10b981;
        }
        .btn-purple {
            background: #7c3aed;
        }
        .btn-orange {
            background: #f59e0b;
        }
        code {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <div class="main-content">
    <div class="container">
        <h1>✅ Vérification de la Configuration</h1>
        
        <div class="success">
            <strong style="font-size: 20px;">🎉 SITE_URL est maintenant correctement configuré !</strong>
        </div>

        <h2 style="margin-top: 30px; color: #374151;">Configuration actuelle :</h2>
        
        <div class="config-item">
            <span class="config-label">SITE_URL</span>
            <span class="config-value"><?php echo SITE_URL; ?></span>
        </div>
        
        <div class="config-item">
            <span class="config-label">SITE_NAME</span>
            <span class="config-value"><?php echo SITE_NAME; ?></span>
        </div>
        
        <div class="config-item">
            <span class="config-label">Base de données</span>
            <span class="config-value"><?php echo DB_NAME; ?></span>
        </div>
        
        <div class="config-item">
            <span class="config-label">Debug Mode</span>
            <span class="config-value"><?php echo DEBUG_MODE ? 'Activé' : 'Désactivé'; ?></span>
        </div>
        
        <div class="config-item">
            <span class="config-label">Session Active</span>
            <span class="config-value"><?php echo isset($_SESSION['user_id']) ? 'Oui - User ID: ' . $_SESSION['user_id'] : 'Non'; ?></span>
        </div>

        <h2 style="margin-top: 40px; color: #374151;">Test des URLs :</h2>
        
        <div class="test-buttons">
            <a href="<?php echo SITE_URL; ?>/index.php" class="btn">
                🏠 Page d'accueil
            </a>
            <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="btn btn-success">
                📊 Dashboard User
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="btn btn-purple">
                🔧 Admin Panel
            </a>
            <a href="<?php echo SITE_URL; ?>/services/index.php" class="btn btn-orange">
                🛍️ Services
            </a>
            <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn" style="background: #6b7280;">
                🔑 Login
            </a>
            <a href="<?php echo SITE_URL; ?>/admin/api-test.php" class="btn" style="background: #ec4899;">
                🧪 Test API
            </a>
        </div>

        <div class="info">
            <strong>✅ Prochaines étapes :</strong><br><br>
            1. <strong>Testez tous les boutons ci-dessus</strong> - Ils doivent tous fonctionner !<br>
            2. <strong>Naviguez dans l'application</strong> - Cliquez sur les menus<br>
            3. <strong>Vérifiez qu'aucun lien ne donne d'erreur 404</strong><br>
            4. <strong>Configurez l'API SMMFollows</strong> dans Admin > Paramètres<br><br>
            
            <strong>Si tout fonctionne :</strong> Vous êtes prêt ! 🚀
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
            <strong style="color: #92400e;">💡 Note importante :</strong><br>
            <p style="color: #92400e; margin-top: 10px;">
                Si vous déplacez votre projet ailleurs que <code>D:\wamp64\www\smm\</code>, 
                vous devrez modifier le SITE_URL dans <code>config.php</code>
            </p>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo SITE_URL; ?>/admin/check-links.php" class="btn" style="background: #059669;">
                🔍 Vérifier tous les liens
            </a>
        </div>
    </div>
    </div>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
