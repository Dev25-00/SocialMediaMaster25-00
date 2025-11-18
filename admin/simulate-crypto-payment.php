<?php
/**
 * SMM Mastery - Simulation Paiements Crypto (Mode Dev/Test)
 * Date: 16 Octobre 2025
 * 
 * Page admin pour simuler des paiements crypto SANS webhooks réels
 * Utile en phase de développement avant d'avoir un hosting public
 * 
 * Documentation: DOCS_DEV_TO_PROD/04_DEVELOPMENT_GUIDES/payment/TEST_CRYPTO_LOCAL.md
 */

require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$success = '';
$error = '';
$pending_tx = null;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // ÉTAPE 1 : Créer paiement pending
    if ($action === 'create_pending') {
        $user_id = intval($_POST['user_id'] ?? 0);
        $amount = floatval($_POST['amount'] ?? 0);
        $gateway = clean($_POST['gateway'] ?? 'nowpayments');
        $crypto_currency = clean($_POST['crypto_currency'] ?? 'USDT');
        
        if ($user_id <= 0 || $amount < 1) {
            $error = 'User ID et montant valides requis';
        } else {
            try {
                // Créer transaction pending (respecter le schéma existant)
                $tx_id = 'SIM_' . uniqid() . '_' . time();
                // Solde actuel
                $stmt_b = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
                $stmt_b->execute([$user_id]);
                $balance_before = $stmt_b->fetchColumn() ?: 0;
                $balance_after = $balance_before; // pas encore crédité

                $stmt = $pdo->prepare("
                    INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, payment_id, description, created_at)
                    VALUES (?, 'deposit', ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$user_id, $amount, $balance_before, $balance_after, "crypto_$gateway", $tx_id, 'Dépôt Crypto (SIMULATION) - En attente']);
                
                $insert_id = $pdo->lastInsertId();
                
                // Log
                $log_msg = "SIMULATION: Created pending crypto payment\n";
                $log_msg .= "User ID: $user_id, Amount: $amount USD, Gateway: $gateway, Currency: $crypto_currency\n";
                $log_msg .= "Transaction ID: $tx_id\n";
                file_put_contents(__DIR__ . '/../payment/logs/crypto_simulation.log', date('Y-m-d H:i:s') . " - $log_msg\n", FILE_APPEND);
                
                $success = "✅ Transaction pending créée ! (ID: $insert_id, TX: $tx_id)";
                
                // Récupérer la transaction pour affichage
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
                $stmt->execute([$insert_id]);
                $pending_tx = $stmt->fetch(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
            }
        }
    }
    
    // ÉTAPE 2 : Simuler IPN confirmation
    if ($action === 'confirm_payment') {
        $tx_id = intval($_POST['transaction_id'] ?? 0);
        
        if ($tx_id <= 0) {
            $error = 'Transaction ID invalide';
        } else {
            try {
                // Récupérer transaction pending (filtrer par description 'En attente')
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND description LIKE '%En attente%'");
                $stmt->execute([$tx_id]);
                $tx = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$tx) {
                    $error = 'Transaction introuvable ou déjà confirmée';
                } else {
                    // Calculer bonus (10% par défaut)
                    $bonus_percent = 10;
                    $bonus_amount = round($tx['amount'] * ($bonus_percent / 100), 2);
                    $total_credit = $tx['amount'] + $bonus_amount;
                    
                    // Récupérer user
                    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt_user->execute([$tx['user_id']]);
                    $target_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
                    
                    $old_balance = $target_user['balance'];
                    $new_balance = $old_balance + $total_credit;
                    
                    // Mettre à jour le solde utilisateur
                    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
                    $stmt->execute([$new_balance, $tx['user_id']]);

                    // Mettre à jour la transaction initiale pour marquer le nouveau solde et indiquer la confirmation
                    $stmt = $pdo->prepare("UPDATE transactions SET balance_before = ?, balance_after = ?, payment_method = ?, description = ? WHERE id = ?");
                    $stmt->execute([$old_balance, $new_balance, 'Crypto (simulation)', 'Dépôt Crypto (SIMULATION) - Confirmé', $tx_id]);
                    
                    // Créer transaction bonus
                    if ($bonus_amount > 0) {
                        // Insérer transaction bonus (avec solde avant/après)
                        $stmt = $pdo->prepare("
                            INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, payment_method, description, created_at)
                            VALUES (?, 'bonus', ?, ?, ?, 'crypto_bonus', ?, NOW())
                        ");
                        $stmt->execute([
                            $tx['user_id'],
                            $bonus_amount,
                            $old_balance + $tx['amount'],
                            $new_balance,
                            "Bonus crypto {$bonus_percent}% (simulation)"
                        ]);
                    }
                    
                    // Log
                    $log_msg = "SIMULATION: Crypto payment confirmed\n";
                    $log_msg .= "User ID: {$tx['user_id']}, Amount: {$tx['amount']} USD\n";
                    $log_msg .= "Bonus: $bonus_amount USD ({$bonus_percent}%)\n";
                    $log_msg .= "Total credited: $total_credit USD\n";
                    $log_msg .= "Balance: $old_balance → $new_balance USD\n";
                    file_put_contents(__DIR__ . '/../payment/logs/crypto_simulation.log', date('Y-m-d H:i:s') . " - $log_msg\n", FILE_APPEND);
                    
                    $success = "✅ Paiement confirmé ! Balance créditée : {$tx['amount']} USD + bonus $bonus_amount USD = $total_credit USD<br>";
                    $success .= "Balance user #{$tx['user_id']} : $old_balance USD → $new_balance USD";
                }
                
            } catch (Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
            }
        }
    }
}

// Récupérer liste users
$stmt = $pdo->query("SELECT id, username, email, balance FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer dernières simulations
$stmt = $pdo->query("
    SELECT t.*, u.username, u.email 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    WHERE t.payment_method LIKE 'crypto_%'
    ORDER BY t.created_at DESC
    LIMIT 10
");
$recent_txs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Simulation Crypto - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
    <style>
        .sim-step {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .sim-step h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .step-badge {
            background: #3b82f6;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }
        .pending-tx-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .tx-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .tx-table th {
            background: #f1f5f9;
            padding: 10px;
            text-align: left;
            font-weight: 600;
        }
        .tx-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-failed { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>🧪 Simulation Paiements Crypto</h1>
            </div>
        </div>

        <!-- Alert Info -->
        <div class="card" style="margin-bottom: 20px; background: #ecfeff; border: 2px solid #06b6d4;">
            <div style="padding: 20px;">
                <h3 style="color: #0e7490; margin-bottom: 10px;">ℹ️ Mode Simulation (Dev/Test)</h3>
                <p style="color: #0e7490; margin: 0; line-height: 1.6;">
                    Cette page permet de simuler des paiements crypto <strong>sans webhooks réels</strong>.
                    Parfait pour tester la logique de crédit avant d'avoir un hosting public.
                    <br><strong>Note :</strong> Les transactions créées ici sont réelles dans la BDD mais marquées comme simulations dans les logs.
                </p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- ÉTAPE 1 : Créer paiement pending -->
        <div class="sim-step">
            <h3>
                <span class="step-badge">1</span>
                Créer un paiement crypto (pending)
            </h3>
            
            <form method="POST">
                <input type="hidden" name="action" value="create_pending">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Utilisateur *</label>
                        <select name="user_id" required>
                            <option value="">Sélectionner un user...</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?php echo $u['id']; ?>">
                                    #<?php echo $u['id']; ?> - <?php echo htmlspecialchars($u['username']); ?> 
                                    (<?php echo htmlspecialchars($u['email']); ?>) 
                                    - Balance: $<?php echo number_format($u['balance'], 2); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Montant (USD) *</label>
                        <input type="number" name="amount" step="0.01" min="1" value="10.00" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px;">
                    <div class="form-group">
                        <label>Gateway</label>
                        <select name="gateway">
                            <option value="nowpayments">NOWPayments</option>
                            <option value="coingate">CoinGate</option>
                            <option value="btcpay">BTCPay Server</option>
                            <option value="coinpayments">CoinPayments</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Crypto Currency</label>
                        <select name="crypto_currency">
                            <option value="USDT">USDT</option>
                            <option value="USDC">USDC</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                    🚀 Créer paiement pending
                </button>
            </form>
        </div>

        <!-- ÉTAPE 2 : Confirmer paiement -->
        <div class="sim-step">
            <h3>
                <span class="step-badge">2</span>
                Simuler IPN Confirmation
            </h3>
            
            <?php if ($pending_tx): ?>
                <div class="pending-tx-box">
                    <strong>⏳ Transaction Pending Créée :</strong><br>
                    <div style="margin-top: 10px; font-family: monospace; font-size: 13px;">
                        ID: <?php echo $pending_tx['id']; ?><br>
                        User ID: <?php echo $pending_tx['user_id']; ?><br>
                        Amount: $<?php echo number_format($pending_tx['amount'], 2); ?><br>
                        Method: <?php echo htmlspecialchars($pending_tx['payment_method']); ?><br>
                        TX ID: <?php echo htmlspecialchars($pending_tx['transaction_id']); ?><br>
                        Status: <span class="status-badge status-pending"><?php echo $pending_tx['status']; ?></span>
                    </div>
                </div>
                
                <form method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="action" value="confirm_payment">
                    <input type="hidden" name="transaction_id" value="<?php echo $pending_tx['id']; ?>">
                    
                    <p style="color: #64748b; margin-bottom: 15px;">
                        Cliquer pour simuler la confirmation du paiement par le gateway crypto.
                        La balance sera créditée automatiquement avec bonus de 10%.
                    </p>
                    
                    <button type="submit" class="btn btn-success">
                        ✅ Confirmer paiement (Simuler IPN)
                    </button>
                </form>
            <?php else: ?>
                <p style="color: #64748b; margin: 0;">
                    Créez d'abord une transaction pending à l'étape 1.
                </p>
            <?php endif; ?>
        </div>

        <!-- Historique des simulations -->
        <div class="card">
            <div class="card-header">
                <h2>📊 Historique Simulations (10 dernières)</h2>
            </div>
            <div style="padding: 20px;">
                <?php if (empty($recent_txs)): ?>
                    <p style="color: #64748b;">Aucune simulation encore.</p>
                <?php else: ?>
                    <table class="tx-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_txs as $tx): ?>
                                <tr>
                                    <td>#<?php echo $tx['id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($tx['username']); ?><br>
                                        <small style="color: #64748b;"><?php echo htmlspecialchars($tx['email']); ?></small>
                                    </td>
                                    <td>$<?php echo number_format($tx['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($tx['payment_method']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $tx['status']; ?>">
                                            <?php echo $tx['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($tx['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Logs -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h2>📝 Logs Simulation</h2>
            </div>
            <div style="padding: 20px;">
                <?php
                $log_file = __DIR__ . '/../payment/logs/crypto_simulation.log';
                if (file_exists($log_file)):
                    $logs = file_get_contents($log_file);
                    $lines = array_slice(array_filter(explode("\n", $logs)), -20); // 20 dernières lignes
                ?>
                    <pre style="background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px; max-height: 400px;"><?php echo htmlspecialchars(implode("\n", array_reverse($lines))); ?></pre>
                <?php else: ?>
                    <p style="color: #64748b;">Aucun log disponible.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
