<?php
/**
 * SMM Mastery - BTCPay Server IPN Handler
 * Verifies webhook signature (HMAC SHA256) when a webhook secret is configured.
 * Uses fields: invoiceId / status / amount / currency
 */
function handleBTCPayIPN($pdo, $post, $server, $log_file) {
    $btcpay_secret = getSetting($pdo, 'btcpay_webhook_secret', '') ?: getSetting($pdo, 'btcpay_api_key', '');

    $raw = file_get_contents('php://input');
    if (empty($raw)) {
        file_put_contents($log_file, "ERROR: Empty BTCPay payload\n", FILE_APPEND);
        http_response_code(400);
        exit('Empty payload');
    }

    // Verify signature if secret available
    $sig_header = $server['HTTP_BTCPAY_SIG'] ?? $server['HTTP_X_SIGNATURE'] ?? $server['HTTP_X_HUB_SIGNATURE'] ?? '';
    if (!empty($btcpay_secret) && !empty($sig_header)) {
        $calc = hash_hmac('sha256', $raw, trim($btcpay_secret));
        if (!hash_equals($calc, $sig_header)) {
            file_put_contents($log_file, "ERROR: BTCPay signature mismatch\n", FILE_APPEND);
            http_response_code(400);
            exit('Bad signature');
        }
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        file_put_contents($log_file, "ERROR: Invalid JSON from BTCPay\n", FILE_APPEND);
        http_response_code(400);
        exit('Invalid JSON');
    }

    // Extract fields (BTCPay Greenfield uses invoiceId and status)
    $invoice = $data['invoiceId'] ?? $data['invoice_id'] ?? $data['id'] ?? '';
    $status = strtolower($data['status'] ?? $data['state'] ?? '');
    $amount = floatval($data['amount'] ?? $data['price'] ?? 0);
    $currency = $data['currency'] ?? ($data['price_currency'] ?? '');

    file_put_contents($log_file, "INFO: BTCPay webhook received invoice={$invoice}, status={$status}, amount={$amount} {$currency}\n", FILE_APPEND);

    if (empty($invoice)) {
        file_put_contents($log_file, "ERROR: BTCPay invoice missing\n", FILE_APPEND);
        http_response_code(400);
        exit('Missing invoice');
    }

    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE payment_id = ? LIMIT 1");
    $stmt->execute([$invoice]);
    $tx = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tx || $tx['type'] !== 'deposit') {
        file_put_contents($log_file, "INFO: No matching deposit for BTCPay invoice {$invoice}\n", FILE_APPEND);
        http_response_code(200);
        exit('OK');
    }

    // Validate amount/currency if present
    if (!empty($currency) && strtoupper($currency) !== 'USD') {
        file_put_contents($log_file, "ERROR: BTCPay currency mismatch for invoice {$invoice}\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad currency');
    }

    $final_states = ['complete', 'settled', 'paid'];
    if (in_array($status, $final_states, true) || (!empty($data['is_paid']) && $data['is_paid'] === true)) {
        if (function_exists('creditUserFromCrypto')) {
            creditUserFromCrypto($pdo, $tx, $log_file, 'BTCPay');
            file_put_contents($log_file, "SUCCESS: BTCPay invoice {$invoice} processed\n", FILE_APPEND);
        } else {
            file_put_contents($log_file, "ERROR: creditUserFromCrypto not available\n", FILE_APPEND);
        }
    } else {
        file_put_contents($log_file, "INFO: BTCPay invoice {$invoice} status {$status} acknowledged but not final\n", FILE_APPEND);
    }

    http_response_code(200);
    exit('OK');
}
