<?php
/**
 * PAYPAL SUCCESS PAGE
 * L'utilisateur arrive ici après un paiement PayPal réussi
 */

require_once '../config.php';
require_once '../functions.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
</head>
<body style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 500px; width: 100%; text-align: center;">
        
        <div style="font-size: 80px; margin-bottom: 20px;">✅</div>
        
        <h1 style="color: #10b981; margin-bottom: 15px;">Paiement Réussi !</h1>
        
        <p style="color: #6b7280; margin-bottom: 30px;">
            Votre paiement a été traité avec succès. Votre solde sera crédité dans quelques instants.
        </p>
        
        <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 30px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Votre solde actuel</div>
            <div style="font-size: 48px; font-weight: 700; color: #2563eb;">
                <?php echo formatCurrency($user['balance']); ?>
            </div>
        </div>

        <div style="background: #dbeafe; padding: 15px; border-radius: 10px; margin-bottom: 30px; text-align: left;">
            <strong style="color: #1e40af;">ℹ️ Note importante :</strong>
            <p style="color: #1e40af; margin-top: 10px; font-size: 14px;">
                La confirmation PayPal peut prendre quelques minutes. Si votre solde n'est pas mis à jour immédiatement, actualisez la page dans 2-3 minutes.
            </p>
        </div>
        
        <a href="../dashboard/balance.php" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 10px;">
            Voir mon solde
        </a>
        
        <a href="../services/index.php" class="btn btn-secondary btn-block">
            Parcourir les services
        </a>
    </div>

    <script>
        // Auto-redirect après 10 secondes
        setTimeout(function() {
            window.location.href = '../dashboard/balance.php';
        }, 10000);
    </script>
</body>
</html>
