<?php
/**
 * STRIPE PAYMENT HANDLER
 * 
 * Ce fichier gère les paiements par carte bancaire via Stripe
 * 
 * CONFIGURATION REQUISE :
 * 1. Créez un compte Stripe sur https://stripe.com/
 * 2. Installez Stripe PHP : composer require stripe/stripe-php
 * 3. Récupérez vos clés API (Publishable Key & Secret Key)
 * 4. Configurez-les dans Admin > Paramètres > Stripe
 * 
 * DOCUMENTATION :
 * https://stripe.com/docs/payments/checkout
 * https://stripe.com/docs/api/php
 */

require_once '../config.php';
require_once '../functions.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$amount = floatval($_POST['amount'] ?? 0);

// Validation
if ($amount < 5) {
    setFlashMessage('error', 'Le montant minimum est de 5$');
    redirect('../dashboard/balance.php');
}

// Pour l'instant, afficher un message d'information
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Stripe - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 600px; width: 100%;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2563eb; margin-bottom: 15px;">💳 Paiement par Carte</h1>
            <p style="color: #6b7280;">
                Montant : <strong style="font-size: 24px; color: #2563eb;">$<?php echo number_format($amount, 2); ?></strong>
            </p>
        </div>

        <div style="background: #fef3c7; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h3 style="color: #92400e; margin-bottom: 10px;">⚠️ Configuration requise</h3>
            <p style="color: #92400e; font-size: 14px;">
                Le paiement Stripe nécessite une configuration supplémentaire :
            </p>
            <ol style="color: #92400e; font-size: 14px; margin-top: 15px; padding-left: 20px;">
                <li>Créez un compte sur <a href="https://stripe.com/" target="_blank" style="color: #2563eb;">stripe.com</a></li>
                <li>Installez Stripe PHP : <code>composer require stripe/stripe-php</code></li>
                <li>Récupérez vos clés API</li>
                <li>Configurez-les dans Admin > Paramètres</li>
                <li>Complétez le code dans <code>payment/stripe.php</code></li>
            </ol>
        </div>

        <div style="background: #dbeafe; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <h4 style="color: #1e40af; margin-bottom: 10px;">📖 Exemple d'implémentation :</h4>
            <pre style="background: #111827; color: #10b981; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px;">
// Initialiser Stripe
require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_...');

// Créer une session de paiement
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Ajout de fonds',
            ],
            'unit_amount' => $amount * 100, // en centimes
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => SITE_URL . '/payment/stripe-success.php',
    'cancel_url' => SITE_URL . '/dashboard/balance.php',
]);

// Rediriger vers Stripe Checkout
header('Location: ' . $session->url);
            </pre>
        </div>

        <div style="text-align: center;">
            <a href="../dashboard/balance.php" class="btn btn-primary btn-lg">
                ← Retour aux méthodes de paiement
            </a>
        </div>

        <p style="margin-top: 30px; text-align: center; font-size: 12px; color: #6b7280;">
            Pour plus d'informations, consultez la 
            <a href="https://stripe.com/docs" target="_blank" style="color: #2563eb;">documentation Stripe</a>
        </p>
    </div>

</body>
</html>

<?php
/*
 * CODE D'EXEMPLE COMPLET POUR STRIPE (À ADAPTER)
 * 
 * Après avoir installé Stripe PHP, remplacez ce fichier par :
 */

/*
require_once '../config.php';
require_once '../functions.php';
require_once '../vendor/autoload.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$amount = floatval($_POST['amount'] ?? 0);

// Validation
if ($amount < 5 || $amount > 10000) {
    setFlashMessage('error', 'Montant invalide');
    redirect('../dashboard/balance.php');
}

// Récupérer la clé Stripe
$stripe_secret = getSetting($pdo, 'stripe_secret_key', '');

if (empty($stripe_secret)) {
    setFlashMessage('error', 'Stripe non configuré');
    redirect('../dashboard/balance.php');
}

// Initialiser Stripe
\Stripe\Stripe::setApiKey($stripe_secret);

// Calculer bonus
$bonus_percent = 0;
if ($amount >= 500) $bonus_percent = 15;
elseif ($amount >= 100) $bonus_percent = 10;
elseif ($amount >= 50) $bonus_percent = 8;
elseif ($amount >= 10) $bonus_percent = 5;

try {
    // Créer une session Stripe Checkout
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Ajout de fonds - ' . SITE_NAME,
                    'description' => 'Montant: $' . $amount . ($bonus_percent > 0 ? ' (Bonus +' . $bonus_percent . '%)' : ''),
                ],
                'unit_amount' => intval($amount * 100), // Convertir en centimes
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => SITE_URL . '/payment/stripe-success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => SITE_URL . '/dashboard/balance.php',
        'client_reference_id' => $user['id'],
        'metadata' => [
            'user_id' => $user['id'],
            'amount' => $amount,
            'bonus_percent' => $bonus_percent,
        ],
    ]);
    
    // Rediriger vers Stripe Checkout
    header('Location: ' . $session->url);
    exit;
    
} catch (\Stripe\Exception\ApiErrorException $e) {
    setFlashMessage('error', 'Erreur Stripe : ' . $e->getMessage());
    redirect('../dashboard/balance.php');
}
*/
?>
