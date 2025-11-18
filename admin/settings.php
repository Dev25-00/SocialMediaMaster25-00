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
            // Auto-crédit fournisseur
            'auto_credit_payment_method' => clean($_POST['auto_credit_payment_method'] ?? 'paypal'),
            'provider_paypal_email' => clean($_POST['provider_paypal_email'] ?? ''),
            'provider_stripe_account' => clean($_POST['provider_stripe_account'] ?? ''),
            // Modes et seuils
            'auto_credit_mode' => in_array(($_POST['auto_credit_mode'] ?? 'normal'), ['normal','queue_only']) ? $_POST['auto_credit_mode'] : 'normal',
            'provider_balance_alert_threshold' => floatval($_POST['provider_balance_alert_threshold'] ?? 25.00),
            // Crypto Gateway (multi-provider)
            'crypto_gateway' => in_array(($_POST['crypto_gateway'] ?? 'nowpayments'), ['coinpayments','coingate','btcpay','nowpayments']) ? $_POST['crypto_gateway'] : 'nowpayments',
            // CoinPayments
            'coinpayments_merchant_id' => clean($_POST['coinpayments_merchant_id'] ?? ''),
            'coinpayments_ipn_secret' => clean($_POST['coinpayments_ipn_secret'] ?? ''),
            // CoinGate
            'coingate_api_key' => clean($_POST['coingate_api_key'] ?? ''),
            'coingate_app_id' => clean($_POST['coingate_app_id'] ?? ''),
            // BTCPay
            'btcpay_server_url' => clean($_POST['btcpay_server_url'] ?? ''),
            'btcpay_store_id' => clean($_POST['btcpay_store_id'] ?? ''),
            'btcpay_api_key' => clean($_POST['btcpay_api_key'] ?? ''),
            // NOWPayments
            'nowpayments_api_key' => clean($_POST['nowpayments_api_key'] ?? ''),
            'nowpayments_ipn_secret' => clean($_POST['nowpayments_ipn_secret'] ?? ''),
            // Crypto general
            'crypto_currency' => clean($_POST['crypto_currency'] ?? 'USDT'),
            'allow_password_preview' => isset($_POST['allow_password_preview']) ? '1' : '0',
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
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
<head>
    ...existing code...
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
</head>
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

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

            <!-- Auto-Crédit Fournisseur -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>🤖 Auto-Crédit Fournisseur</h2>
                </div>
                <div style="padding: 30px;">
                    <div class="form-group">
                        <label for="auto_credit_mode">Mode Auto-Crédit</label>
                        <?php $ac_mode = $current_settings['auto_credit_mode'] ?? 'normal'; ?>
                        <select id="auto_credit_mode" name="auto_credit_mode">
                            <option value="normal" <?php echo $ac_mode === 'normal' ? 'selected' : ''; ?>>Normal (tente un crédit auto)</option>
                            <option value="queue_only" <?php echo $ac_mode === 'queue_only' ? 'selected' : ''; ?>>Queue Only (pas de crédit auto)</option>
                        </select>
                        <small>Queue Only: les commandes sont mises en file si le solde fournisseur est insuffisant; vous créditez manuellement puis le système relance.</small>
                    </div>
                    <div class="form-group">
                        <label for="auto_credit_payment_method">Méthode de paiement</label>
                        <select id="auto_credit_payment_method" name="auto_credit_payment_method">
                            <?php $ac_method = $current_settings['auto_credit_payment_method'] ?? 'paypal'; ?>
                            <option value="paypal" <?php echo $ac_method === 'paypal' ? 'selected' : ''; ?>>PayPal (recommandé)</option>
                            <option value="stripe" <?php echo $ac_method === 'stripe' ? 'selected' : ''; ?>>Stripe (désactivé pour cartes)</option>
                            <option value="crypto" <?php echo $ac_method === 'crypto' ? 'selected' : ''; ?>>Crypto (à venir)</option>
                        </select>
                        <small>Utilisé pour créditer automatiquement le compte du fournisseur quand le solde est insuffisant.</small>
                    </div>

                    <div class="form-group">
                        <label for="provider_paypal_email">Email PayPal du fournisseur</label>
                        <input type="email"
                               id="provider_paypal_email"
                               name="provider_paypal_email"
                               value="<?php echo $current_settings['provider_paypal_email'] ?? ''; ?>"
                               placeholder="provider@example.com">
                        <small>Nécessaire si vous utilisez PayPal comme méthode d'auto-crédit.</small>
                    </div>

                    <div class="form-group">
                        <label for="provider_stripe_account">Compte Stripe du fournisseur</label>
                        <input type="text"
                               id="provider_stripe_account"
                               name="provider_stripe_account"
                               value="<?php echo $current_settings['provider_stripe_account'] ?? ''; ?>"
                               placeholder="acct_1234567890abcdef">
                        <small>Optionnel. Utilisé si Stripe est sélectionné (non recommandé pour le moment).</small>
                    </div>

                    <div class="form-group">
                        <label for="provider_balance_alert_threshold">Seuil d'alerte de solde fournisseur ($)</label>
                        <input type="number" step="0.01" min="0"
                               id="provider_balance_alert_threshold"
                               name="provider_balance_alert_threshold"
                               value="<?php echo $current_settings['provider_balance_alert_threshold'] ?? '25.00'; ?>">
                        <small>Quand le solde descend en dessous de ce seuil, affichez une alerte sur le dashboard (email possible à venir).</small>
                    </div>

                    <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <strong style="color: #92400e;">Note :</strong>
                        <p style="color: #92400e; margin-top: 8px; font-size: 14px;">
                            Le système créditera un minimum de $10 par contrainte du fournisseur. Si le coût de la commande est supérieur, il créditera le montant exact de la commande.
                        </p>
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

            <!-- Crypto (Multi-Gateway) -->
            <div class="card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2><?php echo getIcon('bitcoin'); ?> Crypto (Multi-Gateway)</h2>
                </div>
                <div style="padding: 30px;">
                    <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <strong style="color: #92400e;">💡 Recommandation :</strong>
                        <p style="color: #92400e; margin-top: 8px; font-size: 14px;">
                            <strong>NOWPayments</strong> ou <strong>BTCPay Server</strong> sont recommandés si vous n'avez pas d'entreprise officielle.
                            Pas de vérification KYC stricte requise pour NOWPayments, et BTCPay est 100% self-hosted (aucun intermédiaire).
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label for="crypto_gateway">Processeur Crypto</label>
                        <?php $gw = $current_settings['crypto_gateway'] ?? 'nowpayments'; ?>
                        <select id="crypto_gateway" name="crypto_gateway" onchange="toggleCryptoFields(this.value)">
                            <option value="nowpayments" <?php echo $gw === 'nowpayments' ? 'selected' : ''; ?>>✅ NOWPayments (Recommandé - Pas de KYC)</option>
                            <option value="btcpay" <?php echo $gw === 'btcpay' ? 'selected' : ''; ?>>✅ BTCPay Server (Self-hosted - Aucune vérification)</option>
                            <option value="coingate" <?php echo $gw === 'coingate' ? 'selected' : ''; ?>>⚠️ CoinGate (KYC léger requis)</option>
                            <option value="coinpayments" <?php echo $gw === 'coinpayments' ? 'selected' : ''; ?>>❌ CoinPayments (KYC entreprise requis)</option>
                        </select>
                        <small>Choisissez le processeur adapté à votre situation (entreprise ou freelance).</small>
                    </div>

                    <!-- CoinPayments -->
                    <div id="gw-coinpayments" class="gateway-fields" style="display:none;">
                        <div class="form-group">
                            <label for="coinpayments_merchant_id">Merchant ID</label>
                            <input type="text" id="coinpayments_merchant_id" name="coinpayments_merchant_id" value="<?php echo $current_settings['coinpayments_merchant_id'] ?? ''; ?>" placeholder="Merchant ID">
                        </div>
                        <div class="form-group">
                            <label for="coinpayments_ipn_secret">IPN Secret</label>
                            <input type="text" id="coinpayments_ipn_secret" name="coinpayments_ipn_secret" value="<?php echo $current_settings['coinpayments_ipn_secret'] ?? ''; ?>" placeholder="IPN Secret">
                        </div>
                        <div style="background:#ecfeff; border:1px solid #06b6d4; color:#0e7490; padding:10px; border-radius:6px; font-size:13px;">
                            IPN URL: <code><?php echo SITE_URL; ?>/payment/crypto-ipn.php</code>
                        </div>
                    </div>

                    <!-- CoinGate -->
                    <div id="gw-coingate" class="gateway-fields" style="display:none;">
                        <div class="form-group">
                            <label for="coingate_api_key">API Key</label>
                            <input type="text" id="coingate_api_key" name="coingate_api_key" value="<?php echo $current_settings['coingate_api_key'] ?? ''; ?>" placeholder="API Key (Sandbox ou Live)">
                        </div>
                        <div class="form-group">
                            <label for="coingate_app_id">App ID (optionnel)</label>
                            <input type="text" id="coingate_app_id" name="coingate_app_id" value="<?php echo $current_settings['coingate_app_id'] ?? ''; ?>" placeholder="App ID">
                        </div>
                        <div style="background:#ecfeff; border:1px solid #06b6d4; color:#0e7490; padding:10px; border-radius:6px; font-size:13px;">
                            Callback URL: <code><?php echo SITE_URL; ?>/payment/crypto-ipn.php</code>
                        </div>
                    </div>

                    <!-- BTCPay Server -->
                    <div id="gw-btcpay" class="gateway-fields" style="display:none;">
                        <div class="form-group">
                            <label for="btcpay_server_url">Server URL</label>
                            <input type="url" id="btcpay_server_url" name="btcpay_server_url" value="<?php echo $current_settings['btcpay_server_url'] ?? ''; ?>" placeholder="https://votre-btcpay.com">
                        </div>
                        <div class="form-group">
                            <label for="btcpay_store_id">Store ID</label>
                            <input type="text" id="btcpay_store_id" name="btcpay_store_id" value="<?php echo $current_settings['btcpay_store_id'] ?? ''; ?>" placeholder="Store ID">
                        </div>
                        <div class="form-group">
                            <label for="btcpay_api_key">API Key</label>
                            <input type="text" id="btcpay_api_key" name="btcpay_api_key" value="<?php echo $current_settings['btcpay_api_key'] ?? ''; ?>" placeholder="API Key (Legacy ou GreenField)">
                        </div>
                        <div style="background:#ecfeff; border:1px solid #06b6d4; color:#0e7490; padding:10px; border-radius:6px; font-size:13px;">
                            Webhook URL: <code><?php echo SITE_URL; ?>/payment/crypto-ipn.php</code>
                        </div>
                    </div>

                    <!-- NOWPayments -->
                    <div id="gw-nowpayments" class="gateway-fields" style="display:none;">
                        <div class="form-group">
                            <label for="nowpayments_api_key">API Key</label>
                            <input type="text" id="nowpayments_api_key" name="nowpayments_api_key" value="<?php echo $current_settings['nowpayments_api_key'] ?? ''; ?>" placeholder="API Key">
                        </div>
                        <div class="form-group">
                            <label for="nowpayments_ipn_secret">IPN Secret</label>
                            <input type="text" id="nowpayments_ipn_secret" name="nowpayments_ipn_secret" value="<?php echo $current_settings['nowpayments_ipn_secret'] ?? ''; ?>" placeholder="IPN Secret">
                        </div>
                        <div style="background:#ecfeff; border:1px solid #06b6d4; color:#0e7490; padding:10px; border-radius:6px; font-size:13px;">
                            IPN Callback: <code><?php echo SITE_URL; ?>/payment/crypto-ipn.php</code>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label for="crypto_currency">Devise préférée (par défaut)</label>
                        <?php $cp_curr = $current_settings['crypto_currency'] ?? 'USDT'; ?>
                        <select id="crypto_currency" name="crypto_currency">
                            <option value="USDT" <?php echo $cp_curr === 'USDT' ? 'selected' : ''; ?>>USDT (Stablecoin)</option>
                            <option value="USDC" <?php echo $cp_curr === 'USDC' ? 'selected' : ''; ?>>USDC (Stablecoin)</option>
                            <option value="BTC" <?php echo $cp_curr === 'BTC' ? 'selected' : ''; ?>>BTC</option>
                            <option value="ETH" <?php echo $cp_curr === 'ETH' ? 'selected' : ''; ?>>ETH</option>
                        </select>
                        <small>Certains gateways supportent la sélection multi-devises côté utilisateur.</small>
                    </div>
                </div>
            </div>

            <script>
            function toggleCryptoFields(gateway) {
                document.querySelectorAll('.gateway-fields').forEach(el => el.style.display = 'none');
                const sel = document.getElementById('gw-' + gateway);
                if (sel) sel.style.display = 'block';
            }
            document.addEventListener('DOMContentLoaded', function() {
                const gw = document.getElementById('crypto_gateway').value;
                toggleCryptoFields(gw);
            });
            </script>

                    <!-- Security / Dev -->
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header">
                            <h2>🔒 Sécurité / Développement</h2>
                        </div>
                        <div style="padding: 30px;">
                            <div class="form-group">
                                <label for="allow_password_preview">Autoriser l'affichage du lien de réinitialisation (dev)</label>
                                <div>
                                    <input type="checkbox" id="allow_password_preview" name="allow_password_preview" value="1" <?php echo (!empty($current_settings['allow_password_preview']) && $current_settings['allow_password_preview'] === '1') ? 'checked' : ''; ?>>
                                    <small style="display:block;margin-top:8px;">Permet d'afficher le lien de reset sur la page de mot de passe oublié (utile en développement). Ne pas activer en production.</small>
                                </div>
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
