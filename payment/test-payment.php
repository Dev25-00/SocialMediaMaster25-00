<?php
/**
 * SIMULATION PAIEMENT (DEV ONLY)
 * 
 * ⚠️ CE FICHIER EST UNIQUEMENT POUR LES TESTS EN LOCAL
 * À SUPPRIMER AVANT LA MISE EN PRODUCTION !
 * 
 * Ce fichier permet de simuler un paiement PayPal réussi
 * pour tester l'ajout de fonds sans passer par PayPal.
 */

require_once '../config.php';
require_once '../functions.php';

// SÉCURITÉ : Bloquer en production
if ($_SERVER['SERVER_NAME'] !== 'localhost' && !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('❌ Ce fichier n\'est accessible qu\'en local !');
}

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);

// Traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    
    if ($amount < 1) {
        setFlashMessage('error', 'Le montant minimum est de 1$');
    } else {
        // Calculer le bonus
        $bonus_percent = 0;
        if ($amount >= 500) $bonus_percent = 15;
        elseif ($amount >= 100) $bonus_percent = 10;
        elseif ($amount >= 50) $bonus_percent = 8;
        elseif ($amount >= 10) $bonus_percent = 5;
        
        $bonus_amount = ($amount * $bonus_percent) / 100;
        $total_credit = $amount + $bonus_amount;
        
        // Créditer le solde
        $balance_before = $user['balance'];
        $balance_after = $balance_before + $total_credit;
        
        $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->execute([$balance_after, $user['id']]);
        
        // Transaction principale
        $stmt = $pdo->prepare("
            INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
            VALUES (?, 'deposit', ?, ?, ?, 'TEST', ?, ?, NOW())
        ");
        $stmt->execute([
            $user['id'],
            $total_credit,
            $balance_before,
            $balance_after,
            'TEST-' . time(),
            "TEST PAIEMENT - $" . $amount . ($bonus_amount > 0 ? " + Bonus $" . number_format($bonus_amount, 2) : "")
        ]);
        
        // Bonus séparé si applicable
        if ($bonus_amount > 0) {
            $stmt = $pdo->prepare("
                INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, description, created_at)
                VALUES (?, 'bonus', ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $user['id'],
                $bonus_amount,
                $balance_before + $amount,
                $balance_after,
                "TEST Bonus " . $bonus_percent . "%"
            ]);
        }
        
        setFlashMessage('success', "✅ Solde crédité avec succès ! +$$total_credit");
        redirect('../dashboard/balance.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Paiement (DEV)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 500px; width: 100%;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 64px; margin-bottom: 15px;">🧪</div>
            <h1 style="color: #2563eb; margin-bottom: 10px;">Test Paiement</h1>
            <p style="color: #6b7280; font-size: 14px;">Environnement de développement uniquement</p>
        </div>

        <?php echo renderFlashMessage(); ?>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 30px;">
            <strong style="color: #856404;">⚠️ Attention</strong>
            <p style="color: #856404; margin: 10px 0 0 0; font-size: 14px;">
                Ce fichier permet de créditer votre solde directement, sans passer par PayPal. 
                <strong>À utiliser uniquement en développement !</strong>
            </p>
        </div>

        <div style="background: #f9fafb; padding: 20px; border-radius: 12px; margin-bottom: 25px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Votre solde actuel</div>
            <div style="font-size: 42px; font-weight: 700; color: #2563eb;">
                <?php echo formatCurrency($user['balance']); ?>
            </div>
        </div>

        <form method="POST" style="margin-bottom: 25px;">
            <div class="form-group">
                <label for="amount" style="display: block; margin-bottom: 8px; font-weight: 500;">
                    Montant à créditer ($)
                </label>
                <input type="number" 
                       id="amount" 
                       name="amount" 
                       min="1" 
                       step="0.01" 
                       placeholder="Ex: 50.00"
                       required
                       style="width: 100%; padding: 15px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 18px; text-align: center;">
            </div>
            
            <div style="background: #d1fae5; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <strong style="color: #065f46;">🎁 Bonus automatiques :</strong>
                <div style="color: #065f46; margin-top: 10px; font-size: 14px; line-height: 1.8;">
                    • $10-$49 → +5%<br>
                    • $50-$99 → +8%<br>
                    • $100-$499 → +10%<br>
                    • $500+ → +15%
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                💳 Créditer le solde
            </button>
        </form>

        <div style="display: flex; gap: 10px;">
            <a href="../dashboard/balance.php" class="btn btn-secondary btn-block">
                ← Retour au solde
            </a>
            <a href="../dashboard/index.php" class="btn btn-secondary btn-block">
                Dashboard
            </a>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #fee2e2; border-radius: 10px; text-align: center;">
            <strong style="color: #991b1b;">🚫 À SUPPRIMER EN PRODUCTION</strong>
            <p style="color: #991b1b; margin: 8px 0 0 0; font-size: 12px;">
                Ce fichier doit être supprimé avant de mettre le site en ligne !
            </p>
        </div>
    </div>

</body>
</html>
