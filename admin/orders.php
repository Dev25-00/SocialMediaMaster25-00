<?php
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Annuler une commande
    if (isset($_POST['action']) && $_POST['action'] === 'cancel_order') {
        $order_id = (int)$_POST['order_id'];
        
        // Récupérer la commande
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch();
        
        if ($order && $order['status'] !== 'completed' && $order['status'] !== 'canceled') {
            // Mettre à jour le statut
            $stmt = $pdo->prepare("UPDATE orders SET status = 'canceled' WHERE id = ?");
            $stmt->execute([$order_id]);
            
            // Rembourser le client
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$order['sell_amount'], $order['user_id']]);
            
            // Enregistrer la transaction
            $stmt = $pdo->prepare("
                INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, description)
                SELECT ?, 'refund', ?, balance, balance + ?, CONCAT('Remboursement commande #', ?)
                FROM users WHERE id = ?
            ");
            $stmt->execute([
                $order['user_id'],
                $order['sell_amount'],
                $order['sell_amount'],
                $order['order_number'],
                $order['user_id']
            ]);
            
            setFlashMessage("Commande #{$order['order_number']} annulée et remboursée !", 'success');
        }
        redirect('orders.php');
    }
    
    // Marquer comme complétée
    if (isset($_POST['action']) && $_POST['action'] === 'complete_order') {
        $order_id = (int)$_POST['order_id'];
        
        $stmt = $pdo->prepare("
            UPDATE orders 
            SET status = 'completed', completed_at = NOW() 
            WHERE id = ?
        ");
        
        if ($stmt->execute([$order_id])) {
            setFlashMessage('Commande marquée comme complétée !', 'success');
        }
        redirect('orders.php');
    }
}

// Filtres et recherche
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? clean($_GET['status']) : '';
$platform_filter = isset($_GET['platform']) ? clean($_GET['platform']) : '';
$date_from = isset($_GET['date_from']) ? clean($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? clean($_GET['date_to']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construire la requête
$where = ['1=1'];
$params = [];

if ($search) {
    $where[] = "(o.order_number LIKE ? OR u.username LIKE ? OR s.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status_filter) {
    $where[] = "o.status = ?";
    $params[] = $status_filter;
}

if ($platform_filter) {
    $where[] = "s.platform = ?";
    $params[] = $platform_filter;
}

if ($date_from) {
    $where[] = "DATE(o.created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $where[] = "DATE(o.created_at) <= ?";
    $params[] = $date_to;
}

$where_clause = implode(' AND ', $where);

// Compter le total
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE $where_clause
");
$stmt->execute($params);
$total_orders = $stmt->fetchColumn();
$total_pages = ceil($total_orders / $per_page);

// Récupérer les commandes
$stmt = $pdo->prepare("
    SELECT o.*, u.username, u.email, s.name as service_name, s.platform
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    WHERE $where_clause
    ORDER BY o.created_at DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$orders = $stmt->fetchAll();

// Stats globales
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$stats['pending'] = $pdo->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending', 'processing')")->fetchColumn();
$stats['completed'] = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();
$stats['canceled'] = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'canceled'")->fetchColumn();
$stats['revenue'] = $pdo->query("SELECT COALESCE(SUM(sell_amount), 0) FROM orders WHERE status = 'completed'")->fetchColumn();
$stats['profit'] = $pdo->query("SELECT COALESCE(SUM(profit), 0) FROM orders WHERE status = 'completed'")->fetchColumn();

// Plateformes disponibles
$platforms = $pdo->query("SELECT DISTINCT platform FROM services WHERE is_active = 1 ORDER BY platform")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="../assets/css/global/fixes.css">
    <style>
        .filters-bar {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: 2fr repeat(4, 1fr) auto;
            gap: 15px;
            align-items: end;
        }
        
        .action-btn {
            padding: 6px 12px;
            font-size: 13px;
            margin: 2px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .close {
            color: #9ca3af;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close:hover {
            color: #ef4444;
        }
        
        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
        }
        
        .info-value {
            color: #111827;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: #f3f4f6;
            border-color: #2563eb;
            color: #2563eb;
        }
        
        .pagination .active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                transform: translateY(50px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
<head>
    ...existing code...
    <link rel="stylesheet" href="/smm/assets/css/admin/admin-dashboard.css">
</head>
    <!-- Sidebar -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1><?php echo getIcon('orders'); ?> Gestion des Commandes</h1>
            </div>
        </div>

        <?php echo renderFlashMessage(); ?>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <?php echo getIcon('orders'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Commandes</div>
                    <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
                    <small style="color: #6b7280;">Toutes les commandes</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    ⏳
                </div>
                <div class="stat-info">
                    <div class="stat-label">En cours</div>
                    <div class="stat-value"><?php echo number_format($stats['pending']); ?></div>
                    <small style="color: #f59e0b;">À traiter</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <?php echo getIcon('success'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Complétées</div>
                    <div class="stat-value"><?php echo number_format($stats['completed']); ?></div>
                    <small style="color: #10b981;">Succès</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php echo getIcon('wallet'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Profit Total</div>
                    <div class="stat-value"><?php echo formatCurrency($stats['profit']); ?></div>
                    <small style="color: #10b981;">Revenus: <?php echo formatCurrency($stats['revenue']); ?></small>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <form method="GET" action="">
                <div class="filters-grid">
                    <div class="form-group" style="margin: 0;">
                        <input type="text" 
                               name="search" 
                               placeholder="<?php echo getIcon('search'); ?> Rechercher par N°, utilisateur, service..." 
                               value="<?php echo clean($search); ?>">
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <select name="status">
                            <option value="">Tous les statuts</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>En attente</option>
                            <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>En cours</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Complété</option>
                            <option value="partial" <?php echo $status_filter === 'partial' ? 'selected' : ''; ?>>Partiel</option>
                            <option value="canceled" <?php echo $status_filter === 'canceled' ? 'selected' : ''; ?>>Annulé</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <select name="platform">
                            <option value="">Toutes les plateformes</option>
                            <?php foreach ($platforms as $platform): ?>
                                <option value="<?php echo $platform; ?>" <?php echo $platform_filter === $platform ? 'selected' : ''; ?>>
                                    <?php echo clean($platform); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <input type="date" name="date_from" value="<?php echo $date_from; ?>" placeholder="Date de">
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <input type="date" name="date_to" value="<?php echo $date_to; ?>" placeholder="Date à">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h2>Liste des commandes (<?php echo number_format($total_orders); ?>)</h2>
                <a href="?export=csv<?php echo $search ? '&search='.urlencode($search) : ''; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>" 
                   class="btn btn-sm btn-secondary">
                    📥 Exporter CSV
                </a>
            </div>
            
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('orders'); ?></div>
                    <h3>Aucune commande trouvée</h3>
                    <p>Aucune commande ne correspond aux critères de recherche.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Utilisateur</th>
                                <th>Service</th>
                                <th>Plateforme</th>
                                <th>Quantité</th>
                                <th>Montant</th>
                                <th>Profit</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?php echo $order['order_number']; ?></strong></td>
                                <td>
                                    <div>
                                        <?php echo clean($order['username']); ?>
                                        <br>
                                        <small style="color: #6b7280;"><?php echo clean($order['email']); ?></small>
                                    </div>
                                </td>
                                <td><?php echo clean($order['service_name']); ?></td>
                                <td>
                                    <span class="badge badge-secondary"><?php echo clean($order['platform']); ?></span>
                                </td>
                                <td><?php echo number_format($order['quantity']); ?></td>
                                <td><?php echo formatCurrency($order['sell_amount']); ?></td>
                                <td><strong style="color: #10b981;"><?php echo formatCurrency($order['profit']); ?></strong></td>
                                <td><?php echo getStatusBadge($order['status']); ?></td>
                                <td><?php echo timeAgo($order['created_at']); ?></td>
                                <td>
                                    <button onclick="viewOrder(<?php echo htmlspecialchars(json_encode($order)); ?>)" 
                                            class="action-btn" 
                                            style="background: #2563eb;">
                                        <?php echo getIcon('view'); ?> Voir
                                    </button>
                                    
                                    <?php if ($order['status'] !== 'completed' && $order['status'] !== 'canceled'): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Marquer comme complétée ?');">
                                            <input type="hidden" name="action" value="complete_order">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="action-btn" style="background: #10b981;">
                                                <?php echo getIcon('success'); ?>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Annuler et rembourser cette commande ?');">
                                            <input type="hidden" name="action" value="cancel_order">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="action-btn" style="background: #ef4444;">
                                                <?php echo getIcon('error'); ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&platform=<?php echo $platform_filter; ?>">
                            ← Précédent
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&platform=<?php echo $platform_filter; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&platform=<?php echo $platform_filter; ?>">
                            Suivant →
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal Voir Commande -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo getIcon('orders'); ?> Détails de la commande</h2>
                <span class="close" onclick="closeModal('viewModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div id="orderDetails"></div>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script>
        function viewOrder(order) {
            const html = `
                <div class="info-row">
                    <div class="info-label">N° Commande</div>
                    <div class="info-value"><strong>${order.order_number}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Utilisateur</div>
                    <div class="info-value">${order.username} (${order.email})</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Service</div>
                    <div class="info-value">${order.service_name}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Plateforme</div>
                    <div class="info-value">${order.platform}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Lien cible</div>
                    <div class="info-value">
                        <a href="${order.link}" target="_blank" style="color: #2563eb; word-break: break-all;">
                            ${order.link}
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Quantité</div>
                    <div class="info-value">${order.quantity.toLocaleString()}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Prix d'achat</div>
                    <div class="info-value">$${parseFloat(order.cost_amount).toFixed(2)}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Prix de vente</div>
                    <div class="info-value"><strong>$${parseFloat(order.sell_amount).toFixed(2)}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Profit</div>
                    <div class="info-value"><strong style="color: #10b981;">$${parseFloat(order.profit).toFixed(2)}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Statut</div>
                    <div class="info-value">${getStatusBadgeHTML(order.status)}</div>
                </div>
                ${order.provider_order_id ? `
                <div class="info-row">
                    <div class="info-label">ID Provider</div>
                    <div class="info-value">${order.provider_order_id}</div>
                </div>
                ` : ''}
                ${order.start_count ? `
                <div class="info-row">
                    <div class="info-label">Start Count</div>
                    <div class="info-value">${order.start_count}</div>
                </div>
                ` : ''}
                ${order.remains !== null ? `
                <div class="info-row">
                    <div class="info-label">Restant</div>
                    <div class="info-value">${order.remains}</div>
                </div>
                ` : ''}
                <div class="info-row">
                    <div class="info-label">Date de création</div>
                    <div class="info-value">${new Date(order.created_at).toLocaleString('fr-FR')}</div>
                </div>
                ${order.completed_at ? `
                <div class="info-row">
                    <div class="info-label">Date de complétion</div>
                    <div class="info-value">${new Date(order.completed_at).toLocaleString('fr-FR')}</div>
                </div>
                ` : ''}
                ${order.notes ? `
                <div class="info-row">
                    <div class="info-label">Notes</div>
                    <div class="info-value">${order.notes}</div>
                </div>
                ` : ''}
            `;
            
            document.getElementById('orderDetails').innerHTML = html;
            document.getElementById('viewModal').style.display = 'block';
        }
        
        function getStatusBadgeHTML(status) {
            const badges = {
                'pending': '<span class="badge badge-warning">En attente</span>',
                'processing': '<span class="badge badge-info">En cours</span>',
                'completed': '<span class="badge badge-success">Complété</span>',
                'partial': '<span class="badge badge-warning">Partiel</span>',
                'canceled': '<span class="badge badge-danger">Annulé</span>',
                'refunded': '<span class="badge badge-danger">Remboursé</span>'
            };
            return badges[status] || status;
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Fermer modal en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
