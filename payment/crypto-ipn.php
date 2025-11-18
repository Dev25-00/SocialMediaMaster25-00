<?php
/**
 * SMM Mastery - Crypto IPN (CoinPayments)
 * Guide: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 * Compatibilité: PHP 8+, Mobile
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$log_file = __DIR__ . '/../logs/crypto_ipn.log';
if (!file_exists(dirname($log_file))) {
    mkdir(dirname($log_file), 0777, true);
}

// Log entrée brute
file_put_contents($log_file, date('[Y-m-d H:i:s] ') . 'IPN: ' . json_encode($_POST) . "\n", FILE_APPEND);

// Route vers handler spécifique selon gateway
$gateway = getSetting($pdo, 'crypto_gateway', 'coinpayments');

switch ($gateway) {
    case 'coinpayments':
        require_once __DIR__ . '/gateways/ipn/coinpayments-ipn.php';
        handleCoinPaymentsIPN($pdo, $_POST, $_SERVER, $log_file);
        break;
    case 'coingate':
        require_once __DIR__ . '/gateways/ipn/coingate-ipn.php';
        handleCoinGateIPN($pdo, $_POST, $_SERVER, $log_file);
        break;
    case 'btcpay':
        require_once __DIR__ . '/gateways/ipn/btcpay-ipn.php';
        handleBTCPayIPN($pdo, $_POST, $_SERVER, $log_file);
        break;
    case 'nowpayments':
        require_once __DIR__ . '/gateways/ipn/nowpayments-ipn.php';
        handleNOWPaymentsIPN($pdo, $_POST, $_SERVER, $log_file);
        break;
    default:
        file_put_contents($log_file, "ERROR: Unknown gateway: {$gateway}\n", FILE_APPEND);
        http_response_code(400);
        exit('Bad gateway');
}
