<?php
/**
 * SMM Mastery - CoinGate Gateway Handler
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 * API Docs: https://developer.coingate.com/docs/create-order
 */

function handleCoinGate($pdo, $user, $amount, $currency) {
    $api_key = getSetting($pdo, 'coingate_api_key', '');
    if (empty($api_key)) {
        setFlashMessage('error', "CoinGate n'est pas configuré.");
        redirect('../dashboard/balance.php');
    }

    $txn_id = 'CRYPTO-CG-' . time() . '-' . $user['id'];
    $return_url = SITE_URL . '/payment/paypal-success.php';
    $cancel_url = SITE_URL . '/dashboard/balance.php';
    $callback_url = SITE_URL . '/payment/crypto-ipn.php';

    // Record pending transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
    VALUES (?, 'deposit', ?, ?, ?, 'Crypto', ?, ?, NOW())");
    $stmt->execute([$user['id'], $amount, $user['balance'], $user['balance'], $txn_id, 'Dépôt Crypto (CoinGate) - En attente']);

    // CoinGate API request
    $data = [
        'order_id' => $txn_id,
        'price_amount' => $amount,
        'price_currency' => 'USD',
        'receive_currency' => $currency,
        'title' => SITE_NAME . ' - Dépôt',
        'description' => 'Recharge de solde',
        'callback_url' => $callback_url,
        'cancel_url' => $cancel_url,
        'success_url' => $return_url
    ];

    $ch = curl_init('https://api.coingate.com/v2/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        error_log("CoinGate error: " . $response);
        setFlashMessage('error', "Erreur CoinGate. Réessayez.");
        redirect('../dashboard/balance.php');
    }

    $order = json_decode($response, true);
    if (!isset($order['payment_url'])) {
        setFlashMessage('error', "Erreur: pas de lien de paiement.");
        redirect('../dashboard/balance.php');
    }

    // Redirect to CoinGate payment page
    header('Location: ' . $order['payment_url']);
    exit;
}
