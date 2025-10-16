<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/config/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);
$order_id = $_GET['id'] ?? 0;

// Récupérer la commande
$stmt = $pdo->prepare("
    SELECT o.*, s.name as service_name, s.platform, s.refill_days, s.drop_rate
    FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    setFlashMessage('error', 'Commande introuvable');
    redirect('history.php');
}

// Calculer la progression
$progress = 0;
if ($order['status'] === 'completed') {
    $progress = 100;
} elseif ($order['status'] === 'processing') {
    if ($order['start_count'] && $order['remains'] !== null) {
        $delivered = $order['quantity'] - $order['remains'];
        $progress = min(100, ($delivered / $order['quantity']) * 100);
    } else {
        $progress = 50;
    }
} elseif ($order['status'] === 'pending') {
    $progress = 10;
}

// Configuration page
$page_title = "Suivi Commande #" . $order['order_number'];
$page_title_bar = "Suivi de Commande";

// Inclure header simple
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
?>

<style>
    .progress-bar-container {
        background: #e5e7eb;
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            height: 100%;
            transition: width 0.3s ease;
        }
        .timeline {
            position: relative;
            padding-left: 40px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -28px;
            top: 6px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e5e7eb;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }
        .timeline-item.active::before {
            background: #10b981;
            box-shadow: 0 0 0 2px #10b981;
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -23px;
            top: 18px;
            width: 2px;
            height: calc(100% - 18px);
            background: #e5e7eb;
        }
        .timeline-item:last-child::after {
            display: none;
        }
</style>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
    
    <div style="max-width: 900px; margin: 0 auto;">
        
        <!-- Action Bar -->
        <div style="margin-bottom: 20px;">
            <a href="history.php" class="btn btn-secondary">← Retour à l'historique</a>
        </div>
            
            <!-- Order Header -->
            <div class="card">
                <div style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 24px; margin-bottom: 10px;">
                                Commande #<?php echo $order['order_number']; ?>
                            </h2>
                            <p style="color: #6b7280;">
                                Passée le <?php echo formatDate($order['created_at'], 'd/m/Y à H:i'); ?>
                            </p>
                        </div>
                        <?php echo getStatusBadge($order['status']); ?>
                    </div>
                    
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                    
                    <p style="text-align: center; color: #6b7280; font-size: 14px;">
                        Progression : <strong><?php echo round($progress); ?>%</strong>
                    </p>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card">
                <div class="card-header">
                    <h2>Détails de la commande</h2>
                </div>
                <div style="padding: 30px;">
                    <table style="width: 100%;">
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Service</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong><?php echo clean($order['service_name']); ?></strong>
                                <br><small><?php echo clean($order['platform']); ?></small>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Lien cible</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <a href="<?php echo clean($order['link']); ?>" target="_blank" style="color: #2563eb; word-break: break-all;">
                                    <?php echo clean($order['link']); ?>
                                </a>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Quantité commandée</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong><?php echo number_format($order['quantity']); ?></strong>
                            </td>
                        </tr>
                        <?php if ($order['start_count']): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Départ</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong><?php echo number_format($order['start_count']); ?></strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($order['remains'] !== null && in_array($order['status'], ['processing', 'partial', 'completed'])): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Restant</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong><?php echo number_format($order['remains']); ?></strong>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Livré</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong style="color: #10b981;">
                                    <?php echo number_format($order['quantity'] - $order['remains']); ?>
                                </strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 15px 0; color: #6b7280;">Montant payé</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong style="font-size: 20px; color: #2563eb;">
                                    <?php echo formatCurrency($order['sell_amount']); ?>
                                </strong>
                            </td>
                        </tr>
                        <?php if ($order['refill_days']): ?>
                        <tr>
                            <td style="padding: 15px 0; color: #6b7280;">Garantie Refill</td>
                            <td style="padding: 15px 0; text-align: right;">
                                <strong style="color: #10b981;">
                                    <?php echo getIcon('shares'); ?> <?php echo $order['refill_days']; ?> jours
                                </strong>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h2>Historique</h2>
                </div>
                <div style="padding: 30px;">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <strong>Commande créée</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                <?php echo formatDate($order['created_at']); ?>
                            </p>
                        </div>
                        
                        <?php if ($order['status'] !== 'pending'): ?>
                        <div class="timeline-item active">
                            <strong>En traitement</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                Commande envoyée au fournisseur
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($order['status'] === 'completed'): ?>
                        <div class="timeline-item active">
                            <strong><?php echo getIcon('success'); ?> Terminée</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                <?php echo $order['completed_at'] ? formatDate($order['completed_at']) : 'Récemment'; ?>
                            </p>
                        </div>
                        <?php elseif ($order['status'] === 'canceled'): ?>
                        <div class="timeline-item active">
                            <strong><?php echo getIcon('error'); ?> Annulée</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                Commande annulée
                            </p>
                        </div>
                        <?php elseif ($order['status'] === 'refunded'): ?>
                        <div class="timeline-item active">
                            <strong><?php echo getIcon('wallet'); ?> Remboursée</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                Montant remboursé sur votre solde
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="timeline-item">
                            <strong>En attente...</strong>
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                Livraison en cours
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <?php if ($order['status'] === 'completed' && $order['refill_days']): ?>
            <div class="card">
                <div style="padding: 30px; text-align: center;">
                    <h3 style="margin-bottom: 15px;">Besoin d'un refill ?</h3>
                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Si vous constatez une baisse, vous pouvez demander un refill gratuit
                    </p>
                    <a href="refill.php?order=<?php echo $order['id']; ?>" class="btn btn-primary">
                        <?php echo getIcon('shares'); ?> Demander un refill
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Support -->
            <div class="card">
                <div style="padding: 30px; text-align: center; background: #f9fafb;">
                    <p style="color: #6b7280; margin-bottom: 15px;">
                        Un problème avec cette commande ?
                    </p>
                    <a href="../support/new-ticket.php?order=<?php echo $order['id']; ?>" class="btn btn-secondary">
                        <?php echo getIcon('support'); ?> Contacter le support
                    </a>
                </div>
        </div>

    </div> <!-- Fin max-width wrapper -->
    
    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<script>
    // Auto-refresh toutes les 30 secondes si la commande est en cours
    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
    setTimeout(function() {
        location.reload();
    }, 30000);
    <?php endif; ?>
</script>

<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>