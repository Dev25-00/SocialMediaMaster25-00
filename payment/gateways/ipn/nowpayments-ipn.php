<?php
/**
 * SMM Mastery - NOWPayments IPN Handler
 * Notes:
 * - NOWPayments sends a JSON payload. When configured, it may send a signature header.
 * - We attempt HMAC verification when an IPN secret is configured in settings ('nowpayments_ipn_secret').
 * - This implementation attempts common header names; please verify against NOWPayments docs and adjust the header/algorithm if needed.
 */
function handleNOWPaymentsIPN($pdo, $post, $server, $log_file) {
    $secret = getSetting($pdo, 'nowpayments_ipn_secret', '');

    $raw = file_get_contents('php://input');
    if (empty($raw)) {
        file_put_contents($log_file, "ERROR: Empty payload from NOWPayments\n", FILE_APPEND);
        http_response_code(400);
        exit('Empty payload');
    }

    // Try to verify signature if secret configured
    $sig_header = $server['HTTP_X_NOWPAYMENTS_SIGNATURE'] ?? $server['HTTP_X_NOWPAYMENTS_SIG'] ?? $server['HTTP_X_NOWPAYMENTS_SIGN'] ?? '';
    if (!empty($secret) && !empty($sig_header)) {
        // Assumption: HMAC SHA512 (common). If your gateway uses different algo, update accordingly.
        $calc = hash_hmac('sha512', $raw, trim($secret));
        if (!hash_equals($calc, $sig_header)) {
            file_put_contents($log_file, "ERROR: NOWPayments signature mismatch\n", FILE_APPEND);
            http_response_code(400);
            exit('Bad signature');
        }
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        file_put_contents($log_file, "ERROR: Invalid JSON payload from NOWPayments\n", FILE_APPEND);
        http_response_code(400);
        exit('Invalid JSON');
    }

    // Extract common fields
    $order_id = $data['order_id'] ?? $data['orderId'] ?? $data['order_id'] ?? ($data['id'] ?? '');
    $price_amount = floatval($data['price_amount'] ?? $data['amount'] ?? $data['pay_amount'] ?? 0);
    $price_currency = $data['price_currency'] ?? $data['price_currency'] ?? ($data['currency'] ?? 'USD');
    $status = strtolower($data['payment_status'] ?? $data['status'] ?? '');

    file_put_contents($log_file, "INFO: NOWPayments IPN received for order_id={$order_id}, amount={$price_amount} {$price_currency}, status={$status}\n", FILE_APPEND);

    if (empty($order_id)) {
        file_put_contents($log_file, "ERROR: NOWPayments order_id missing\n", FILE_APPEND);
        http_response_code(400);
        exit('Missing order_id');
    }

    // Lookup transaction by payment_id (order id)
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE payment_id = ? LIMIT 1");
    $stmt->execute([$order_id]);
    $tx = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tx || $tx['type'] !== 'deposit') {
        file_put_contents($log_file, "INFO: No matching deposit for NOWPayments order {$order_id}\n", FILE_APPEND);
        http_response_code(200);
        exit('OK');
    }

    // Validate amount & currency (tolerance 0.01)
    if (strtoupper($price_currency) !== 'USD' || $price_amount < floatval($tx['amount']) - 0.01) {
        file_put_contents($log_file, "ERROR: NOWPayments amount/currency mismatch for order {$order_id} (got {$price_amount} {$price_currency}, expected {$tx['amount']})\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad amount');
    }

    // Consider the payment final when status indicates finished/confirmed/paid
    $final_states = ['finished', 'confirmed', 'paid', 'success'];
    if (in_array($status, $final_states, true) || (!empty($data['is_paid']) && $data['is_paid'] === true)) {
        // Reuse existing credit logic
        if (function_exists('creditUserFromCrypto')) {
            creditUserFromCrypto($pdo, $tx, $log_file, 'NOWPayments');
            file_put_contents($log_file, "SUCCESS: NOWPayments order {$order_id} processed\n", FILE_APPEND);
        } else {
            file_put_contents($log_file, "ERROR: creditUserFromCrypto not available\n", FILE_APPEND);
        }
    } else {
        file_put_contents($log_file, "INFO: NOWPayments order {$order_id} status {$status} acknowledged but not final\n", FILE_APPEND);
    }

    http_response_code(200);
    exit('OK');
}
