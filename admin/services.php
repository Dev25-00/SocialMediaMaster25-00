<?php
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Mettre à jour un service
    if (isset($_POST['action']) && $_POST['action'] === 'update_service') {
        $service_id = (int)$_POST['service_id'];
        $name = clean($_POST['name']);
        $description = clean($_POST['description']);
        $tier = clean($_POST['tier']);
        $sell_price = (float)$_POST['sell_price'];
        $min_quantity = (int)$_POST['min_quantity'];
        $max_quantity = (int)$_POST['max_quantity'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $stmt = $pdo->prepare("
            UPDATE services 
            SET name = ?, description = ?, tier = ?, sell_price = ?, 
                min_quantity = ?, max_quantity = ?, is_active = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        if ($stmt->execute([$name, $description, $tier, $sell_price, $min_quantity, $max_quantity, $is_active, $service_id])) {
            setFlashMessage('Service mis à jour avec succès !', 'success');
        } else {
            setFlashMessage('Erreur lors de la mise à jour.', 'error');
        }
        redirect('services.php');
    }
    
    // Activer/Désactiver un service
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_service') {
        $service_id = (int)$_POST['service_id'];
        
        $stmt = $pdo->prepare("UPDATE services SET is_active = NOT is_active WHERE id = ?");
        
        if ($stmt->execute([$service_id])) {
            setFlashMessage('Statut du service mis à jour !', 'success');
        }
        redirect('services.php');
    }
    
    // Activer/Désactiver plusieurs services
    if (isset($_POST['action']) && $_POST['action'] === 'bulk_toggle') {
        $service_ids = $_POST['service_ids'] ?? [];
        $new_status = (int)$_POST['new_status'];
        
        if (!empty($service_ids)) {
            $placeholders = str_repeat('?,', count($service_ids) - 1) . '?';
            $stmt = $pdo->prepare("UPDATE services SET is_active = ? WHERE id IN ($placeholders)");
            
            $params = array_merge([$new_status], $service_ids);
            $stmt->execute($params);
            
            $count = count($service_ids);
            $status_text = $new_status ? 'activés' : 'désactivés';
            setFlashMessage("$count service(s) $status_text avec succès !", 'success');
        }
        redirect('services.php');
    }
}

// Filtres et recherche
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$platform_filter = isset($_GET['platform']) ? clean($_GET['platform']) : '';
$tier_filter = isset($_GET['tier']) ? clean($_GET['tier']) : '';
$status_filter = isset($_GET['is_active']) ? clean($_GET['is_active']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 30;
$offset = ($page - 1) * $per_page;

// Construire la requête
$where = ['1=1'];
$params = [];

if ($search) {
    $where[] = "(name LIKE ? OR description LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($platform_filter) {
    $where[] = "platform = ?";
    $params[] = $platform_filter;
}

if ($tier_filter) {
    $where[] = "tier = ?";
    $params[] = $tier_filter;
}

if ($status_filter !== '') {
    $where[] = "is_active = ?";
    $params[] = (int)$status_filter;
}

$where_clause = implode(' AND ', $where);

// Compter le total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM services WHERE $where_clause");
$stmt->execute($params);
$total_services = $stmt->fetchColumn();
$total_pages = ceil($total_services / $per_page);

// Récupérer les services
$stmt = $pdo->prepare("
    SELECT s.*,
           (SELECT COUNT(*) FROM orders WHERE service_id = s.id) as total_orders,
           (SELECT COALESCE(SUM(profit), 0) FROM orders WHERE service_id = s.id AND status = 'completed') as total_profit
    FROM services s
    WHERE $where_clause
    ORDER BY s.is_active DESC, s.platform, s.name
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$services = $stmt->fetchAll();

// Stats globales
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$stats['active'] = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
$stats['inactive'] = $pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 0")->fetchColumn();
$stats['orders_count'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$stats['total_profit'] = $pdo->query("SELECT COALESCE(SUM(profit), 0) FROM orders WHERE status = 'completed'")->fetchColumn();

// Plateformes et catégories
$platforms = $pdo->query("SELECT DISTINCT platform FROM services ORDER BY platform")->fetchAll(PDO::FETCH_COLUMN);
$categories = $pdo->query("SELECT DISTINCT category FROM services ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

// Dernière synchronisation
$last_sync = getSetting($pdo, 'last_sync', 'Jamais');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
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
            grid-template-columns: 2fr repeat(3, 1fr) auto;
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
            max-width: 800px;
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            height: 20px;
            cursor: pointer;
        }
        
        .tier-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .tier-budget { background: #dcfce7; color: #166534; }
        .tier-standard { background: #dbeafe; color: #1e40af; }
        .tier-premium { background: #fce7f3; color: #9f1239; }
        .tier-ultimate { background: #fef3c7; color: #92400e; }
        
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
        
        .bulk-actions {
            display: none;
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            align-items: center;
            gap: 15px;
        }
        
        .bulk-actions.show {
            display: flex;
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
                <h1><?php echo getIcon('services'); ?> Gestion des Services</h1>
            </div>
        </div>

        <?php echo renderFlashMessage(); ?>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <?php echo getIcon('services'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Services</div>
                    <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
                    <small style="color: #10b981;"><?php echo $stats['active']; ?> actifs</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <?php echo getIcon('success'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Services Actifs</div>
                    <div class="stat-value"><?php echo number_format($stats['active']); ?></div>
                    <small style="color: #6b7280;">Disponibles à la vente</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <?php echo getIcon('orders'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Commandes</div>
                    <div class="stat-value"><?php echo number_format($stats['orders_count']); ?></div>
                    <small style="color: #6b7280;">Total vendues</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php echo getIcon('wallet'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Profit Total</div>
                    <div class="stat-value"><?php echo formatCurrency($stats['total_profit']); ?></div>
                    <small style="color: #10b981;">Sur services vendus</small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <a href="sync-services.php" class="btn btn-success" style="padding: 15px; text-align: center;">
                <?php echo getIcon('shares'); ?> Synchroniser maintenant
            </a>
            <button onclick="showBulkActions()" class="btn btn-secondary" style="padding: 15px;">
                ☑️ Actions groupées
            </button>
            <div style="background: white; padding: 15px; border-radius: 8px; text-align: center;">
                <strong style="color: #6b7280; font-size: 14px;">Dernière sync</strong>
                <p style="margin: 5px 0 0 0; font-size: 16px;"><?php echo $last_sync; ?></p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-bar">
            <form method="GET" action="">
                <div class="filters-grid">
                    <div class="form-group" style="margin: 0;">
                        <input type="text" 
                               name="search" 
                               placeholder="<?php echo getIcon('search'); ?> Rechercher par nom, catégorie..." 
                               value="<?php echo clean($search); ?>">
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
                        <select name="tier">
                            <option value="">Tous les tiers</option>
                            <option value="budget" <?php echo $tier_filter === 'budget' ? 'selected' : ''; ?>><?php echo tierBadge('budget'); ?></option>
                            <option value="standard" <?php echo $tier_filter === 'standard' ? 'selected' : ''; ?>><?php echo tierBadge('standard'); ?></option>
                            <option value="premium" <?php echo $tier_filter === 'premium' ? 'selected' : ''; ?>><?php echo tierBadge('premium'); ?></option>
                            <option value="ultimate" <?php echo $tier_filter === 'ultimate' ? 'selected' : ''; ?>><?php echo tierBadge('ultimate'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <select name="is_active">
                            <option value="">Tous les statuts</option>
                            <option value="1" <?php echo $status_filter === '1' ? 'selected' : ''; ?>>Actif</option>
                            <option value="0" <?php echo $status_filter === '0' ? 'selected' : ''; ?>>Inactif</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions" id="bulkActionsBar">
            <strong>Action groupée :</strong>
            <span id="selectedCount">0 sélectionné(s)</span>
            <form method="POST" style="display: inline; margin-left: auto;">
                <input type="hidden" name="action" value="bulk_toggle">
                <input type="hidden" name="new_status" value="1">
                <input type="hidden" name="service_ids" id="bulkActivateIds">
                <button type="submit" class="btn btn-sm btn-success"><?php echo getIcon('success'); ?> Activer</button>
            </form>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="bulk_toggle">
                <input type="hidden" name="new_status" value="0">
                <input type="hidden" name="service_ids" id="bulkDeactivateIds">
                <button type="submit" class="btn btn-sm btn-secondary"><?php echo getIcon('error'); ?> Désactiver</button>
            </form>
        </div>

        <!-- Services Table -->
        <div class="card">
            <div class="card-header">
                <h2>Liste des services (<?php echo number_format($total_services); ?>)</h2>
            </div>
            
            <?php if (empty($services)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('services'); ?></div>
                    <h3>Aucun service trouvé</h3>
                    <p>Aucun service ne correspond aux critères de recherche.</p>
                    <a href="sync-services.php" class="btn btn-primary" style="margin-top: 20px;">
                        <?php echo getIcon('shares'); ?> Synchroniser les services
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th>Service</th>
                                <th>Plateforme</th>
                                <th>Catégorie</th>
                                <th>Tier</th>
                                <th>Prix</th>
                                <th>Min/Max</th>
                                <th>Commandes</th>
                                <th>Profit</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="service-checkbox" value="<?php echo $service['id']; ?>">
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo clean($service['name']); ?></strong>
                                        <?php if ($service['provider_id']): ?>
                                            <br><small style="color: #6b7280;">ID: <?php echo $service['provider_id']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-secondary"><?php echo clean($service['platform']); ?></span></td>
                                <td><?php echo clean($service['category']); ?></td>
                                <td>
                                    <?php
                                    $tier_icons = [
                                        'budget' => getIcon('budget', true),
                                        'standard' => getIcon('standard', true),
                                        'premium' => getIcon('premium', true),
                                        'ultimate' => getIcon('ultimate', true)
                                    ];
                                    $tier_classes = [
                                        'budget' => 'tier-budget',
                                        'standard' => 'tier-standard',
                                        'premium' => 'tier-premium',
                                        'ultimate' => 'tier-ultimate'
                                    ];
                                    echo '<span class="tier-badge ' . $tier_classes[$service['tier']] . '">';
                                    echo $tier_icons[$service['tier']] . ' ' . ucfirst($service['tier']);
                                    echo '</span>';
                                    ?>
                                </td>
                                <td>
                                    <div>
                                        <strong style="color: #10b981;"><?php echo formatCurrency($service['sell_price']); ?></strong> / 1K
                                        <br>
                                        <small style="color: #6b7280;">Coût: <?php echo formatCurrency($service['cost_price']); ?></small>
                                    </div>
                                </td>
                                <td><?php echo number_format($service['min_quantity']); ?> - <?php echo number_format($service['max_quantity']); ?></td>
                                <td><?php echo number_format($service['total_orders']); ?></td>
                                <td><strong style="color: #10b981;"><?php echo formatCurrency($service['total_profit']); ?></strong></td>
                                <td>
                                    <?php if ($service['is_active']): ?>
                                        <span class="badge badge-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button onclick="editService(<?php echo htmlspecialchars(json_encode($service)); ?>)" 
                                            class="action-btn" 
                                            style="background: #2563eb;">
                                        <?php echo getIcon('edit'); ?>
                                    </button>
                                    
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="toggle_service">
                                        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                        <button type="submit" class="action-btn" style="background: <?php echo $service['is_active'] ? '#ef4444' : '#10b981'; ?>;">
                                            <?php echo $service['is_active'] ? getIcon('error') : getIcon('success'); ?>
                                        </button>
                                    </form>
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
                        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&platform=<?php echo $platform_filter; ?>&tier=<?php echo $tier_filter; ?>&is_active=<?php echo $status_filter; ?>">
                            ← Précédent
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&platform=<?php echo $platform_filter; ?>&tier=<?php echo $tier_filter; ?>&is_active=<?php echo $status_filter; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&platform=<?php echo $platform_filter; ?>&tier=<?php echo $tier_filter; ?>&is_active=<?php echo $status_filter; ?>">
                            Suivant →
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal Éditer Service -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo getIcon('edit'); ?> Éditer le service</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_service">
                    <input type="hidden" name="service_id" id="edit_service_id">
                    
                    <div class="form-group">
                        <label>Nom du service</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tier</label>
                            <select name="tier" id="edit_tier" required>
                                <option value="budget"><?php echo tierBadge('budget'); ?></option>
                                <option value="standard"><?php echo tierBadge('standard'); ?></option>
                                <option value="premium"><?php echo tierBadge('premium'); ?></option>
                                <option value="ultimate"><?php echo tierBadge('ultimate'); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Prix de vente ($) / 1K</label>
                            <input type="number" name="sell_price" id="edit_sell_price" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Quantité minimum</label>
                            <input type="number" name="min_quantity" id="edit_min_quantity" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Quantité maximum</label>
                            <input type="number" name="max_quantity" id="edit_max_quantity" min="1" required>
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_active" id="edit_is_active">
                        <label for="edit_is_active" style="margin: 0;">Service actif (visible pour les clients)</label>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                        <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            💾 Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script>
        function editService(service) {
            document.getElementById('edit_service_id').value = service.id;
            document.getElementById('edit_name').value = service.name;
            document.getElementById('edit_description').value = service.description || '';
            document.getElementById('edit_tier').value = service.tier;
            document.getElementById('edit_sell_price').value = parseFloat(service.sell_price);
            document.getElementById('edit_min_quantity').value = service.min_quantity;
            document.getElementById('edit_max_quantity').value = service.max_quantity;
            document.getElementById('edit_is_active').checked = service.is_active == 1;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function showBulkActions() {
            const bulkBar = document.getElementById('bulkActionsBar');
            bulkBar.classList.toggle('show');
        }
        
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.service-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateBulkActions();
        }
        
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.service-checkbox:checked');
            const count = checkboxes.length;
            
            document.getElementById('selectedCount').textContent = count + ' sélectionné(s)';
            
            const ids = Array.from(checkboxes).map(cb => cb.value);
            document.getElementById('bulkActivateIds').value = JSON.stringify(ids);
            document.getElementById('bulkDeactivateIds').value = JSON.stringify(ids);
            
            if (count > 0) {
                document.getElementById('bulkActionsBar').classList.add('show');
            } else {
                document.getElementById('bulkActionsBar').classList.remove('show');
            }
        }
        
        // Écouter les changements sur les checkboxes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('service-checkbox')) {
                updateBulkActions();
            }
        });
        
        // Fermer modal en cliquant à l'extérieur
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
