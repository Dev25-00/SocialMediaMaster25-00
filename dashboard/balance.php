<?php
/**
 * MON SOLDE - Version 2.0
 * Gestion du solde et ajout de fonds
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../includes/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);

// Récupérer l'historique des transactions
$stmt = $pdo->prepare("
    SELECT * FROM transactions 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 50
");
$stmt->execute([$user['id']]);
$transactions = $stmt->fetchAll();

// Calculer statistiques
$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN type = 'deposit' THEN amount ELSE 0 END) as total_deposits,
        SUM(CASE WHEN type = 'order' THEN amount ELSE 0 END) as total_spent,
        SUM(CASE WHEN type = 'bonus' THEN amount ELSE 0 END) as total_bonus
    FROM transactions 
    WHERE user_id = ?
");
$stmt->execute([$user['id']]);
$stats = $stmt->fetch();

// Récupérer les paliers de bonus depuis la config
global $DEPOSIT_BONUS_TIERS;
$bonus_text_parts = [];
foreach ($DEPOSIT_BONUS_TIERS as $tier) {
    $min = number_format($tier[0], 0);
    $max = $tier[1] >= 999999 ? '+' : '-$' . number_format($tier[1], 0);
    $percent = $tier[2];
    $bonus_text_parts[] = "+{$percent}% (\${$min}{$max})";
}
$bonus_text = implode(' • ', $bonus_text_parts);

// Configuration page
$page_title = "Mon Solde";
$page_title_bar = "Mon Solde";

// Inclure header simple
require_once __DIR__ . '/../includes/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
        
        <?php 
        // Header configuration
        $page_header_title = "Mon Solde";
        $page_header_icon = "wallet";
        $page_header_description = getIcon('info', false, 'sm') . " Rechargez rapidement et commencez à utiliser nos services";
        $page_header_gradient = false;
        require_once __DIR__ . '/../includes/page-header.php';
        ?>
        
        <?php echo renderFlashMessage(); ?>

        <!-- Quick Stats - Ligne compacte (Statistiques utiles) -->
        <div class="quick-stats-mini">
            <div class="stat-mini">
                <span class="stat-label-mini"><?php echo getIcon('add', false, 'sm'); ?> Déposé</span>
                <span class="stat-value-mini"><?php echo formatCurrency($stats['total_deposits'] ?? 0); ?></span>
            </div>
            <div class="stat-mini">
                <span class="stat-label-mini"><?php echo getIcon('orders', false, 'sm'); ?> Dépensé</span>
                <span class="stat-value-mini"><?php echo formatCurrency($stats['total_spent'] ?? 0); ?></span>
            </div>
            <div class="stat-mini">
                <span class="stat-label-mini"><?php echo getIcon('premium', false, 'sm'); ?> Bonus</span>
                <span class="stat-value-mini"><?php echo formatCurrency($stats['total_bonus'] ?? 0); ?></span>
            </div>
        </div>

        <!-- Bonus Table - Mise en avant visuelle -->
        <div class="bonus-section">
            <div class="bonus-header">
                <div class="bonus-header-icon"><?php echo getIcon('premium', true, 'xl'); ?></div>
                <div class="bonus-header-content">
                    <h3><?php echo getIcon('sparkles', false, 'sm'); ?> Bonus sur Recharge</h3>
                    <p>Plus vous rechargez, plus vous gagnez !</p>
                </div>
            </div>
            <div class="bonus-table">
                <?php foreach ($DEPOSIT_BONUS_TIERS as $tier): ?>
                    <div class="bonus-tier-card" 
                         data-min-amount="<?php echo $tier[0]; ?>" 
                         onclick="fillPaymentAmount(<?php echo $tier[0]; ?>)"
                         style="cursor: pointer;">
                        <div class="bonus-tier-amount">
                            $<?php echo number_format($tier[0], 0); ?><?php echo $tier[1] >= 999999 ? '+' : ' - $' . number_format($tier[1], 0); ?>
                        </div>
                        <div class="bonus-tier-percent">
                            +<?php echo $tier[2]; ?>%
                        </div>
                        <div class="bonus-tier-label">Cliquez pour appliquer</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Payment Methods Section -->
        <div id="add-funds" class="section-anchor">
            <h3 class="section-title-mini">
                <?php echo getIcon('money', true, 'md'); ?>
                Méthodes de paiement disponibles maintenant et prochainement
            </h3>
            
            <div class="payment-grid-mini">
                
                <!-- PayPal Compact -->
                <div class="payment-card-mini">
                    <div class="payment-header-mini">
                        <div class="payment-icon-mini"><?php echo getIcon('paypal', true, 'lg'); ?></div>
                        <div>
                            <h4>PayPal</h4>
                            <p>Instantané</p>
                        </div>
                    </div>
                    <form action="<?php echo SITE_URL; ?>/payment/paypal.php" method="POST" class="payment-form-mini">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="input-group-mini">
                            <span class="input-prefix">$</span>
                            <input type="number" 
                                   name="amount" 
                                   placeholder="<?php echo MIN_DEPOSIT_AMOUNT; ?>" 
                                   min="<?php echo MIN_DEPOSIT_AMOUNT; ?>" 
                                   max="<?php echo MAX_DEPOSIT_AMOUNT; ?>"
                                   step="0.01" 
                                   required 
                                   class="input-mini">
                        </div>
                        <button type="submit" class="btn-pay-mini btn-paypal">
                            Payer
                        </button>
                    </form>
                </div>

                <!-- Carte Compact -->
                <div class="payment-card-mini">
                    <div class="payment-header-mini">
                        <div class="payment-icon-mini"><?php echo getIcon('card', true, 'lg'); ?></div>
                        <div>
                            <h4>Carte Bancaire</h4>
                            <p>Sécurisé</p>
                        </div>
                    </div>
                    <form action="<?php echo SITE_URL; ?>/payment/stripe.php" method="POST" class="payment-form-mini">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="input-group-mini">
                            <span class="input-prefix">$</span>
                            <input type="number" 
                                   name="amount" 
                                   placeholder="<?php echo MIN_DEPOSIT_AMOUNT; ?>" 
                                   min="<?php echo MIN_DEPOSIT_AMOUNT; ?>"
                                   max="<?php echo MAX_DEPOSIT_AMOUNT; ?>"
                                   step="0.01" 
                                   required 
                                   class="input-mini">
                        </div>
                        <button type="submit" class="btn-pay-mini btn-card">
                            Payer
                        </button>
                    </form>
                </div>

                <!-- Crypto Compact -->
                <div class="payment-card-mini">
                    <div class="payment-header-mini">
                        <div class="payment-icon-mini"><?php echo getIcon('bitcoin', true, 'lg'); ?></div>
                        <div>
                            <h4>Crypto</h4>
                            <p>BTC, ETH, USDT</p>
                        </div>
                    </div>
                    <form action="<?php echo SITE_URL; ?>/payment/crypto.php" method="POST" class="payment-form-mini">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="input-group-mini">
                            <span class="input-prefix">$</span>
                            <input type="number" 
                                   name="amount" 
                                   placeholder="<?php echo MIN_DEPOSIT_AMOUNT; ?>" 
                                   min="<?php echo MIN_DEPOSIT_AMOUNT; ?>"
                                   max="<?php echo MAX_DEPOSIT_AMOUNT; ?>"
                                   step="0.01" 
                                   required 
                                   class="input-mini">
                        </div>
                        <button type="submit" class="btn-pay-mini btn-crypto">
                            Payer
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- Transaction History -->
        <div class="section-header-mini">
            <h3>
                <?php echo getIcon('chart', false, 'md'); ?>
                Historique des transactions
            </h3>
        </div>
        
        <div class="card">
            <?php if (empty($transactions)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <?php echo getIcon('chart', true, 'xl'); ?>
                    </div>
                    <h3>Aucune transaction</h3>
                    <p>Votre historique de transactions apparaîtra ici</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo getIcon('info', false, 'sm'); ?> Type</th>
                                <th><?php echo getIcon('edit', false, 'sm'); ?> Description</th>
                                <th><?php echo getIcon('money', false, 'sm'); ?> Montant</th>
                                <th><?php echo getIcon('wallet', false, 'sm'); ?> Solde avant</th>
                                <th><?php echo getIcon('wallet', false, 'sm'); ?> Solde après</th>
                                <th><?php echo getIcon('calendar', false, 'sm'); ?> Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $tx): ?>
                            <tr>
                                <td>
                                    <?php
                                    $type_map = [
                                        'deposit' => ['icon' => 'add', 'text' => 'Dépôt', 'class' => 'tx-deposit'],
                                        'order' => ['icon' => 'orders', 'text' => 'Commande', 'class' => 'tx-order'],
                                        'refund' => ['icon' => 'money', 'text' => 'Remboursement', 'class' => 'tx-refund'],
                                        'bonus' => ['icon' => 'premium', 'text' => 'Bonus', 'class' => 'tx-bonus']
                                    ];
                                    $type = $type_map[$tx['type']] ?? ['icon' => 'info', 'text' => $tx['type'], 'class' => ''];
                                    ?>
                                    <span class="tx-type <?php echo $type['class']; ?>">
                                        <?php echo getIcon($type['icon'], false, 'sm'); ?>
                                        <?php echo $type['text']; ?>
                                    </span>
                                </td>
                                <td><?php echo clean($tx['description']); ?></td>
                                <td>
                                    <strong class="<?php echo in_array($tx['type'], ['deposit', 'refund', 'bonus']) ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo in_array($tx['type'], ['deposit', 'refund', 'bonus']) ? '+' : '-'; ?>
                                        <?php echo formatCurrency($tx['amount']); ?>
                                    </strong>
                                </td>
                                <td><?php echo formatCurrency($tx['balance_before']); ?></td>
                                <td><strong><?php echo formatCurrency($tx['balance_after']); ?></strong></td>
                                <td>
                                    <div class="date-cell">
                                        <?php echo date('d/m/Y', strtotime($tx['created_at'])); ?>
                                        <small><?php echo date('H:i', strtotime($tx['created_at'])); ?></small>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
/* ========================================
   BALANCE PAGE - STYLE MINIMALISTE  
   ======================================== */

/* Quick Stats Compact */
.quick-stats-mini {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 30px;
}

.stat-mini {
    background: white;
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.stat-label-mini {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-value-mini {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}

/* ========================================
   BONUS SECTION - Tableau visuel attractif compact
   ======================================== */
.bonus-section {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    border: 2px solid #fbbf24;
    box-shadow: 
        0 2px 8px rgba(251, 191, 36, 0.25),
        inset 0 1px 2px rgba(255, 255, 255, 0.5);
    position: relative;
    overflow: hidden;
}

.bonus-section::before {
    content: '⭐';
    position: absolute;
    top: -15px;
    right: -15px;
    font-size: 80px;
    opacity: 0.1;
}

.bonus-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.bonus-header-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
}

.bonus-header-content h3 {
    font-size: 16px;
    font-weight: 700;
    color: #78350f;
    margin: 0 0 2px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.bonus-header-content p {
    font-size: 12px;
    color: #92400e;
    margin: 0;
    font-weight: 500;
}

.bonus-table {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 8px;
}

.bonus-tier-card {
    background: white;
    border-radius: 8px;
    padding: 10px 8px;
    text-align: center;
    border: 2px solid #fbbf24;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.bonus-tier-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(251, 191, 36, 0.2) 50%, 
        transparent 100%);
    animation: bonusShine 3s ease-in-out infinite;
}

@keyframes bonusShine {
    0%, 30% { left: -100%; }
    60%, 100% { left: 100%; }
}

.bonus-tier-card:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 4px 10px rgba(251, 191, 36, 0.25),
        0 1px 4px rgba(0, 0, 0, 0.08);
    border-color: #f59e0b;
}

.bonus-tier-amount {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
}

.bonus-tier-percent {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 2px;
    line-height: 1;
}

.bonus-tier-label {
    font-size: 10px;
    color: #92400e;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    transition: all 0.3s ease;
}

/* Indicateur cliquable sur hover */
.bonus-tier-card:hover .bonus-tier-label {
    color: #f59e0b;
}

.bonus-tier-card:active {
    transform: scale(0.95);
}

/* Section Title */
.section-title-mini {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header-mini h3 {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 30px 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Payment Grid Compact */
.payment-grid-mini {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.payment-card-mini {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.payment-card-mini:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.payment-header-mini {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.payment-icon-mini {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.payment-header-mini h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 2px 0;
}

.payment-header-mini p {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
}

/* Form Compact */
.payment-form-mini {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.input-group-mini {
    position: relative;
}

.input-prefix {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    font-weight: 600;
    color: #6b7280;
}

.input-mini {
    width: 100%;
    padding: 10px 12px 10px 32px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.input-mini:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-pay-mini {
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: white;
}

.btn-paypal {
    background: #0070ba;
}

.btn-paypal:hover {
    background: #005a92;
    transform: translateY(-1px);
}

.btn-card {
    background: #635bff;
}

.btn-card:hover {
    background: #4f46e5;
    transform: translateY(-1px);
}

.btn-crypto {
    background: #f7931a;
}

.btn-crypto:hover {
    background: #e67e00;
    transform: translateY(-1px);
}

/* Responsive Mobile */
@media (max-width: 768px) {
    .quick-stats-mini {
        grid-template-columns: 1fr;
    }
    
    .bonus-table {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .stat-mini {
        padding: 14px;
    }
    
    .payment-grid-mini {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
/**
 * Remplir les champs de paiement avec le montant du bonus sélectionné
 */
function fillPaymentAmount(amount) {
    // Récupérer tous les inputs de montant dans les formulaires de paiement
    const amountInputs = document.querySelectorAll('.payment-form-mini input[name="amount"]');
    
    // Remplir chaque input avec le montant
    amountInputs.forEach(input => {
        input.value = amount;
        // Animation visuelle
        input.style.transition = 'all 0.3s ease';
        input.style.backgroundColor = '#fef3c7';
        input.style.borderColor = '#f59e0b';
        
        // Retour à la normale après 1 seconde
        setTimeout(() => {
            input.style.backgroundColor = '';
            input.style.borderColor = '';
        }, 1000);
    });
    
    // Scroll doux vers les méthodes de paiement
    const paymentSection = document.getElementById('add-funds');
    if (paymentSection) {
        paymentSection.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }
    
    // Feedback visuel sur la carte bonus cliquée
    event.currentTarget.style.transform = 'scale(0.95)';
    setTimeout(() => {
        event.currentTarget.style.transform = '';
    }, 200);
}

// Ajouter effet hover sur les cartes bonus
document.addEventListener('DOMContentLoaded', function() {
    const bonusTierCards = document.querySelectorAll('.bonus-tier-card');
    
    bonusTierCards.forEach(card => {
        // Afficher un indicateur au survol
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 6px 16px rgba(251, 191, 36, 0.4)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
});
</script>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->

<?php require_once __DIR__ . '/../includes/dashboard-footer-simple.php'; ?>
