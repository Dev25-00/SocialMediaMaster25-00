<?php
/**
 * SMM Mastery - BTCPay Server Gateway Handler
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 * API Docs: https://docs.btcpayserver.org/API/Greenfield/v1/
 */

function handleBTCPay($pdo, $user, $amount, $currency) {
    $server_url = getSetting($pdo, 'btcpay_server_url', '');
    $store_id = getSetting($pdo, 'btcpay_store_id', '');
    $api_key = getSetting($pdo, 'btcpay_api_key', '');

    if (empty($server_url) || empty($store_id) || empty($api_key)) {
        setFlashMessage('error', "BTCPay Server n'est pas configuré.");
        redirect('../dashboard/balance.php');
    }

    $txn_id = 'CRYPTO-BTC-' . time() . '-' . $user['id'];
    $return_url = SITE_URL . '/payment/paypal-success.php';
    $webhook_url = SITE_URL . '/payment/crypto-ipn.php';

    // Record pending transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
    VALUES (?, 'deposit', ?, ?, ?, 'Crypto', ?, ?, NOW())");
    $stmt->execute([$user['id'], $amount, $user['balance'], $user['balance'], $txn_id, 'Dépôt Crypto (BTCPay) - En attente']);

    // BTCPay API request (Greenfield)
    $data = [
        'amount' => (string)$amount,
        'currency' => 'USD',
        'orderId' => $txn_id,
        'metadata' => [
            'orderId' => $txn_id,
            'userId' => $user['id']
        ],
        'checkout' => [
            'redirectURL' => $return_url
        ]
    ];

    $ch = curl_init(rtrim($server_url, '/') . '/api/v1/stores/' . $store_id . '/invoices');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: token ' . $api_key
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        error_log("BTCPay error: " . $response);
        setFlashMessage('error', "Erreur BTCPay. Réessayez.");
        redirect('../dashboard/balance.php');
    }

    $invoice = json_decode($response, true);
    if (!isset($invoice['checkoutLink'])) {
        setFlashMessage('error', "Erreur: pas de lien de paiement.");
        redirect('../dashboard/balance.php');
    }

    // Redirect to BTCPay checkout
    header('Location: ' . $invoice['checkoutLink']);
    exit;
}
