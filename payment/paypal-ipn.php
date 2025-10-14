<?php
/**
 * PAYPAL IPN (Instant Payment Notification)
 * 
 * Ce fichier reçoit les notifications de PayPal après un paiement réussi
 * et crédite automatiquement le solde de l'utilisateur.
 * 
 * ⚠️ NE FONCTIONNE PAS SUR LOCALHOST !
 * Ce fichier nécessite que votre site soit accessible publiquement.
 * 
 * DOCUMENTATION PAYPAL IPN :
 * https://developer.paypal.com/api/nvp-soap/ipn/
 */

require_once '../config.php';
require_once '../functions.php';

// Log des requêtes IPN pour debug
$log_file = '../logs/paypal_ipn.log';
$log_dir = dirname($log_file);
if (!file_exists($log_dir)) {
    mkdir($log_dir, 0777, true);
}

// Logger la requête
file_put_contents($log_file, date('[Y-m-d H:i:s] ') . "IPN Received\n", FILE_APPEND);
// Archived original debug line:
// file_put_contents($log_file, print_r($_POST, true) . "\n", FILE_APPEND);
// Use structured logging instead (safer, less noisy):
error_log(date('[Y-m-d H:i:s] ') . 'IPN POST: ' . json_encode($_POST) . "\n");

// Lire les données POST de PayPal
$raw_post = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post);
$myPost = [];
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}

// Lire la configuration
$paypal_mode = getSetting($pdo, 'paypal_mode', 'sandbox');
$paypal_email = getSetting($pdo, 'paypal_email', '');

// URL de vérification PayPal
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

// Envoyer la requête de vérification à PayPal
$paypal_url = $paypal_mode === 'live'
    ? 'https://www.paypal.com/cgi-bin/webscr'
    : 'https://www.sandbox.paypal.com/cgi-bin/webscr';

$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$res = curl_exec($ch);
curl_close($ch);

file_put_contents($log_file, "PayPal Response: $res\n", FILE_APPEND);

// Vérifier la réponse de PayPal
if (strcmp($res, "VERIFIED") == 0) {
    
    // Récupérer les informations du paiement
    $payment_status = $_POST['payment_status'] ?? '';
    $payment_amount = floatval($_POST['mc_gross'] ?? 0);
    $payment_currency = $_POST['mc_currency'] ?? '';
    $txn_id = $_POST['txn_id'] ?? '';
    $receiver_email = $_POST['receiver_email'] ?? '';
    $payer_email = $_POST['payer_email'] ?? '';
    $custom = $_POST['custom'] ?? ''; // user_id
    $item_number = $_POST['item_number'] ?? ''; // transaction_id
    
    file_put_contents($log_file, "Payment Verified - Status: $payment_status, Amount: $payment_amount\n", FILE_APPEND);
    
    // Vérifications de sécurité
    if (strtolower($receiver_email) !== strtolower($paypal_email)) {
        file_put_contents($log_file, "ERROR: Receiver email mismatch\n", FILE_APPEND);
        exit;
    }
    
    if ($payment_status !== 'Completed') {
        file_put_contents($log_file, "INFO: Payment not completed yet\n", FILE_APPEND);
        exit;
    }
    
    if ($payment_currency !== 'USD') {
        file_put_contents($log_file, "ERROR: Currency is not USD\n", FILE_APPEND);
        exit;
    }
    
    // Vérifier si la transaction existe déjà
    $stmt = $pdo->prepare("SELECT id FROM transactions WHERE payment_id = ?");
    $stmt->execute([$txn_id]);
    if ($stmt->fetch()) {
        file_put_contents($log_file, "INFO: Transaction already processed\n", FILE_APPEND);
        exit;
    }
    
    // Récupérer l'utilisateur
    $user_id = (int)$custom;
    if ($user_id <= 0) {
        file_put_contents($log_file, "ERROR: Invalid user_id\n", FILE_APPEND);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        file_put_contents($log_file, "ERROR: User not found\n", FILE_APPEND);
        exit;
    }
    
    // Calculer le bonus
    $bonus_percent = 0;
    if ($payment_amount >= 500) $bonus_percent = 15;
    elseif ($payment_amount >= 100) $bonus_percent = 10;
    elseif ($payment_amount >= 50) $bonus_percent = 8;
    elseif ($payment_amount >= 10) $bonus_percent = 5;
    
    $bonus_amount = ($payment_amount * $bonus_percent) / 100;
    $total_credit = $payment_amount + $bonus_amount;
    
    // Créditer le solde
    $balance_before = $user['balance'];
    $balance_after = $balance_before + $total_credit;
    
    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$balance_after, $user_id]);
    
    // Enregistrer la transaction
    $stmt = $pdo->prepare("
        INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
        VALUES (?, 'deposit', ?, ?, ?, 'PayPal', ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        $total_credit,
        $balance_before,
        $balance_after,
        $txn_id,
        "Dépôt PayPal - $" . $payment_amount . ($bonus_amount > 0 ? " + Bonus $" . $bonus_amount : "")
    ]);
    
    // Si bonus, ajouter une transaction séparée pour traçabilité
    if ($bonus_amount > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, description, created_at)
            VALUES (?, 'bonus', ?, ?, ?, 'System', ?, NOW())
        ");
        $stmt->execute([
            $user_id,
            $bonus_amount,
            $balance_before + $payment_amount,
            $balance_after,
            "Bonus " . $bonus_percent . "% sur dépôt PayPal"
        ]);
    }
    
    file_put_contents($log_file, "SUCCESS: User $user_id credited with $$total_credit ($$payment_amount + $$bonus_amount bonus)\n", FILE_APPEND);
    
    // Envoyer un email de confirmation (optionnel)
    /*
    $subject = "Votre dépôt a été crédité";
    $message = "
        <h2>Dépôt réussi !</h2>
        <p>Bonjour {$user['username']},</p>
        <p>Votre paiement PayPal de $$payment_amount a été traité avec succès.</p>
        <p><strong>Montant crédité : $$total_credit</strong></p>
        <p>Votre nouveau solde : $$balance_after</p>
        <p>Merci de votre confiance !</p>
    ";
    sendEmail($user['email'], $subject, $message);
    */
    
} elseif (strcmp($res, "INVALID") == 0) {
    file_put_contents($log_file, "ERROR: Invalid IPN\n", FILE_APPEND);
} else {
    file_put_contents($log_file, "ERROR: Unknown response from PayPal\n", FILE_APPEND);
}

file_put_contents($log_file, "---\n\n", FILE_APPEND);
http_response_code(200);
?>
