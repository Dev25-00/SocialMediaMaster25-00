<?php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/config/icons-config.php';

// Vérifier si connecté
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);

// GESTION LANGUE FALLBACK
$selectedLang = $_GET['lang'] ?? 'fr';
$validLangs = ['fr', 'en', 'es', 'de', 'it', 'pt', 'ar', 'zh-CN', 'ja', 'ko', 'hi'];
if (!in_array($selectedLang, $validLangs)) {
    $selectedLang = 'fr';
}

// Filtres
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Construire la requête
$where = ["o.user_id = ?"];
$params = [$user['id']];

if ($status) {
    $where[] = "o.status = ?";
    $params[] = $status;
}

if ($search) {
    $where[] = "(o.order_number LIKE ? OR s.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where_clause = implode(' AND ', $where);

// Pagination
$page = $_GET['page'] ?? 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Compter le total
$count_stmt = $pdo->prepare("
    SELECT COUNT(*) FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE $where_clause
");
$count_stmt->execute($params);
$total = $count_stmt->fetchColumn();
$total_pages = ceil($total / $per_page);

// Récupérer les commandes
$query = "
    SELECT o.*, s.name as service_name, s.platform
    FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE $where_clause
    ORDER BY o.created_at DESC
    LIMIT $per_page OFFSET $offset
";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

// Configuration page
$page_title = "Historique Commandes";
$page_title_bar = "Mes Commandes";

// Inclure header simple
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
?>

<!-- Container sans padding top (collé au top-bar) -->
<div class="container-fluid" style="padding: 0;">
    
    <!-- Content wrapper avec padding latéral seulement -->
    <div style="padding: 24px;">

    <?php 
    // Header configuration
    $page_header_title = "Mes Commandes";
    $page_header_icon = "orders";
    $page_header_description = getIcon('info', false, 'sm') . " Consultez l'historique complet de vos commandes";
    $page_header_gradient = false;
    require_once __DIR__ . '/../includes/layout/page-header.php';
    ?>

    <?php echo renderFlashMessage(); ?>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" id="filterForm">
                <div class="filter-group">
                    <label><?php echo getIcon('search'); ?> Rechercher</label>
                    <input type="text" name="search" placeholder="N° commande ou service..." value="<?php echo clean($search); ?>">
                </div>
                
                <div class="filter-group">
                    <label><?php echo getIcon('stats'); ?> Statut</label>
                    <select name="status" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Tous</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>En attente</option>
                        <option value="processing" <?php echo $status === 'processing' ? 'selected' : ''; ?>>En cours</option>
                        <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Terminé</option>
                        <option value="partial" <?php echo $status === 'partial' ? 'selected' : ''; ?>>Partiel</option>
                        <option value="canceled" <?php echo $status === 'canceled' ? 'selected' : ''; ?>>Annulé</option>
                        <option value="refunded" <?php echo $status === 'refunded' ? 'selected' : ''; ?>>Remboursé</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="history.php" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h2><?php echo $total; ?> commande<?php echo $total > 1 ? 's' : ''; ?> trouvée<?php echo $total > 1 ? 's' : ''; ?></h2>
                <a href="new.php" class="btn btn-primary">+ Nouvelle commande</a>
            </div>
            
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('orders'); ?></div>
                    <h3>Aucune commande</h3>
                    <p>Vous n'avez pas encore passé de commande</p>
                    <a href="new.php" class="btn btn-primary">Passer ma première commande</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Service</th>
                                <th>Lien</th>
                                <th>Quantité</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $order['order_number']; ?></strong>
                                </td>
                                <td>
                                    <div class="service-info">
                                        <strong><?php echo clean($order['service_name']); ?></strong>
                                        <small><?php echo clean($order['platform']); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo clean($order['link']); ?>" target="_blank" style="color: #2563eb; font-size: 14px;">
                                        <?php echo substr($order['link'], 0, 30); ?>...
                                    </a>
                                </td>
                                <td><?php echo number_format($order['quantity']); ?></td>
                                <td><strong><?php echo formatCurrency($order['sell_amount']); ?></strong></td>
                                <td><?php echo getStatusBadge($order['status']); ?></td>
                                <td>
                                    <?php echo formatDate($order['created_at'], 'd/m/Y'); ?>
                                    <small style="display: block; color: #6b7280; font-size: 12px;">
                                        <?php echo formatDate($order['created_at'], 'H:i'); ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="tracking.php?id=<?php echo $order['id']; ?>" class="btn btn-sm">
                                        <?php echo getIcon('view'); ?> Voir
                                    </a>
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
                            <a href="?page=<?php echo $page-1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>" class="btn btn-sm">← Précédent</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                            <a href="?page=<?php echo $i; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>" 
                               class="btn btn-sm <?php echo $i === (int)$page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page+1; ?>&status=<?php echo $status; ?>&search=<?php echo $search; ?>" class="btn btn-sm">Suivant →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Summary -->
                <div style="padding: 20px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #6b7280;">
                            Affichage de <?php echo min(($page-1)*$per_page + 1, $total); ?> à <?php echo min($page*$per_page, $total); ?> sur <?php echo $total; ?> commandes
                        </span>
                        <a href="?export=csv" class="btn btn-sm btn-secondary">
                            📥 Exporter CSV
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div> <!-- Fin padding wrapper -->

</div> <!-- Fin container-fluid -->
</div>

<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>
