<?php
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$success = '';
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_settings') {
        $settings = [
            'smmfollows_api_key' => trim($_POST['smmfollows_api_key'] ?? ''),
            'site_name' => clean($_POST['site_name'] ?? 'SMM Mastery'),
            'site_email' => clean($_POST['site_email'] ?? ''),
            'currency' => clean($_POST['currency'] ?? 'USD'),
            'currency_symbol' => clean($_POST['currency_symbol'] ?? '$'),
            'welcome_bonus' => floatval($_POST['welcome_bonus'] ?? 1.00),
            'min_deposit' => floatval($_POST['min_deposit'] ?? 5.00),
            'paypal_email' => clean($_POST['paypal_email'] ?? ''),
            'paypal_mode' => clean($_POST['paypal_mode'] ?? 'sandbox'),
        ];
        
        try {
            foreach ($settings as $key => $value) {
                updateSetting($pdo, $key, $value);
            }
            $success = 'Paramètres sauvegardés avec succès !';
        } catch (Exception $e) {
            $error = 'Erreur lors de la sauvegarde : ' . $e->getMessage();
        }
    }
}

// Récupérer les paramètres actuels
$current_settings = getSiteSettings($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/fixes.css">
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1><?php echo getIcon('settings'); ?> Paramètres</h1>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="save_settings">
            
            <!-- API SMMFollows -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>🔌 API SMMFollows</h2>
                    <a href="api-test.php" class="btn btn-sm btn-primary">Tester l'API</a>
                </div>
                <div style="padding: 30px;">
                    <div class="form-group">
                        <label for="smmfollows_api_key">Clé API SMMFollows *</label>
                        <input type="text" 
                               id="smmfollows_api_key" 
                               name="smmfollows_api_key" 
                               value="<?php echo $current_settings['smmfollows_api_key'] ?? ''; ?>"
                               placeholder="Votre clé API"
                               required>
                        <small>Récupérez votre clé sur <a href="https://smmfollows.com/account" target="_blank">smmfollows.com/account</a></small>
                    </div>
                    
                    <div style="background: #dbeafe; padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <strong style="color: #1e40af;"><?php echo getIcon('info'); ?> Important :</strong>
                        <p style="color: #1e40af; margin-top: 8px; font-size: 14px;">
                            Assurez-vous d'avoir du solde sur votre compte SMMFollows pour que les commandes fonctionnent.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paramètres Site -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>🌐 Paramètres du Site</h2>
                </div>
                <div style="padding: 30px;">
                    <div class="form-group">
                        <label for="site_name">Nom du site</label>
                        <input type="text" 
                               id="site_name" 
                               name="site_name" 
                               value="<?php echo $current_settings['site_name'] ?? 'SMM Mastery'; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="site_email">Email du site</label>
                        <input type="email" 
                               id="site_email" 
                               name="site_email" 
                               value="<?php echo $current_settings['site_email'] ?? ''; ?>"
                               placeholder="contact@votresite.com">
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="currency">Devise</label>
                            <select id="currency" name="currency">
                                <option value="USD" <?php echo ($current_settings['currency'] ?? 'USD') === 'USD' ? 'selected' : ''; ?>>USD - Dollar américain</option>
                                <option value="EUR" <?php echo ($current_settings['currency'] ?? '') === 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                <option value="GBP" <?php echo ($current_settings['currency'] ?? '') === 'GBP' ? 'selected' : ''; ?>>GBP - Livre sterling</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="currency_symbol">Symbole devise</label>
                            <input type="text" 
                                   id="currency_symbol" 
                                   name="currency_symbol" 
                                   value="<?php echo $current_settings['currency_symbol'] ?? '$'; ?>">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="welcome_bonus">Bonus de bienvenue ($)</label>
                            <input type="number" 
                                   id="welcome_bonus" 
                                   name="welcome_bonus" 
                                   step="0.01"
                                   value="<?php echo $current_settings['welcome_bonus'] ?? '1.00'; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="min_deposit">Dépôt minimum ($)</label>
                            <input type="number" 
                                   id="min_deposit" 
                                   name="min_deposit" 
                                   step="0.01"
                                   value="<?php echo $current_settings['min_deposit'] ?? '5.00'; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PayPal -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2><?php echo getIcon('card'); ?> PayPal</h2>
                </div>
                <div style="padding: 30px;">
                    <div class="form-group">
                        <label for="paypal_email">Email PayPal Business</label>
                        <input type="email" 
                               id="paypal_email" 
                               name="paypal_email" 
                               value="<?php echo $current_settings['paypal_email'] ?? ''; ?>"
                               placeholder="votre@paypal.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="paypal_mode">Mode PayPal</label>
                        <select id="paypal_mode" name="paypal_mode">
                            <option value="sandbox" <?php echo ($current_settings['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''; ?>>
                                Sandbox (Test)
                            </option>
                            <option value="live" <?php echo ($current_settings['paypal_mode'] ?? '') === 'live' ? 'selected' : ''; ?>>
                                Live (Production)
                            </option>
                        </select>
                        <small>Utilisez "Sandbox" pour les tests</small>
                    </div>
                </div>
            </div>

            <!-- Bouton Save -->
            <div class="card">
                <div style="padding: 30px;">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        💾 Sauvegarder les paramètres
                    </button>
                </div>
            </div>
        </form>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
