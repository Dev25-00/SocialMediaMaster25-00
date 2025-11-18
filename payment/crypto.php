<?php
/**
 * SMM Mastery - Crypto Payment (CoinPayments)
 * Guide: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\payment\
 * Compatibilité: PHP 8+, Mobile
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../dashboard/balance.php');
}

// CSRF basic check (optional if already enforced globally)
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Session expirée. Veuillez réessayer.');
    redirect('../dashboard/balance.php');
}

$user = getCurrentUser($pdo);
$amount = floatval($_POST['amount'] ?? 0);

if ($amount < MIN_DEPOSIT_AMOUNT) {
    setFlashMessage('error', 'Le montant minimum est de ' . formatCurrency(MIN_DEPOSIT_AMOUNT));
    redirect('../dashboard/balance.php');
}
if ($amount > MAX_DEPOSIT_AMOUNT) {
    setFlashMessage('error', 'Montant maximum: ' . formatCurrency(MAX_DEPOSIT_AMOUNT));
    redirect('../dashboard/balance.php');
}

// CoinPayments settings from DB
$crypto_gateway = getSetting($pdo, 'crypto_gateway', 'coinpayments');
$cp_currency = getSetting($pdo, 'crypto_currency', 'USDT');

// Route to gateway-specific handler
switch ($crypto_gateway) {
    case 'coinpayments':
        require_once __DIR__ . '/gateways/coinpayments.php';
        handleCoinPayments($pdo, $user, $amount, $cp_currency);
        break;
    case 'coingate':
        require_once __DIR__ . '/gateways/coingate.php';
        handleCoinGate($pdo, $user, $amount, $cp_currency);
        break;
    case 'btcpay':
        require_once __DIR__ . '/gateways/btcpay.php';
        handleBTCPay($pdo, $user, $amount, $cp_currency);
        break;
    case 'nowpayments':
        require_once __DIR__ . '/gateways/nowpayments.php';
        handleNOWPayments($pdo, $user, $amount, $cp_currency);
        break;
    default:
        setFlashMessage('error', "Gateway crypto non supporté: {$crypto_gateway}.");
        redirect('../dashboard/balance.php');
}
