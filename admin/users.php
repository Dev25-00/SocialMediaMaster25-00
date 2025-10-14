<?php
require_once '../config.php';
require_once '../functions.php';

// Vérifier si admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../auth/login.php');
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Mise à jour utilisateur
    if (isset($_POST['action']) && $_POST['action'] === 'update_user') {
        $user_id = (int)$_POST['user_id'];
        $email = clean($_POST['email']);
        $role = clean($_POST['role']);
        $status = clean($_POST['status']);
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET email = ?, role = ?, status = ?
            WHERE id = ?
        ");
        
        if ($stmt->execute([$email, $role, $status, $user_id])) {
            setFlashMessage('Utilisateur mis à jour avec succès !', 'success');
        } else {
            setFlashMessage('Erreur lors de la mise à jour.', 'error');
        }
        redirect('users.php');
    }
    
    // Ajuster le solde
    if (isset($_POST['action']) && $_POST['action'] === 'adjust_balance') {
        $user_id = (int)$_POST['user_id'];
        $amount = (float)$_POST['amount'];
        $type = $_POST['type']; // add ou subtract
        $description = clean($_POST['description']);
        
        // Récupérer le solde actuel
        $stmt = $pdo->prepare("SELECT balance, username FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch();
        
        if ($user_data) {
            $old_balance = $user_data['balance'];
            $new_balance = $type === 'add' ? ($old_balance + $amount) : ($old_balance - $amount);
            
            if ($new_balance < 0) {
                setFlashMessage('Le solde ne peut pas être négatif !', 'error');
                redirect('users.php');
            }
            
            // Mettre à jour le solde
            $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $stmt->execute([$new_balance, $user_id]);
            
            // Enregistrer la transaction
            $trans_type = $type === 'add' ? 'bonus' : 'adjustment';
            $trans_amount = $type === 'add' ? $amount : -$amount;
            
            $stmt = $pdo->prepare("
                INSERT INTO transactions (user_id, type, amount, balance_before, balance_after, description)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $user_id,
                $trans_type,
                $trans_amount,
                $old_balance,
                $new_balance,
                $description
            ]);
            
            setFlashMessage("Solde de {$user_data['username']} ajusté avec succès !", 'success');
        }
        redirect('users.php');
    }
    
    // Réinitialiser le mot de passe
    if (isset($_POST['action']) && $_POST['action'] === 'reset_password') {
        $user_id = (int)$_POST['user_id'];
        $new_password = $_POST['new_password'];
        
        if (strlen($new_password) < 8) {
            setFlashMessage('Le mot de passe doit contenir au moins 8 caractères.', 'error');
            redirect('users.php');
        }
        
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        
        if ($stmt->execute([$hashed, $user_id])) {
            setFlashMessage('Mot de passe réinitialisé avec succès !', 'success');
        }
        redirect('users.php');
    }
}

// Filtres et recherche
$search = isset($_GET['search']) ? clean($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? clean($_GET['role']) : '';
$status_filter = isset($_GET['status']) ? clean($_GET['status']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Construire la requête
$where = ['1=1'];
$params = [];

if ($search) {
    $where[] = "(username LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($role_filter) {
    $where[] = "role = ?";
    $params[] = $role_filter;
}

if ($status_filter) {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

$where_clause = implode(' AND ', $where);

// Compter le total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $where_clause");
$stmt->execute($params);
$total_users = $stmt->fetchColumn();
$total_pages = ceil($total_users / $per_page);

// Récupérer les utilisateurs
$stmt = $pdo->prepare("
    SELECT u.*, 
           (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders,
           (SELECT COALESCE(SUM(sell_amount), 0) FROM orders WHERE user_id = u.id) as total_spent
    FROM users u
    WHERE $where_clause
    ORDER BY u.created_at DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$users = $stmt->fetchAll();

// Stats globales
$stats = [];
$stats['total'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['active'] = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
$stats['suspended'] = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'suspended'")->fetchColumn();
$stats['users_count'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$stats['resellers_count'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'reseller'")->fetchColumn();
$stats['admins_count'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/fixes.css">
    <style>
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 16px;
        }
        
        .action-btn {
            padding: 6px 12px;
            font-size: 13px;
            margin: 2px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
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
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s;
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
        
        .filters-bar {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
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
    </style>
</head>
<body class="dashboard-page logged-in">
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1><?php echo getIcon('followers'); ?> Gestion des Utilisateurs</h1>
            </div>
        </div>

        <?php echo renderFlashMessage(); ?>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <?php echo getIcon('followers'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Total Utilisateurs</div>
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <small style="color: #10b981;"><?php echo $stats['active']; ?> actifs</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php echo getIcon('user'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Utilisateurs</div>
                    <div class="stat-value"><?php echo $stats['users_count']; ?></div>
                    <small style="color: #6b7280;">Clients réguliers</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <?php echo getIcon('shares'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Revendeurs</div>
                    <div class="stat-value"><?php echo $stats['resellers_count']; ?></div>
                    <small style="color: #6b7280;">Avec accès API</small>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <?php echo getIcon('warning'); ?>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Suspendus</div>
                    <div class="stat-value"><?php echo $stats['suspended']; ?></div>
                    <small style="color: #ef4444;">À vérifier</small>
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
                               placeholder="<?php echo getIcon('search'); ?> Rechercher par nom ou email..." 
                               value="<?php echo clean($search); ?>">
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <select name="role">
                            <option value="">Tous les rôles</option>
                            <option value="user" <?php echo $role_filter === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                            <option value="reseller" <?php echo $role_filter === 'reseller' ? 'selected' : ''; ?>>Revendeur</option>
                            <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin: 0;">
                        <select name="status">
                            <option value="">Tous les statuts</option>
                            <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Actif</option>
                            <option value="suspended" <?php echo $status_filter === 'suspended' ? 'selected' : ''; ?>>Suspendu</option>
                            <option value="banned" <?php echo $status_filter === 'banned' ? 'selected' : ''; ?>>Banni</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h2>Liste des utilisateurs (<?php echo $total_users; ?>)</h2>
            </div>
            
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <div class="empty-icon"><?php echo getIcon('followers'); ?></div>
                    <h3>Aucun utilisateur trouvé</h3>
                    <p>Aucun utilisateur ne correspond aux critères de recherche.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Solde</th>
                                <th>Commandes</th>
                                <th>Total dépensé</th>
                                <th>Statut</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): 
                                // Générer couleur avatar basée sur le username
                                $colors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'];
                                $color = $colors[ord($user['username'][0]) % count($colors)];
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="user-avatar" style="background: <?php echo $color; ?>">
                                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong><?php echo clean($user['username']); ?></strong>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge badge-danger" style="margin-left: 5px;">Admin</span>
                                            <?php elseif ($user['role'] === 'reseller'): ?>
                                                <span class="badge badge-info" style="margin-left: 5px;">Revendeur</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo clean($user['email']); ?></td>
                                <td>
                                    <?php
                                    $role_badges = [
                                        'admin' => '<span class="badge badge-danger">Admin</span>',
                                        'reseller' => '<span class="badge badge-info">Revendeur</span>',
                                        'user' => '<span class="badge badge-secondary">Utilisateur</span>'
                                    ];
                                    echo $role_badges[$user['role']];
                                    ?>
                                </td>
                                <td><strong style="color: #10b981;"><?php echo formatCurrency($user['balance']); ?></strong></td>
                                <td><?php echo $user['total_orders']; ?></td>
                                <td><?php echo formatCurrency($user['total_spent']); ?></td>
                                <td>
                                    <?php
                                    $status_badges = [
                                        'active' => '<span class="badge badge-success">Actif</span>',
                                        'suspended' => '<span class="badge badge-warning">Suspendu</span>',
                                        'banned' => '<span class="badge badge-danger">Banni</span>'
                                    ];
                                    echo $status_badges[$user['status']];
                                    ?>
                                </td>
                                <td><?php echo timeAgo($user['created_at']); ?></td>
                                <td>
                                    <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" 
                                            class="action-btn" 
                                            style="background: #2563eb; color: white;">
                                        <?php echo getIcon('edit'); ?> Éditer
                                    </button>
                                    <button onclick="adjustBalance(<?php echo $user['id']; ?>, '<?php echo clean($user['username']); ?>')" 
                                            class="action-btn" 
                                            style="background: #10b981; color: white;">
                                        <?php echo getIcon('wallet'); ?> Solde
                                    </button>
                                    <button onclick="resetPassword(<?php echo $user['id']; ?>, '<?php echo clean($user['username']); ?>')" 
                                            class="action-btn" 
                                            style="background: #f59e0b; color: white;">
                                        <?php echo getIcon('lock'); ?> MDP
                                    </button>
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
                        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>&status=<?php echo $status_filter; ?>">
                            ← Précédent
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>&status=<?php echo $status_filter; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>&status=<?php echo $status_filter; ?>">
                            Suivant →
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal Éditer Utilisateur -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo getIcon('edit'); ?> Éditer l'utilisateur</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="form-group">
                        <label>Nom d'utilisateur</label>
                        <input type="text" id="edit_username" disabled style="background: #f3f4f6;">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Rôle</label>
                        <select name="role" id="edit_role" required>
                            <option value="user">Utilisateur</option>
                            <option value="reseller">Revendeur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="status" id="edit_status" required>
                            <option value="active">Actif</option>
                            <option value="suspended">Suspendu</option>
                            <option value="banned">Banni</option>
                        </select>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
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

    <!-- Modal Ajuster Solde -->
    <div id="balanceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo getIcon('wallet'); ?> Ajuster le solde</h2>
                <span class="close" onclick="closeModal('balanceModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="adjust_balance">
                    <input type="hidden" name="user_id" id="balance_user_id">
                    
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <input type="text" id="balance_username" disabled style="background: #f3f4f6;">
                    </div>
                    
                    <div class="form-group">
                        <label>Type d'ajustement</label>
                        <select name="type" required>
                            <option value="add"><?php echo getIcon('add'); ?> Ajouter au solde</option>
                            <option value="subtract">➖ Soustraire du solde</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Montant ($)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Raison de l'ajustement..." required></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" onclick="closeModal('balanceModal')" class="btn btn-secondary">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <?php echo getIcon('success'); ?> Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Réinitialiser MDP -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo getIcon('lock'); ?> Réinitialiser le mot de passe</h2>
                <span class="close" onclick="closeModal('passwordModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="user_id" id="password_user_id">
                    
                    <div class="form-group">
                        <label>Utilisateur</label>
                        <input type="text" id="password_username" disabled style="background: #f3f4f6;">
                    </div>
                    
                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="text" name="new_password" id="new_password" required placeholder="Minimum 8 caractères">
                        <button type="button" onclick="generatePassword()" class="btn btn-sm" style="margin-top: 10px;">
                            🎲 Générer aléatoire
                        </button>
                    </div>
                    
                    <div class="alert alert-warning" style="margin-top: 15px;">
                        <?php echo getIcon('warning'); ?> L'utilisateur devra utiliser ce nouveau mot de passe pour se connecter.
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                        <button type="button" onclick="closeModal('passwordModal')" class="btn btn-secondary">
                            Annuler
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <?php echo getIcon('lock'); ?> Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
    <script>
        function editUser(user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_status').value = user.status;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function adjustBalance(userId, username) {
            document.getElementById('balance_user_id').value = userId;
            document.getElementById('balance_username').value = username;
            document.getElementById('balanceModal').style.display = 'block';
        }
        
        function resetPassword(userId, username) {
            document.getElementById('password_user_id').value = userId;
            document.getElementById('password_username').value = username;
            document.getElementById('passwordModal').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        function generatePassword() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('new_password').value = password;
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
