<!-- SMM Mastery - Admin Sidepanel -->
<link rel="stylesheet" href="/smm/assets/css/admin/admin-sidebar-clean.css">
<aside class="admin-sidepanel">
    <nav class="admin-links">
        <!-- Section Gestion avec dropdown -->
        <div class="sidebar-section">
            <button class="sidebar-dropdown" onclick="this.classList.toggle('open')">
                <span class="sidebar-icon">🏠</span>
                <span class="section-title">Gestion</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <div class="sidebar-dropdown-content">
                <a href="/smm/admin/dashboard.php" class="admin-link"><span class="sidebar-icon">📊</span> Dashboard</a>
                <a href="/smm/admin/users.php" class="admin-link"><span class="sidebar-icon">👤</span> Utilisateurs</a>
                <a href="/smm/admin/services.php" class="admin-link"><span class="sidebar-icon">🛠️</span> Services</a>
                <a href="/smm/admin/orders.php" class="admin-link"><span class="sidebar-icon">📝</span> Commandes</a>
                <a href="/smm/admin/tickets.php" class="admin-link"><span class="sidebar-icon">🎫</span> Tickets</a>
            </div>
        </div>
        <!-- Section Paramètres & Outils avec dropdown -->
        <div class="sidebar-section">
            <button class="sidebar-dropdown" onclick="this.classList.toggle('open')">
                <span class="sidebar-icon">⚙️</span>
                <span class="section-title">Paramètres & Outils</span>
                <span class="dropdown-arrow">▼</span>
            </button>
            <div class="sidebar-dropdown-content">
                <a href="/smm/admin/settings.php" class="admin-link"><span class="sidebar-icon">🔧</span> Paramètres</a>
                <a href="/smm/admin/diagnostic.php" class="admin-link"><span class="sidebar-icon">🩺</span> Diagnostic</a>
                <a href="/smm/admin/api-test.php" class="admin-link"><span class="sidebar-icon">🔬</span> Test API</a>
                <a href="/smm/admin/analyze-api-data.php" class="admin-link"><span class="sidebar-icon">📈</span> Analyse API</a>
                <a href="/smm/admin/analyze-services.php" class="admin-link"><span class="sidebar-icon">🔍</span> Analyse Services</a>
                <a href="/smm/admin/check-links.php" class="admin-link"><span class="sidebar-icon">🔗</span> Vérifier Liens</a>
                <a href="/smm/admin/check-platforms.php" class="admin-link"><span class="sidebar-icon">🌐</span> Plateformes</a>
                <a href="/smm/admin/check-platforms-data.php" class="admin-link"><span class="sidebar-icon">📂</span> Données Plateformes</a>
                <a href="/smm/admin/check-prices.php" class="admin-link"><span class="sidebar-icon">💰</span> Vérifier Prix</a>
                <a href="/smm/admin/update-database-schema.php" class="admin-link"><span class="sidebar-icon">🗄️</span> Mise à jour BDD</a>
                <a href="/smm/admin/sync-services.php" class="admin-link"><span class="sidebar-icon">🔄</span> Sync Services</a>
                <a href="/smm/admin/sync-services-OLD.php" class="admin-link"><span class="sidebar-icon">⏳</span> Sync Services OLD</a>
                <a href="/smm/admin/sync-services-v2.php" class="admin-link"><span class="sidebar-icon">⏩</span> Sync Services V2</a>
                <a href="/smm/admin/fix-admin-role.php" class="admin-link"><span class="sidebar-icon">🛡️</span> Fix Admin Role</a>
                <a href="/smm/admin/fix-all-links.php" class="admin-link"><span class="sidebar-icon">🧹</span> Fix All Links</a>
                <a href="/smm/admin/test-icons.php" class="admin-link"><span class="sidebar-icon">⭐</span> Test Icônes</a>
                <a href="/smm/admin/test-location-filter.php" class="admin-link"><span class="sidebar-icon">📍</span> Test Location Filter</a>
                <a href="/smm/admin/verify-config.php" class="admin-link"><span class="sidebar-icon">✅</span> Vérifier Config</a>
            </div>
        </div>
        <!-- Section Accès rapide -->
        <div class="sidebar-section">
            <a href="/smm/dashboard/index.php" class="admin-link"><span class="sidebar-icon">🏠</span> Mon Dashboard</a>
            <a href="/smm/auth/logout.php" class="admin-link" data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?"><span class="sidebar-icon">🚪</span> Déconnexion</a>
        </div>
    </nav>
</aside>
