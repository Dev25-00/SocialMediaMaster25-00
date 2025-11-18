<?php
/**
 * SMM Mastery - NOWPayments Gateway Handler
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 * API Docs: https://documenter.getpostman.com/view/7907941/S1a32n38
 */

function handleNOWPayments($pdo, $user, $amount, $currency) {
    $api_key = getSetting($pdo, 'nowpayments_api_key', '');
    if (empty($api_key)) {
        setFlashMessage('error', "NOWPayments n'est pas configuré.");
        redirect('../dashboard/balance.php');
    }

    $txn_id = 'CRYPTO-NOW-' . time() . '-' . $user['id'];
    $return_url = SITE_URL . '/payment/paypal-success.php';
    $cancel_url = SITE_URL . '/dashboard/balance.php';
    $ipn_url = SITE_URL . '/payment/crypto-ipn.php';

    // Record pending transaction
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
    VALUES (?, 'deposit', ?, ?, ?, 'Crypto', ?, ?, NOW())");
    $stmt->execute([$user['id'], $amount, $user['balance'], $user['balance'], $txn_id, 'Dépôt Crypto (NOWPayments) - En attente']);

    // NOWPayments API request (create payment)
    $data = [
        'price_amount' => $amount,
        'price_currency' => 'usd',
        'pay_currency' => strtolower($currency), // e.g. usdttrc20
        'ipn_callback_url' => $ipn_url,
        'order_id' => $txn_id,
        'order_description' => SITE_NAME . ' - Dépôt',
        'success_url' => $return_url,
        'cancel_url' => $cancel_url
    ];

    $ch = curl_init('https://api.nowpayments.io/v1/payment');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-api-key: ' . $api_key
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 201 && $http_code !== 200) {
        error_log("NOWPayments error: " . $response);
        setFlashMessage('error', "Erreur NOWPayments. Réessayez.");
        redirect('../dashboard/balance.php');
    }

    $payment = json_decode($response, true);
    if (!isset($payment['invoice_url'])) {
        setFlashMessage('error', "Erreur: pas de lien de paiement.");
        redirect('../dashboard/balance.php');
    }

    // Redirect to NOWPayments invoice
    header('Location: ' . $payment['invoice_url']);
    exit;
}
