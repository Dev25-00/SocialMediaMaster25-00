<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2><?php echo getIcon('rocket', true); ?> <?php echo SITE_NAME; ?></h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="nav-item">
            <span class="icon"><?php echo getIcon('stats'); ?></span>
            <span>Dashboard</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/services/index.php" class="nav-item">
            <span class="icon"><?php echo getIcon('services'); ?></span>
            <span>Services</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/orders/new.php" class="nav-item">
            <span class="icon"><?php echo getIcon('add'); ?></span>
            <span>Nouvelle commande</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/orders/history.php" class="nav-item">
            <span class="icon"><?php echo getIcon('orders'); ?></span>
            <span>Mes commandes</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="nav-item">
            <span class="icon"><?php echo getIcon('wallet'); ?></span>
            <span>Mon solde</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/support/tickets.php" class="nav-item">
            <span class="icon"><?php echo getIcon('support'); ?></span>
            <span>Support</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/dashboard/profile.php" class="nav-item">
            <span class="icon"><?php echo getIcon('user'); ?></span>
            <span>Profil</span>
        </a>
        <?php if (isAdmin()): ?>
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-item">
            <span class="icon"><?php echo getIcon('settings'); ?></span>
            <span>Admin</span>
        </a>
        <?php endif; ?>
        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="nav-item" data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?">
            <span class="icon"><?php echo getIcon('logout'); ?></span>
            <span>Déconnexion</span>
        </a>
    </nav>
</div>
