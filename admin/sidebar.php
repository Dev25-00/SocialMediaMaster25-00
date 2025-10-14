<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>🔧 Admin Panel</h2>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="nav-item">
            <span class="icon">📊</span>
            <span>Dashboard</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/users.php" class="nav-item">
            <span class="icon">👥</span>
            <span>Utilisateurs</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/orders.php" class="nav-item">
            <span class="icon">📦</span>
            <span>Commandes</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/services.php" class="nav-item">
            <span class="icon">🛍️</span>
            <span>Services</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/sync-services.php" class="nav-item">
            <span class="icon">🔄</span>
            <span>Sync Services V2 ⚡</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/check-platforms.php" class="nav-item">
            <span class="icon">🌍</span>
            <span>Plateformes</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/update-database-schema.php" class="nav-item">
            <span class="icon">🗄️</span>
            <span>Mise à jour BDD</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/check-prices.php" class="nav-item">
            <span class="icon">💰</span>
            <span>Vérifier Prix</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/analyze-api-data.php" class="nav-item">
            <span class="icon">📊</span>
            <span>Analyser API</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/api-test.php" class="nav-item">
            <span class="icon">🧪</span>
            <span>Test API</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/settings.php" class="nav-item">
            <span class="icon">⚙️</span>
            <span>Paramètres</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/admin/diagnostic.php" class="nav-item">
            <span class="icon">🔍</span>
            <span>Diagnostic</span>
        </a>
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">
        
        <a href="<?php echo SITE_URL; ?>/dashboard/index.php" class="nav-item">
            <span class="icon">👤</span>
            <span>Mon Dashboard</span>
        </a>
        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="nav-item" data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?">
            <span class="icon">🚪</span>
            <span>Déconnexion</span>
        </a>
    </nav>
</div>
