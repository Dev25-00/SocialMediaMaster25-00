<?php
/**
 * SIMULATEUR IPN PAYPAL (DÉVELOPPEMENT UNIQUEMENT)
 * 
 * ⚠️ CE FICHIER EST POUR TESTER L'IPN SUR LOCALHOST
 * À SUPPRIMER AVANT LA MISE EN PRODUCTION !
 * 
 * Ce fichier simule une notification IPN PayPal pour créditer
 * automatiquement le solde après un paiement Sandbox.
 */

require_once '../config.php';
require_once '../functions.php';

// SÉCURITÉ : Bloquer en production
if ($_SERVER['SERVER_NAME'] !== 'localhost' && !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('❌ Ce fichier n\'est accessible qu\'en local !');
}

if (!isLoggedIn() || !isAdmin()) {
    die('❌ Accès réservé aux administrateurs !');
}

// Traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $amount = floatval($_POST['amount']);
    
    if ($user_id <= 0 || $amount <= 0) {
        $error = 'Données invalides !';
    } else {
        // Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = 'Utilisateur introuvable !';
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
            $stmt->execute([$balance_after, $user_id]);
            
            // Transaction principale
            $stmt = $pdo->prepare("
                INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
                VALUES (?, 'deposit', ?, ?, ?, 'PayPal', ?, ?, NOW())
            ");
            $stmt->execute([
                $user_id,
                $total_credit,
                $balance_before,
                $balance_after,
                'SANDBOX-' . time(),
                "Dépôt PayPal Sandbox - $" . number_format($amount, 2) . ($bonus_amount > 0 ? " + Bonus $" . number_format($bonus_amount, 2) : "")
            ]);
            
            // Bonus séparé si applicable
            if ($bonus_amount > 0) {
                $stmt = $pdo->prepare("
                    INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, description, created_at)
                    VALUES (?, 'bonus', ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $user_id,
                    $bonus_amount,
                    $balance_before + $amount,
                    $balance_after,
                    "Bonus " . $bonus_percent . "% sur dépôt PayPal Sandbox"
                ]);
            }
            
            $success = "✅ Solde crédité avec succès ! +" . formatCurrency($total_credit);
            
            // Rediriger vers le dashboard utilisateur
            setFlashMessage('success', $success);
            header('Location: ../dashboard/balance.php');
            exit;
        }
    }
}

// Récupérer les dernières transactions PayPal en attente
$stmt = $pdo->query("
    SELECT t.*, u.username, u.email
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.payment_method = 'PayPal'
    AND t.description LIKE '%En attente%'
    ORDER BY t.created_at DESC
    LIMIT 10
");
$pending_transactions = $stmt->fetchAll();

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, username, email, balance FROM users ORDER BY username");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Simulateur IPN PayPal (DEV)</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px;">
    
    <div style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); margin-bottom: 20px;">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 64px; margin-bottom: 15px;">🔧</div>
                <h1 style="color: #2563eb; margin-bottom: 10px;">Simulateur IPN PayPal</h1>
                <p style="color: #6b7280; font-size: 14px;">Crédit manuel après paiement Sandbox</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 30px;">
                <strong style="color: #856404;">⚠️ Comment ça marche ?</strong>
                <ol style="color: #856404; margin: 10px 0 0 20px; font-size: 14px; line-height: 1.8;">
                    <li>Vous faites un paiement sur PayPal Sandbox</li>
                    <li>Le paiement réussit mais le solde n'est pas crédité (IPN bloqué)</li>
                    <li>Utilisez ce formulaire pour créditer manuellement</li>
                    <li>Les bonus sont calculés automatiquement</li>
                </ol>
            </div>

            <h3 style="margin-bottom: 20px;">💳 Créditer un paiement</h3>
            
            <form method="POST" style="margin-bottom: 30px;">
                <div class="form-group">
                    <label for="user_id" style="display: block; margin-bottom: 8px; font-weight: 500;">
                        Utilisateur
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            required
                            style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px;">
                        <option value="">Sélectionner un utilisateur</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?php echo $u['id']; ?>">
                                <?php echo clean($u['username']); ?> 
                                (<?php echo clean($u['email']); ?>) 
                                - Solde: <?php echo formatCurrency($u['balance']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="amount" style="display: block; margin-bottom: 8px; font-weight: 500;">
                        Montant payé sur PayPal ($)
                    </label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           min="0.01" 
                           step="0.01" 
                           placeholder="Ex: 10.00"
                           required
                           style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px;">
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
                    ✅ Créditer le solde maintenant
                </button>
            </form>

            <div style="display: flex; gap: 10px;">
                <a href="../admin/users.php" class="btn btn-secondary btn-block">
                    👥 Voir les utilisateurs
                </a>
                <a href="../dashboard/balance.php" class="btn btn-secondary btn-block">
                    💰 Mon solde
                </a>
            </div>
        </div>

        <?php if (!empty($pending_transactions)): ?>
        <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="margin-bottom: 20px;">⏳ Transactions PayPal en attente</h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 12px; text-align: left; font-weight: 600;">Utilisateur</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600;">Montant</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_transactions as $tx): ?>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 12px;">
                                <strong><?php echo clean($tx['username']); ?></strong><br>
                                <small style="color: #6b7280;"><?php echo clean($tx['email']); ?></small>
                            </td>
                            <td style="padding: 12px;">
                                <strong style="color: #2563eb; font-size: 16px;">
                                    <?php echo formatCurrency($tx['amount']); ?>
                                </strong>
                            </td>
                            <td style="padding: 12px;">
                                <small style="color: #6b7280;">
                                    <?php echo timeAgo($tx['created_at']); ?>
                                </small>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <p style="margin-top: 15px; font-size: 13px; color: #6b7280;">
                💡 Ces transactions sont en attente de crédit. Utilisez le formulaire ci-dessus pour les créditer.
            </p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 20px; padding: 20px; background: #fee2e2; border-radius: 12px; text-align: center;">
            <strong style="color: #991b1b;">🚫 À SUPPRIMER EN PRODUCTION</strong>
            <p style="color: #991b1b; margin: 8px 0 0 0; font-size: 13px;">
                Ce fichier doit être supprimé avant de mettre le site en ligne !<br>
                En production, l'IPN fonctionnera automatiquement.
            </p>
        </div>
    </div>

</body>
</html>
