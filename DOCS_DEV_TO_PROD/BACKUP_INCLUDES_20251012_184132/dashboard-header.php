<?php
/**
 * HEADER UNIFIÉ POUR DASHBOARD (Pages avec session)
 * Inclut: Navigation, User info, Icons professionnels
 */

// Sécurité: Vérifier la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

// Charger config icônes
require_once __DIR__ . '/icons-config.php';

// Informations utilisateur
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';
$role = $_SESSION['role'] ?? 'user';

// Récupérer le solde actuel
try {
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_balance = $stmt->fetchColumn() ?: 0;
} catch (PDOException $e) {
    $user_balance = 0;
}

// Compter les notifications (tickets non lus, commandes en cours, etc.)
$notification_count = 0;
try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM tickets 
        WHERE user_id = ? AND status = 'answered'
    ");
    $stmt->execute([$user_id]);
    $notification_count += $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE user_id = ? AND status = 'completed' 
        AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ");
    $stmt->execute([$user_id]);
    $notification_count += $stmt->fetchColumn();
} catch (PDOException $e) {
    // Silencieux
}

// Déterminer la page active pour highlighting navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Dashboard'; ?> - SMM Mastery</title>
    
    <!-- Font Awesome -->
    <?php echo ICON_CDN; ?>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js (si nécessaire) -->
    <?php if (isset($include_charts) && $include_charts): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</head>
<body class="dashboard-body">
    
    <!-- Header Navigation -->
    <header class="dashboard-header">
        <div class="header-container">
            
            <!-- Logo -->
            <div class="header-logo">
                <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="logo-link">
                    <?php echo getIcon('rocket', true, 'lg'); ?>
                    <span class="logo-text">SMM Mastery</span>
                </a>
            </div>
            
            <!-- Navigation Desktop -->
            <nav class="header-nav desktop-nav">
                <a href="<?php echo SITE_URL; ?>/dashboard/index.php" 
                   class="nav-link <?php echo $current_dir === 'dashboard' && $current_page === 'index' ? 'active' : ''; ?>">
                    <?php echo getIcon('dashboard'); ?>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?php echo SITE_URL; ?>/services/index.php" 
                   class="nav-link <?php echo $current_dir === 'services' ? 'active' : ''; ?>">
                    <?php echo getIcon('services'); ?>
                    <span>Services</span>
                </a>
                
                <a href="<?php echo SITE_URL; ?>/orders/new.php" 
                   class="nav-link <?php echo $current_dir === 'orders' && $current_page === 'new' ? 'active' : ''; ?>">
                    <?php echo getIcon('add'); ?>
                    <span>Nouvelle Commande</span>
                </a>
                
                <a href="<?php echo SITE_URL; ?>/orders/history.php" 
                   class="nav-link <?php echo $current_dir === 'orders' && $current_page === 'history' ? 'active' : ''; ?>">
                    <?php echo getIcon('orders'); ?>
                    <span>Mes Commandes</span>
                </a>
                
                <a href="<?php echo SITE_URL; ?>/support/tickets.php" 
                   class="nav-link <?php echo $current_dir === 'support' ? 'active' : ''; ?>">
                    <?php echo getIcon('support'); ?>
                    <span>Support</span>
                    <?php if ($notification_count > 0): ?>
                        <span class="notification-badge"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>
            </nav>
            
            <!-- User Actions -->
            <div class="header-actions">
                
                <!-- Balance -->
                <div class="balance-display">
                    <?php echo getIcon('wallet'); ?>
                    <span class="balance-amount">$<?php echo number_format($user_balance, 2); ?></span>
                    <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="btn-add-funds">
                        <?php echo getIcon('add', false, 'sm'); ?>
                        <span>Ajouter</span>
                    </a>
                </div>
                
                <!-- Notifications -->
                <div class="notifications-dropdown">
                    <button class="notification-btn">
                        <?php echo getIcon('bell', false, 'lg'); ?>
                        <?php if ($notification_count > 0): ?>
                            <span class="notification-dot"></span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu notification-menu">
                        <div class="dropdown-header">
                            Notifications
                            <?php if ($notification_count > 0): ?>
                                <span class="badge"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="notification-list">
                            <?php if ($notification_count > 0): ?>
                                <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="notification-item">
                                    <?php echo getIcon('support', false, 'md'); ?>
                                    <div class="notification-content">
                                        <div class="notification-title">Nouveaux messages</div>
                                        <div class="notification-time">Support tickets</div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div class="notification-empty">
                                    <?php echo getIcon('info'); ?>
                                    <span>Aucune notification</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="user-dropdown">
                    <button class="user-btn">
                        <?php echo getIcon('user', false, 'lg'); ?>
                        <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu user-menu">
                        <div class="dropdown-header">
                            <div class="user-info">
                                <div class="user-name-large"><?php echo htmlspecialchars($username); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($email); ?></div>
                                <?php if ($role === 'admin'): ?>
                                    <span class="role-badge role-admin">
                                        <?php echo getIcon('shield', false, 'sm'); ?> Admin
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo SITE_URL; ?>/dashboard/profile.php" class="dropdown-item">
                            <?php echo getIcon('user'); ?>
                            <span>Mon Profil</span>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="dropdown-item">
                            <?php echo getIcon('wallet'); ?>
                            <span>Mon Solde</span>
                        </a>
                        <?php if ($role === 'admin'): ?>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="dropdown-item">
                            <?php echo getIcon('settings'); ?>
                            <span>Administration</span>
                        </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="dropdown-item text-danger">
                            <?php echo getIcon('logout'); ?>
                            <span>Déconnexion</span>
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
            </div>
        </div>
    </header>
    
    <!-- Navigation Mobile -->
    <nav class="mobile-nav">
        <div class="mobile-nav-overlay"></div>
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <div class="logo">
                    <?php echo getIcon('rocket', true, 'xl'); ?>
                    <span>SMM Mastery</span>
                </div>
                <button class="mobile-nav-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-nav-user">
                <div class="user-avatar">
                    <?php echo getIcon('user', false, 'xl'); ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
                    <div class="user-balance">
                        <?php echo getIcon('wallet', false, 'sm'); ?>
                        $<?php echo number_format($user_balance, 2); ?>
                    </div>
                </div>
            </div>
            
            <div class="mobile-nav-links">
                <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="mobile-nav-link">
                    <?php echo getIcon('dashboard'); ?>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/services/index.php" class="mobile-nav-link">
                    <?php echo getIcon('services'); ?>
                    <span>Services</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/orders/new.php" class="mobile-nav-link">
                    <?php echo getIcon('add'); ?>
                    <span>Nouvelle Commande</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/orders/history.php" class="mobile-nav-link">
                    <?php echo getIcon('orders'); ?>
                    <span>Mes Commandes</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="mobile-nav-link">
                    <?php echo getIcon('wallet'); ?>
                    <span>Mon Solde</span>
                </a>
                <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="mobile-nav-link">
                    <?php echo getIcon('support'); ?>
                    <span>Support</span>
                    <?php if ($notification_count > 0): ?>
                        <span class="notification-badge"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/dashboard/profile.php" class="mobile-nav-link">
                    <?php echo getIcon('settings'); ?>
                    <span>Paramètres</span>
                </a>
                <?php if ($role === 'admin'): ?>
                <div class="mobile-nav-divider"></div>
                <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="mobile-nav-link">
                    <?php echo getIcon('shield'); ?>
                    <span>Administration</span>
                </a>
                <?php endif; ?>
                <div class="mobile-nav-divider"></div>
                <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="mobile-nav-link text-danger">
                    <?php echo getIcon('logout'); ?>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="dashboard-main">
