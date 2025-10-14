<?php
/**
 * DASHBOARD PRINCIPAL - Version 2.0
 * Interface modernisée avec icônes professionnelles
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../includes/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);
$stats = getUserStats($pdo, $user['id']);

// Obtenir les dernières commandes
$stmt = $pdo->prepare("
    SELECT o.*, s.name as service_name, s.platform, s.tier
    FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
    LIMIT 5
");
$stmt->execute([$user['id']]);
$recent_orders = $stmt->fetchAll();

// Statistiques pour les graphiques (30 derniers jours)
$stmt = $pdo->prepare("
    SELECT DATE(created_at) as date, COUNT(*) as count, SUM(sell_amount) as total
    FROM orders
    WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$stmt->execute([$user['id']]);
$chart_data = $stmt->fetchAll();

// Configuration page
$page_title = "Dashboard";
$page_title_bar = "Dashboard";
$include_charts = !empty($chart_data);

// Inclure header simple
require_once __DIR__ . '/../includes/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">
    
    <?php 
    // Header configuration avec nom d'utilisateur
    $page_header_title = "Bienvenue, " . $user['username'];
    $page_header_icon = "rocket";
    $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    $months = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $page_header_description = getIcon('calendar', false, 'sm') . " " . $days[date('w')] . ' ' . date('d') . ' ' . $months[date('n')] . ' ' . date('Y');
    $page_header_gradient = true;
    require_once __DIR__ . '/../includes/page-header.php';
    ?>

        <!-- Flash Messages -->
        <?php echo renderFlashMessage(); ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <?php echo getIcon('wallet', true, 'xl'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Solde actuel</div>
                    <div class="stat-value"><?php echo formatCurrency($user['balance']); ?></div>
                    <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="stat-action">
                        <?php echo getIcon('add', false, 'sm'); ?>
                        Ajouter des fonds
                    </a>
                </div>
            </div>
            
            <div class="stat-card stat-card-pink">
                <div class="stat-icon">
                    <?php echo getIcon('chart', false, 'xl'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total dépensé</div>
                    <div class="stat-value"><?php echo formatCurrency($stats['total_spent']); ?></div>
                    <div class="stat-detail">Ce mois: <?php echo formatCurrency($stats['month_spent'] ?? 0); ?></div>
                </div>
            </div>
            
            <div class="stat-card stat-card-blue">
                <div class="stat-icon">
                    <?php echo getIcon('orders', false, 'xl'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Commandes totales</div>
                    <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
                    <div class="stat-detail">En cours: <?php echo $stats['pending_orders'] ?? 0; ?></div>
                </div>
            </div>
            
            <div class="stat-card stat-card-green">
                <div class="stat-icon">
                    <?php echo getIcon('success', false, 'xl'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Complétées</div>
                    <div class="stat-value"><?php echo number_format($stats['completed_orders']); ?></div>
                    <div class="stat-detail">
                        Taux: <?php echo $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100) : 0; ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-header">
            <h2>
                <?php echo getIcon('rocket', true, 'lg'); ?>
                Actions rapides
            </h2>
        </div>
        
        <div class="quick-actions-grid">
            <a href="<?php echo SITE_URL; ?>/orders/new.php" class="action-card action-primary">
                <div class="action-icon">
                    <?php echo getIcon('add', true, 'xl'); ?>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Nouvelle commande</h3>
                    <p class="action-desc">Passer une commande rapidement</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/services/index.php" class="action-card action-secondary">
                <div class="action-icon">
                    <?php echo getIcon('services', true, 'xl'); ?>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Parcourir les services</h3>
                    <p class="action-desc">Découvrir tous nos services SMM</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="action-card action-success">
                <div class="action-icon">
                    <?php echo getIcon('money', true, 'xl'); ?>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Ajouter des fonds</h3>
                    <p class="action-desc">Recharger votre solde</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            
            <a href="<?php echo SITE_URL; ?>/support/new-ticket.php" class="action-card action-info">
                <div class="action-icon">
                    <?php echo getIcon('support', true, 'xl'); ?>
                </div>
                <div class="action-content">
                    <h3 class="action-title">Contacter le support</h3>
                    <p class="action-desc">Obtenir de l'aide</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        </div>

        <!-- Recent Orders -->
        <div class="section-header">
            <h2>
                <?php echo getIcon('orders', false, 'lg'); ?>
                Dernières commandes
            </h2>
            <a href="<?php echo SITE_URL; ?>/orders/history.php" class="btn btn-secondary">
                <?php echo getIcon('view', false, 'sm'); ?>
                Voir tout
            </a>
        </div>
        
        <div class="card">
            <?php if (empty($recent_orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <?php echo getIcon('orders', true, 'xl'); ?>
                    </div>
                    <h3>Aucune commande</h3>
                    <p>Vous n'avez pas encore passé de commande</p>
                    <a href="<?php echo SITE_URL; ?>/orders/new.php" class="btn btn-primary btn-lg">
                        <?php echo getIcon('add', false, 'sm'); ?>
                        Passer ma première commande
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo getIcon('orders', false, 'sm'); ?> N° Commande</th>
                                <th><?php echo getIcon('services', false, 'sm'); ?> Service</th>
                                <th><?php echo getIcon('followers', false, 'sm'); ?> Quantité</th>
                                <th><?php echo getIcon('money', false, 'sm'); ?> Montant</th>
                                <th><?php echo getIcon('info', false, 'sm'); ?> Statut</th>
                                <th><?php echo getIcon('calendar', false, 'sm'); ?> Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><strong><?php echo $order['order_number']; ?></strong></td>
                                <td>
                                    <div class="service-cell">
                                        <?php echo platformIcon($order['platform'], false); ?>
                                        <div>
                                            <div class="service-name"><?php echo clean($order['service_name']); ?></div>
                                            <div class="service-meta">
                                                <?php echo tierBadge($order['tier']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo number_format($order['quantity']); ?></td>
                                <td><strong><?php echo formatCurrency($order['sell_amount']); ?></strong></td>
                                <td><?php echo statusBadge($order['status']); ?></td>
                                <td>
                                    <div class="date-cell">
                                        <?php echo date('d/m/Y', strtotime($order['created_at'])); ?>
                                        <small><?php echo timeAgo($order['created_at']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/orders/tracking.php?id=<?php echo $order['id']; ?>" 
                                       class="btn btn-sm btn-secondary">
                                        <?php echo getIcon('view', false, 'sm'); ?>
                                        Voir
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Chart Section -->
        <?php if (!empty($chart_data)): ?>
        <div class="section-header">
            <h2>
                <?php echo getIcon('chart', false, 'lg'); ?>
                Activité des 30 derniers jours
            </h2>
        </div>
        
        <div class="card">
            <div class="card-body">
                <canvas id="ordersChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tips Section -->
        <div class="tips-section">
            <div class="tip-card tip-primary">
                <div class="tip-icon">
                    <?php echo getIcon('info', true, 'xl'); ?>
                </div>
                <div class="tip-content">
                    <h4>Besoin d'aide ?</h4>
                    <p>Notre équipe support est disponible 24/7 pour répondre à vos questions.</p>
                    <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="tip-link">
                        Contacter le support <?php echo getIcon('arrow', false, 'sm'); ?>
                    </a>
                </div>
            </div>
            
            <div class="tip-card tip-success">
                <div class="tip-icon">
                    <?php echo getIcon('premium', true, 'xl'); ?>
                </div>
                <div class="tip-content">
                    <h4>Services Premium</h4>
                    <p>Découvrez nos services premium avec garantie No Drop et refill à vie.</p>
                    <a href="<?php echo SITE_URL; ?>/services/index.php?tier=premium" class="tip-link">
                        Explorer <?php echo getIcon('arrow', false, 'sm'); ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if (!empty($chart_data)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ordersChart');
    const chartData = <?php echo json_encode($chart_data); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
            }),
            datasets: [{
                label: 'Commandes',
                data: chartData.map(d => d.count),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' commande(s)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            family: 'Inter'
                        }
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Inter'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<style>
/* Styles additionnels pour dashboard */
.gradient-text {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-header {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
    width: 100% !important;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
}

.stat-card-primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card-pink .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card-blue .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card-green .stat-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.stat-detail, .stat-action {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

.stat-action {
    color: #2563eb;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}

.stat-action:hover {
    text-decoration: underline;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 40px;
    width: 100% !important;
}

@media (max-width: 968px) {
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2563eb, #7c3aed);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.action-card:hover::before {
    transform: scaleX(1);
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-primary .action-icon {
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: white;
}

.action-secondary .action-icon {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
}

.action-success .action-icon {
    background: linear-gradient(135deg, #10b981, #3b82f6);
    color: white;
}

.action-info .action-icon {
    background: linear-gradient(135deg, #06b6d4, #8b5cf6);
    color: white;
}

.action-content {
    flex: 1;
}

.action-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.action-desc {
    font-size: 13px;
    color: #6b7280;
}

.action-arrow {
    color: #9ca3af;
    font-size: 20px;
    transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
    color: #2563eb;
    transform: translateX(4px);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 10px;
}

.service-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.service-name {
    font-weight: 500;
    color: #111827;
}

.service-meta {
    margin-top: 4px;
}

.date-cell small {
    display: block;
    color: #9ca3af;
    font-size: 12px;
    margin-top: 2px;
}

.tips-section {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-top: 40px;
    width: 100% !important;
}

.tip-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.tip-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tip-primary .tip-icon {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.tip-success .tip-icon {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.tip-content h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.tip-content p {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 12px;
}

.tip-link {
    font-size: 14px;
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.tip-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .tips-section {
        grid-template-columns: 1fr;
    }
}
</style>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->

<?php require_once __DIR__ . '/../includes/dashboard-footer-simple.php'; ?>
