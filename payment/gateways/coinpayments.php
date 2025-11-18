<?php
/**
 * SMM Mastery - CoinPayments Gateway Handler
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 */

function handleCoinPayments($pdo, $user, $amount, $currency) {
    $cp_merchant = getSetting($pdo, 'coinpayments_merchant_id', '');
    $cp_ipn_secret = getSetting($pdo, 'coinpayments_ipn_secret', '');

    if (empty($cp_merchant) || empty($cp_ipn_secret)) {
        setFlashMessage('error', "CoinPayments n'est pas configuré. Contactez l'admin.");
        redirect('../dashboard/balance.php');
    }

    // Build transaction data
    $txn_id = 'CRYPTO-CP-' . time() . '-' . $user['id'];
    $return_url = SITE_URL . '/payment/paypal-success.php';
    $cancel_url = SITE_URL . '/dashboard/balance.php';
    $ipn_url = SITE_URL . '/payment/crypto-ipn.php';

    // Record pending transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
    VALUES (?, 'deposit', ?, ?, ?, 'Crypto', ?, ?, NOW())");
    $stmt->execute([
        $user['id'],
        $amount,
        $user['balance'],
        $user['balance'],
        $txn_id,
        'Dépôt Crypto (CoinPayments) - En attente'
    ]);

    // Prepare CoinPayments form
    $fields = [
        'cmd' => '_pay_simple',
        'reset' => '1',
        'merchant' => $cp_merchant,
        'currency' => 'USD',
        'amountf' => number_format($amount, 2, '.', ''),
        'item_name' => SITE_NAME . ' - Dépôt de fonds',
        'item_number' => $txn_id,
        'invoice' => $txn_id,
        'want_shipping' => '0',
        'success_url' => $return_url,
        'cancel_url' => $cancel_url,
        'ipn_url' => $ipn_url,
        'allow_amount_edit' => '0',
        'allow_extra' => '0'
    ];

    // Auto-submit HTML
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>CoinPayments - <?php echo SITE_NAME; ?></title>
        <link rel="stylesheet" href="../../assets/css/global/main.css" />
    </head>
    <body style="background:#0f172a; color:#e5e7eb; min-height:100vh; display:flex; align-items:center; justify-content:center;">
        <div style="background:#111827; border:1px solid #1f2937; border-radius:12px; padding:28px; max-width:520px; width:100%; text-align:center;">
            <h1 style="margin:0 0 10px 0;">🪙 CoinPayments</h1>
            <p>Redirection en cours…</p>
            <form id="cpForm" action="https://www.coinpayments.net/index.php" method="POST">
                <?php foreach ($fields as $k => $v): ?>
                    <input type="hidden" name="<?php echo htmlspecialchars($k); ?>" value="<?php echo htmlspecialchars($v); ?>" />
                <?php endforeach; ?>
                <noscript><button type="submit" class="btn btn-primary">Continuer</button></noscript>
            </form>
        </div>
        <script>setTimeout(function(){ document.getElementById('cpForm').submit(); }, 500);</script>
    </body>
    </html>
    <?php
    exit;
}
