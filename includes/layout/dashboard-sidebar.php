<?php
// Variables pour le sidebar
if (!isset($user)) {
    $user = getCurrentUser($pdo);
}
$user_balance = isset($user['balance']) ? $user['balance'] : 0;
$role = isset($user['role']) ? $user['role'] : 'user';

// Variables pour l'état actuel
$current_path = $_SERVER['REQUEST_URI'];
$path_parts = explode('/', trim($current_path, '/'));
$current_page = isset($path_parts[count($path_parts)-1]) ? str_replace('.php', '', $path_parts[count($path_parts)-1]) : 'index';
$current_dir = isset($path_parts[count($path_parts)-2]) ? $path_parts[count($path_parts)-2] : 'dashboard';
?>

<!-- Dashboard Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2><?php echo getIcon('rocket', true); ?> SMM Mastery</h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="nav-item <?php echo $current_dir === 'dashboard' && $current_page === 'index' ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('dashboard'); ?></span>
            <span>Dashboard</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/services/index.php" class="nav-item <?php echo $current_dir === 'services' ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('services'); ?></span>
            <span>Services</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/orders/history.php" class="nav-item <?php echo $current_dir === 'orders' && ($current_page === 'history' || $current_page === 'tracking') ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('orders'); ?></span>
            <span>Mes Commandes</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/dashboard/finances/balance.php" class="nav-item <?php echo $current_dir === 'dashboard' && $current_page === 'balance' ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('balance'); ?></span>
            <span>Mon Solde</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="nav-item <?php echo $current_dir === 'support' ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('support'); ?></span>
            <span>Support</span>
        </a>
        
        <a href="<?php echo SITE_URL; ?>/dashboard/account/profile.php" class="nav-item <?php echo $current_dir === 'dashboard' && $current_page === 'profile' ? 'active' : ''; ?>">
            <span class="icon"><?php echo getIcon('user'); ?></span>
            <span>Mon Profil</span>
        </a>
        
        <?php if ($role === 'admin'): ?>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-item">
            <span class="icon"><?php echo getIcon('settings'); ?></span>
            <span>Administration</span>
        </a>
        <?php endif; ?>
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">
        
        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="nav-item" style="color: #f87171;">
            <span class="icon"><?php echo getIcon('logout'); ?></span>
            <span>Déconnexion</span>
        </a>
    </nav>
</div>
