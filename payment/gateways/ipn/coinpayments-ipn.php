<?php
/**
 * SMM Mastery - CoinPayments IPN Handler
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 */

function handleCoinPaymentsIPN($pdo, $post, $server, $log_file) {
    // Verify HMAC
    $cp_ipn_secret = getSetting($pdo, 'coinpayments_ipn_secret', '');
    if (empty($cp_ipn_secret)) {
        file_put_contents($log_file, "ERROR: IPN secret missing\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad config');
    }

    if (!isset($server['HTTP_HMAC']) || empty($server['HTTP_HMAC'])) {
        file_put_contents($log_file, "ERROR: Missing HMAC header\n", FILE_APPEND);
        http_response_code(400);
        exit('No HMAC');
    }

    $hmac = $server['HTTP_HMAC'];
    $raw_post_data = file_get_contents('php://input');
    $calc_hmac = hash_hmac('sha512', $raw_post_data, trim($cp_ipn_secret));
    if (!hash_equals($hmac, $calc_hmac)) {
        file_put_contents($log_file, "ERROR: Invalid HMAC\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad HMAC');
    }

    // Validate merchant
    $cp_merchant = getSetting($pdo, 'coinpayments_merchant_id', '');
    if (($post['merchant'] ?? '') !== $cp_merchant) {
        file_put_contents($log_file, "ERROR: Merchant mismatch\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad merchant');
    }

    // Extract IPN data
    $status = intval($post['status'] ?? 0);
    $amount1 = floatval($post['amount1'] ?? 0);
    $currency1 = $post['currency1'] ?? 'USD';
    $invoice = $post['invoice'] ?? ($post['item_number'] ?? '');

    // Retrieve transaction
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE payment_id = ? AND payment_method = 'Crypto' LIMIT 1");
    $stmt->execute([$invoice]);
    $tx = $stmt->fetch();

    if (!$tx || $tx['type'] !== 'deposit') {
        file_put_contents($log_file, "INFO: No matching deposit for invoice {$invoice}\n", FILE_APPEND);
        http_response_code(200);
        exit('OK');
    }

    // Validate amount & currency
    if ($currency1 !== 'USD' || $amount1 < $tx['amount'] - 0.01) {
        file_put_contents($log_file, "ERROR: Amount or currency mismatch\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad amount');
    }

    // Process if complete (status >= 100 or == 2)
    if ($status >= 100 || $status == 2) {
        creditUserFromCrypto($pdo, $tx, $log_file, 'CoinPayments');
    } else {
        file_put_contents($log_file, "INFO: Status $status acknowledged\n", FILE_APPEND);
    }

    http_response_code(200);
    exit('OK');
}

function creditUserFromCrypto($pdo, $tx, $log_file, $gateway_name) {
    $userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $userStmt->execute([$tx['user_id']]);
    $user = $userStmt->fetch();
    if (!$user) {
        file_put_contents($log_file, "ERROR: User not found\n", FILE_APPEND);
        http_response_code(400);
        exit('No user');
    }

    $payment_amount = $tx['amount'];
    $bonus_percent = 0;
    if ($payment_amount >= 500) $bonus_percent = 15;
    elseif ($payment_amount >= 100) $bonus_percent = 10;
    elseif ($payment_amount >= 50) $bonus_percent = 8;
    elseif ($payment_amount >= 10) $bonus_percent = 5;

    $bonus_amount = ($payment_amount * $bonus_percent) / 100;
    $total_credit = $payment_amount + $bonus_amount;

    $balance_before = $user['balance'];
    $balance_after = $balance_before + $total_credit;

    // Update user balance
    $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?")->execute([$balance_after, $user['id']]);

    // Record final transaction
    $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
                   VALUES (?, 'deposit', ?, ?, ?, 'Crypto', ?, ?, NOW())")
        ->execute([$user['id'], $total_credit, $balance_before, $balance_after, $tx['payment_id'], "Dépôt Crypto ({$gateway_name}) - Confirmé"]);

    // Record bonus
    if ($bonus_amount > 0) {
        $pdo->prepare("INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, description, created_at)
                       VALUES (?, 'bonus', ?, ?, ?, 'System', ?, NOW())")
            ->execute([$user['id'], $bonus_amount, $balance_before + $payment_amount, $balance_after, "Bonus {$bonus_percent}% sur dépôt Crypto ({$gateway_name})"]);
    }

    file_put_contents($log_file, "SUCCESS: Credited user #{$user['id']} with $total_credit (USD) via {$gateway_name}\n", FILE_APPEND);
}
