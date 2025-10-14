<?php
/**
 * PAYPAL PAYMENT HANDLER
 * 
 * Ce fichier gère les paiements PayPal
 * 
 * CONFIGURATION REQUISE :
 * 1. Créez un compte PayPal Business sur https://www.paypal.com/
 * 2. Récupérez votre email PayPal Business
 * 3. Configurez-le dans Admin > Paramètres > PayPal Email
 * 
 * MODES :
 * - Sandbox (Test) : https://www.sandbox.paypal.com
 * - Live (Production) : https://www.paypal.com
 */

require_once '../config.php';
require_once '../functions.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$amount = floatval($_POST['amount'] ?? 0);

// Récupérer les paramètres
$min_deposit = floatval(getSetting($pdo, 'min_deposit', '5.00'));
$paypal_email = getSetting($pdo, 'paypal_email', '');
$paypal_mode = getSetting($pdo, 'paypal_mode', 'sandbox'); // sandbox ou live

// Validation
if ($amount < $min_deposit) {
    setFlashMessage('error', "Le montant minimum est de " . formatCurrency($min_deposit));
    redirect('../dashboard/balance.php');
}

if ($amount > 10000) {
    setFlashMessage('error', 'Le montant maximum est de 10 000$');
    redirect('../dashboard/balance.php');
}

if (empty($paypal_email)) {
    setFlashMessage('error', 'PayPal n\'est pas configuré. Contactez l\'administrateur.');
    redirect('../dashboard/balance.php');
}

// URL PayPal selon le mode
$paypal_url = $paypal_mode === 'live' 
    ? 'https://www.paypal.com/cgi-bin/webscr'
    : 'https://www.sandbox.paypal.com/cgi-bin/webscr';

// Générer un ID de transaction unique
$transaction_id = 'PAYPAL-' . time() . '-' . $user['id'];

// URL de retour
$return_url = SITE_URL . '/payment/paypal-success.php';
$cancel_url = SITE_URL . '/dashboard/balance.php';
$notify_url = SITE_URL . '/payment/paypal-ipn.php';

// Calculer le bonus
$bonus_percent = 0;
if ($amount >= 500) $bonus_percent = 15;
elseif ($amount >= 100) $bonus_percent = 10;
elseif ($amount >= 50) $bonus_percent = 8;
elseif ($amount >= 10) $bonus_percent = 5;

$bonus_amount = ($amount * $bonus_percent) / 100;
$total_amount = $amount + $bonus_amount;

// Enregistrer la transaction en attente
$stmt = $pdo->prepare("
    INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
    VALUES (?, 'deposit', ?, ?, ?, 'PayPal', ?, ?, NOW())
");
$stmt->execute([
    $user['id'],
    $amount,
    $user['balance'],
    $user['balance'], // Sera mis à jour après confirmation
    $transaction_id,
    "Dépôt PayPal - En attente de confirmation"
]);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement PayPal - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 500px; width: 100%; text-align: center;">
        <h1 style="color: #2563eb; margin-bottom: 20px;">💳 Paiement PayPal</h1>
        
        <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Montant à payer</div>
            <div style="font-size: 48px; font-weight: 700; color: #2563eb; margin-bottom: 10px;">
                $<?php echo number_format($amount, 2); ?>
            </div>
            
            <?php if ($bonus_amount > 0): ?>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px; border-radius: 10px; margin-top: 15px;">
                    <div style="font-size: 14px; opacity: 0.9;">🎁 Bonus de bienvenue</div>
                    <div style="font-size: 24px; font-weight: 700;">+$<?php echo number_format($bonus_amount, 2); ?> (+<?php echo $bonus_percent; ?>%)</div>
                    <div style="font-size: 14px; opacity: 0.9; margin-top: 5px;">
                        Total crédit : <strong>$<?php echo number_format($total_amount, 2); ?></strong>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <p style="color: #6b7280; margin-bottom: 30px;">
            Vous allez être redirigé vers PayPal pour finaliser votre paiement de manière sécurisée.
        </p>

        <!-- Formulaire PayPal -->
        <form action="<?php echo $paypal_url; ?>" method="POST" id="paypalForm">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
            <input type="hidden" name="item_name" value="Ajout de fonds - <?php echo SITE_NAME; ?>">
            <input type="hidden" name="item_number" value="<?php echo $transaction_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="custom" value="<?php echo $user['id']; ?>">
            <input type="hidden" name="return" value="<?php echo $return_url; ?>">
            <input type="hidden" name="cancel_return" value="<?php echo $cancel_url; ?>">
            <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
            <input type="hidden" name="no_shipping" value="1">
            
            <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 15px;">
                Payer avec PayPal →
            </button>
        </form>

        <a href="../dashboard/balance.php" class="btn btn-secondary btn-block">
            ← Annuler
        </a>

        <p style="margin-top: 30px; font-size: 12px; color: #6b7280;">
            🔒 Paiement 100% sécurisé par PayPal
        </p>
    </div>

    <script>
        // Auto-submit après 3 secondes
        setTimeout(function() {
            if (confirm('Redirection vers PayPal dans 3 secondes. Continuer ?')) {
                document.getElementById('paypalForm').submit();
            }
        }, 3000);
    </script>
</body>
</html>
