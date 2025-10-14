<?php
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);

// Stats globales
$stats = [];

// Nombre d'utilisateurs
$stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['active_users'] = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();

// Nombre de commandes
$stats['total_orders'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$stats['pending_orders'] = $pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending', 'processing')")->fetchColumn();
$stats['completed_orders'] = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();

// Revenus
$stats['total_revenue'] = $pdo->query("SELECT COALESCE(SUM(sell_amount), 0) FROM orders")->fetchColumn();
$stats['total_profit'] = $pdo->query("SELECT COALESCE(SUM(profit), 0) FROM orders")->fetchColumn();

// Services
$stats['total_services'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$stats['active_services'] = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();

// Tickets
$stats['open_tickets'] = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'open'")->fetchColumn();

// Dernières commandes
$stmt = $pdo->query("
    SELECT o.*, u.username, s.name as service_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    ORDER BY o.created_at DESC
    LIMIT 10
");
$recent_orders = $stmt->fetchAll();

// Dernière sync
$last_sync = getSetting($pdo, 'last_sync', 'Jamais');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/fixes.css">
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>Panneau d'Administration 🔧</h1>
            </div>
        </div>

        <?php echo renderFlashMessage(); ?>

        <!-- Quick Actions -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
            <a href="api-test.php" class="btn btn-primary" style="padding: 15px; text-align: center;">
                🧪 Tester l'API
            </a>
            <a href="sync-services.php" class="btn btn-success" style="padding: 15px; text-align: center;">
                <?php echo getIcon('shares'); ?> Synchroniser Services
            </a>
            <a href="settings.php" class="btn btn-secondary" style="padding: 15px; text-align: center;">
                <?php echo getIcon('settings'); ?> Paramètres
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <?php echo getIcon('followers'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Utilisateurs</div>
                    <div class="stat-value"><?php echo $stats['total_users']; ?></div>
                    <small style="color: #10b981;"><?php echo $stats['active_users']; ?> actifs</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <?php echo getIcon('orders'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Commandes</div>
                    <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                    <small style="color: #f59e0b;"><?php echo $stats['pending_orders']; ?> en cours</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php echo getIcon('wallet'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Revenus totaux</div>
                    <div class="stat-value"><?php echo formatCurrency($stats['total_revenue']); ?></div>
                    <small style="color: #10b981;">Profit: <?php echo formatCurrency($stats['total_profit']); ?></small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <?php echo getIcon('services'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Services</div>
                    <div class="stat-value"><?php echo $stats['active_services']; ?></div>
                    <small style="color: #6b7280;">sur <?php echo $stats['total_services']; ?> total</small>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h2><?php echo getIcon('stats'); ?> Informations Système</h2>
            </div>
            <div style="padding: 30px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <strong style="color: #6b7280; font-size: 14px;">Dernière synchronisation</strong>
                        <p style="margin-top: 5px; font-size: 16px;"><?php echo $last_sync; ?></p>
                    </div>
                    <div>
                        <strong style="color: #6b7280; font-size: 14px;">Tickets ouverts</strong>
                        <p style="margin-top: 5px; font-size: 16px;">
                            <?php echo $stats['open_tickets']; ?> 
                            <?php if ($stats['open_tickets'] > 0): ?>
                                <span class="badge badge-warning">À traiter</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <strong style="color: #6b7280; font-size: 14px;">Version PHP</strong>
                        <p style="margin-top: 5px; font-size: 16px;"><?php echo PHP_VERSION; ?></p>
                    </div>
                    <div>
                        <strong style="color: #6b7280; font-size: 14px;">Base de données</strong>
                        <p style="margin-top: 5px; font-size: 16px;"><?php echo DB_NAME; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h2>Dernières commandes</h2>
                <a href="orders.php" class="btn btn-sm">Voir tout →</a>
            </div>
            
            <?php if (empty($recent_orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('orders'); ?></div>
                    <h3>Aucune commande</h3>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Utilisateur</th>
                                <th>Service</th>
                                <th>Montant</th>
                                <th>Profit</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><strong><?php echo $order['order_number']; ?></strong></td>
                                <td><?php echo clean($order['username']); ?></td>
                                <td><?php echo clean($order['service_name']); ?></td>
                                <td><?php echo formatCurrency($order['sell_amount']); ?></td>
                                <td><strong style="color: #10b981;"><?php echo formatCurrency($order['profit']); ?></strong></td>
                                <td><?php echo getStatusBadge($order['status']); ?></td>
                                <td><?php echo timeAgo($order['created_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>
