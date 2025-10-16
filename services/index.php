<?php
/**
 * SMM Mastery - Page Services
 * Date: 14 Octobre 2025
 * Version: 3.0 - Réorganisée et optimisée
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\
 * 
 * FONCTIONNALITÉS:
 * - Affichage grid responsive des services disponibles
 * - Filtres multi-critères avancés (plateforme, tier, action, etc.)
 * - Infinite scroll avec chargement progressif
 * - Modal de commande intégré
 * - Support favoris et partage
 * - Design moderne avec skeleton loading
 * 
 * DÉPENDANCES:
 * - css/filters.css (styles des filtres)
 * - css/mobile-filters.css (responsive mobile)
 * - css/order-modal.css (styles modal commande)
 * - js/services-manager.js (gestionnaire principal)
 * - js/cards-enhancement.js (métadonnées enrichies)
 * - js/order-modal.js (modal de commande)
 * - ../api/services.php (API récupération services)
 * - ../api/create-order.php (API création commande)
 * 
 * STRUCTURE:
 * - Header avec statistiques
 * - Barre de filtres sticky (2 lignes)
 * - Grid responsive (1-5 colonnes)
 * - Modal de commande
 */

require_once '../config.php';
require_once '../functions.php';
require_once '../includes/config/icons-config.php';

// Vérification authentification
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user = getCurrentUser($pdo);

// NE PLUS CHARGER TOUS LES SERVICES ICI
// Les services sont chargés progressivement via API JavaScript (services-manager.js)

// Obtenir uniquement les métadonnées pour les filtres
$platforms_query = "SELECT platform, COUNT(*) as count FROM services WHERE is_active = 1 GROUP BY platform ORDER BY platform";
$platforms_from_db = $pdo->query($platforms_query)->fetchAll(PDO::FETCH_ASSOC);

// Créer un dictionnaire plateforme => count
$platform_counts = [];
foreach ($platforms_from_db as $p) {
    $platform_counts[$p['platform']] = $p['count'];
}

// Compter le total pour l'affichage initial
$total_query = "SELECT COUNT(*) as total FROM services WHERE is_active = 1";
$total = $pdo->query($total_query)->fetch(PDO::FETCH_ASSOC)['total'];

// Obtenir les catégories
$categories = $pdo->query("SELECT DISTINCT category FROM services WHERE is_active = 1 ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

// Configuration page
$page_title = "Services";
$page_title_bar = "Services SMM";

// Inclure header simple
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
?>

<!-- ========================================
     STYLES CSS RÉORGANISÉS
     ======================================== -->
<!-- Filtres 2 Lignes & Grid Responsive -->
<link rel="stylesheet" href="css/filters.css?v=<?php echo time(); ?>">
<!-- Responsive Mobile Ultra-Compact -->
<link rel="stylesheet" href="css/mobile-filters.css?v=<?php echo time(); ?>">
<!-- Modal de Commande -->
<link rel="stylesheet" href="css/order-modal.css?v=<?php echo time(); ?>">

<!-- Container principal avec espacement correct pour le top-bar sticky -->
<div class="container-fluid dashboard-container" style="overflow: visible !important;">
    
    <!-- Content wrapper STICKY FRIENDLY avec padding complet -->
    <div class="dashboard-content-wrapper" style="overflow: visible !important;">
    
    <?php 
    // Header configuration
    $page_header_title = "Services Premium";
    $page_header_icon = "services";
    $page_header_description = "Choisissez parmi <strong>" . number_format($total) . "</strong> services disponibles";
    $page_header_gradient = false;
    require_once __DIR__ . '/../includes/layout/page-header.php';
    ?>

    <!-- Filtres 2 Lignes Optimisés -->
        <div class="services-filters-multiline" id="filtersBar">
            
            <!-- Languette toggle en bas à droite de la section filtres -->
            <button class="filters-toggle-tab" id="filtersToggleTab" title="Afficher/Masquer les filtres" aria-label="Afficher ou masquer les filtres">
                <i class="fas fa-angle-double-up"></i>
            </button>
            
            <!-- Ligne 1: Plateformes (FULL WIDTH) -->
            <div class="filters-row filters-row-primary">
                
                <!-- Plateformes (100% width) -->
                <div class="filter-group-multiline">
                    <button class="filter-label-multiline filter-label-clickable platform-btn-multiline active" 
                            data-platform="" 
                            title="Toutes les plateformes">
                        <?php echo getIcon('services', false, 'sm'); ?>
                    </button>
                    <div class="platform-filters-multiline" id="platformFilters">
                        <?php 
                        // Définir TOUTES les plateformes supportées avec leurs icônes
                        $platform_icons = [
                            // Principales plateformes
                            'Instagram' => ['icon' => 'instagram', 'color' => '#E4405F'],
                            'YouTube' => ['icon' => 'youtube', 'color' => '#FF0000'],
                            'TikTok' => ['icon' => 'tiktok', 'color' => '#000000'],
                            'Facebook' => ['icon' => 'facebook', 'color' => '#1877F2'],
                            'Twitter' => ['icon' => 'twitter', 'color' => '#1DA1F2'],
                            'LinkedIn' => ['icon' => 'linkedin', 'color' => '#0A66C2'],
                            'Telegram' => ['icon' => 'phone', 'color' => '#0088cc'],
                            'Spotify' => ['icon' => 'music', 'color' => '#1DB954'],
                            'Snapchat' => ['icon' => 'camera', 'color' => '#FFFC00'],
                            'Twitch' => ['icon' => 'twitch', 'color' => '#9146FF'],
                            'Discord' => ['icon' => 'discord', 'color' => '#5865F2'],
                            'Reddit' => ['icon' => 'reddit', 'color' => '#FF4500'],
                            'Pinterest' => ['icon' => 'pinterest', 'color' => '#E60023'],
                            
                            // ✅ NOUVELLES PLATEFORMES V2
                            'Kick' => ['icon' => 'play', 'color' => '#53fc18'],
                            'Rumble' => ['icon' => 'video', 'color' => '#85C742'],
                            'BlueSky' => ['icon' => 'cloud', 'color' => '#1185fe'],
                            'Kwai' => ['icon' => 'film', 'color' => '#FF6B00'],
                            'Truth Social' => ['icon' => 'bullhorn', 'color' => '#E81C28'],
                            'Audiomack' => ['icon' => 'headphones', 'color' => '#FFA200'],
                            'Quora' => ['icon' => 'quora', 'color' => '#B92B27'],
                            'Tumblr' => ['icon' => 'tumblr', 'color' => '#35465C'],
                            'SoundCloud' => ['icon' => 'soundcloud', 'color' => '#FF5500'],
                            'Medium' => ['icon' => 'medium', 'color' => '#000000'],
                            'Rutube' => ['icon' => 'play', 'color' => '#2596be'],
                            'Apple Music' => ['icon' => 'apple', 'color' => '#FA243C'],
                            'Chzzk' => ['icon' => 'broadcast', 'color' => '#00E7A0'],
                            'Square' => ['icon' => 'square', 'color' => '#3E4348'],
                            'Website' => ['icon' => 'globe', 'color' => '#6366f1'],
                            'Mobile' => ['icon' => 'mobile', 'color' => '#8b5cf6'],
                            'Worldwide' => ['icon' => 'globe-americas', 'color' => '#10b981']
                        ];
                        
                        // Afficher TOUTES les plateformes définies
                        // Celles qui ont des services afficheront leur count, les autres seront masquées ou grayed out
                        foreach ($platform_icons as $platform_name => $platform_data): 
                            $count = $platform_counts[$platform_name] ?? 0;
                            
                            // Si pas de services, masquer le bouton
                            if ($count === 0) continue;
                        ?>
                            <button class="platform-btn-multiline" 
                                    data-platform="<?php echo htmlspecialchars($platform_name); ?>"
                                    title="<?php echo htmlspecialchars($platform_name); ?> (<?php echo $count; ?>)"
                                    style="--platform-color: <?php echo $platform_data['color']; ?>">
                                <?php echo getIcon($platform_data['icon'], true); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-divider-multiline"></div>

            </div>

            <!-- Ligne 2: Refill + Prix + Pays + Tri + Reset -->
            <!-- Ligne de filtres secondaires remontée en desktop -->
            <div class="filters-row filters-row-secondary filters-row-secondary-desktop">

                <!-- Tiers (moved from row1) -->
                <div class="filter-group-multiline group-tiers">
                    <button class="filter-label-multiline filter-label-clickable tier-btn-multiline active" 
                            data-tier="" 
                            title="Toutes les qualités">
                        <?php echo getIcon('star', false, 'sm'); ?>
                    </button>
                    <div class="tier-filters-multiline" id="tierFilters">
                        <button class="tier-btn-multiline tier-budget" data-tier="budget" title="Budget">
                            <?php echo getIcon('budget', false, 'sm'); ?>
                        </button>
                        <button class="tier-btn-multiline tier-standard" data-tier="standard" title="Standard">
                            <?php echo getIcon('standard', false, 'sm'); ?>
                        </button>
                        <button class="tier-btn-multiline tier-premium" data-tier="premium" title="Premium">
                            <?php echo getIcon('premium', false, 'sm'); ?>
                        </button>
                        <button class="tier-btn-multiline tier-ultimate" data-tier="ultimate" title="Ultimate">
                            <?php echo getIcon('ultimate', false, 'sm'); ?>
                        </button>
                    </div>
                </div>

                <div class="filter-divider-multiline"></div>

                <!-- Type d'action + Drop Rate (moved from row1) -->
                <div class="filter-group-multiline filter-actions-drop-group group-actions">
                    <!-- Type d'action -->
                    <div class="filter-subgroup-multiline">
                        <label class="filter-label-multiline" title="Type d'action">
                            <?php echo getIcon('zap', false, 'sm'); ?>
                        </label>
                        <select id="actionTypeFilter" class="filter-select-multiline filter-select-actions">
                            <option value="">⚡ Actions</option>
                            <option value="followers">👥 Followers</option>
                            <option value="likes">❤️ Likes</option>
                            <option value="views">👁️ Views</option>
                            <option value="subscribers">➕ Subscribers</option>
                            <option value="comments">💬 Comments</option>
                            <option value="shares">🔄 Shares</option>
                        </select>
                    </div>
                    
                    <!-- Drop Rate -->
                    <div class="filter-subgroup-multiline group-drop">
                        <label class="filter-label-multiline" title="Taux de drop">
                            <?php echo getIcon('shield', false, 'sm'); ?>
                        </label>
                        <select id="dropRateFilter" class="filter-select-multiline filter-select-drop">
                            <option value="">🛡️ Drop Rate</option>
                            <option value="No Drop">✅ No Drop</option>
                            <option value="Low Drop">⚠️ Low Drop</option>
                            <option value="High Drop">❌ High Drop</option>
                        </select>
                    </div>
                </div>

                <div class="filter-divider-multiline"></div>

                <!-- Auto-Refill (en jours) -->
                <div class="filter-group-multiline group-refill">
                    <label class="filter-label-multiline" title="Auto-Refill">
                        <?php echo getIcon('refresh', false, 'sm'); ?>
                    </label>
                    <select id="refillFilter" class="filter-select-multiline filter-select-refill">
                        <option value="">🔄 Refill</option>
                        <option value="0">❌ Sans refill</option>
                        <option value="30">⏱️ 1-30 jours</option>
                        <option value="90">⏰ 30-90 jours</option>
                        <option value="365">📅 90-365 jours</option>
                        <option value="lifetime">♾️ Lifetime (365+)</option>
                    </select>
                </div>

                <div class="filter-divider-multiline"></div>

                <!-- Filtre Pays -->
                <div class="filter-group-multiline group-country">
                    <label class="filter-label-multiline" title="Pays">
                        <?php echo getIcon('globe', false, 'sm'); ?>
                    </label>
                    <select id="countryFilter" class="filter-select-multiline filter-select-country">
                        <option value="">🌍 Tous les pays</option>
                        <option value="Worldwide">🌐 Worldwide</option>
                        <option value="USA">🇺🇸 USA</option>
                        <option value="UK">🇬🇧 UK</option>
                        <option value="Canada">🇨🇦 Canada</option>
                        <option value="Australia">🇦🇺 Australia</option>
                        <option value="France">🇫🇷 France</option>
                        <option value="Germany">🇩🇪 Germany</option>
                        <option value="Spain">🇪🇸 Spain</option>
                        <option value="Italy">🇮🇹 Italy</option>
                        <option value="Brazil">🇧🇷 Brazil</option>
                        <option value="India">🇮🇳 India</option>
                        <option value="Japan">🇯🇵 Japan</option>
                        <option value="South Korea">🇰🇷 South Korea</option>
                        <option value="Mexico">🇲🇽 Mexico</option>
                        <option value="Netherlands">🇳🇱 Netherlands</option>
                        <option value="Turkey">🇹🇷 Turkey</option>
                        <option value="Russia">🇷🇺 Russia</option>
                        <option value="Argentina">🇦🇷 Argentina</option>
                        <option value="Poland">🇵🇱 Poland</option>
                        <option value="Sweden">🇸🇪 Sweden</option>
                    </select>
                </div>

                <div class="filter-divider-multiline"></div>

                <!-- Prix Min-Max -->
                <div class="filter-group-multiline price-range-multiline group-price">
                    <label class="filter-label-multiline" title="Budget">
                        <?php echo getIcon('money', false, 'sm'); ?>
                    </label>
                    <input type="number" 
                           id="priceMin" 
                           class="price-input-multiline" 
                           placeholder="Min"
                           min="0"
                           step="0.01">
                    <span class="price-separator-multiline">—</span>
                    <input type="number" 
                           id="priceMax" 
                           class="price-input-multiline" 
                           placeholder="Max"
                           min="0"
                           step="0.01">
                </div>

                <div class="filter-divider-multiline"></div>

                <!-- Tri moved to tertiary row for layout clarity -->

            </div>

            <!-- Ligne 3: ID search, Favoris, Reset, Results count -->
            <div class="filters-row filters-row-tertiary">
                <div class="left-tools">
                    <!-- Recherche par ID (subtile) -->
                    <div class="filter-group-multiline filter-search-id-group">
                        <label class="filter-label-multiline" title="Rechercher par ID">
                            <?php echo getIcon('search', false, 'sm'); ?>
                        </label>
                        <input type="text" 
                               id="searchIdInput" 
                               class="filter-search-id-input" 
                               placeholder="ID service..."
                               maxlength="10">
                        <button class="filter-search-id-clear" 
                                id="clearSearchIdBtn" 
                                title="Effacer la recherche"
                                style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Tri (Prix / Alphabétique) - placed in tertiary for layout -->
                    <div class="filter-group-multiline filter-sort-group" style="margin-left:8px;">
                        <label class="filter-label-multiline" title="Trier">
                            <?php echo getIcon('bar-chart', false, 'sm'); ?>
                        </label>
                        <select id="sortSelect" class="filter-select-multiline">
                            <option value="price-asc">💰 Prix ↑</option>
                            <option value="price-desc">💵 Prix ↓</option>
                            <option value="name-asc">🔤 A-Z</option>
                            <option value="name-desc">🔠 Z-A</option>
                        </select>
                    </div>

                    <!-- Toggle Favoris -->
                    <button class="filter-favorites-toggle" id="favoritesToggleBtn" title="Afficher uniquement les favoris" data-active="false">
                        <?php echo getIcon('star', false, 'sm'); ?>
                        <span class="favorites-label">Favoris</span>
                    </button>

                    <!-- Reset (bouton compact) -->
                    <button class="filter-reset-btn-multiline" id="resetFiltersBtn" title="Réinitialiser tous les filtres">
                        <?php echo getIcon('delete', false, 'sm'); ?>
                    </button>
                </div>

                <div class="right-tools">
                    <!-- Résultats count -->
                    <div class="filter-results-multiline">
                        <span id="resultsCount"><?php echo number_format($total); ?></span>
                        <span class="results-label">services</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Services Grid -->
        <div class="services-grid-modern" id="servicesGrid">
            <!-- Loading State Minimaliste -->
            <div class="loading-state-mini" id="loadingState">
                <div class="loading-spinner-mini"></div>
                <span class="loading-text-mini">Chargement...</span>
            </div>
            
            <!-- Empty State -->
            <div class="empty-state-modern" id="emptyState" style="display: none;">
                <div class="empty-icon-modern">
                    <?php echo getIcon('search', false, 'xl'); ?>
                </div>
                <h3>Aucun service trouvé</h3>
                <p>Essayez de modifier vos critères de recherche</p>
                <button class="btn btn-primary" id="emptyStateResetBtn">
                    Voir tous les services
                </button>
            </div>
            
            <!-- Les services seront chargés ici dynamiquement -->
        </div>

        <!-- Sentinelle pour Infinite Scroll (visible pour IntersectionObserver) -->
        <div class="infinite-scroll-sentinel" id="scrollSentinel">
            <!-- Skeleton Loaders (placeholders animés) -->
            <div class="skeleton-grid" id="skeletonLoaders">
                <!-- Les skeleton cards seront générés par JS -->
            </div>
        </div>

    </div>
</div>

<!-- Template pour skeleton loader (placeholder animé) -->
<template id="skeletonCardTemplate">
    <div class="service-card-skeleton">
        <div class="skeleton-tier-badge skeleton-shimmer"></div>
        
        <div class="skeleton-header">
            <div class="skeleton-icon skeleton-shimmer"></div>
            <div class="skeleton-text-group">
                <div class="skeleton-text skeleton-text-lg skeleton-shimmer"></div>
                <div class="skeleton-text skeleton-text-sm skeleton-shimmer"></div>
            </div>
        </div>
        
        <div class="skeleton-title skeleton-shimmer"></div>
        
        <div class="skeleton-price skeleton-shimmer"></div>
        
        <div class="skeleton-metrics">
            <div class="skeleton-metric skeleton-shimmer"></div>
            <div class="skeleton-metric skeleton-shimmer"></div>
            <div class="skeleton-metric skeleton-shimmer"></div>
        </div>
        
        <div class="skeleton-button skeleton-shimmer"></div>
    </div>
</template>

<!-- Template Compact pour Grid Multi-colonnes -->
<template id="serviceCardTemplate">
    <div class="service-card-modern" data-service-id="">
        <!-- Header: ID | Platform (gauche) | Action (centre si filtré) | Tier (droite) -->
        <div class="service-card-header">
            <span class="service-id-badge"></span>
            <div class="service-platform-badge">
                <i class="platform-icon-mini"></i>
                <span class="platform-name"></span>
            </div>
            <span class="service-action-badge" style="display: none;"></span>
            <span class="service-tier-badge"></span>
        </div>

        <!-- Body: Titre (80%) | Prix + Commander (20%) -->
        <div class="service-card-body">
            <h3 class="service-card-title"></h3>
            
            <!-- Footer: Prix + Action (dans le body) -->
            <div class="service-card-footer">
                <div>
                    <span class="service-price"></span>
                    <span class="service-price-unit"></span>
                </div>
                <div class="service-card-actions">
                    <button class="service-favorite-btn" title="Add to favorites" data-favorite="false">
                        <i class="far fa-star"></i>
                    </button>
                    <a href="#" class="service-order-btn">
                        <i class="fas fa-shopping-cart"></i> Buy
                    </a>
                </div>
            </div>
        </div>

        <!-- Features: Caractéristiques (drop rate, refill, min/max) -->
        <div class="service-features"></div>
    </div>
</template>
        </a>
    </div>
</template>

<style>
/* ========================================
   FILTRES COMPACTS & STICKY - VERSION PRO
   ======================================== */

/* Container principal sticky */
.services-filters-compact {
    position: sticky;
    top: 90px; /* Augmenté pour dashboard header */
    z-index: 998;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    /* Amélioration mobile sticky */
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    will-change: transform;
}

.services-filters-compact:hover {
    box-shadow: 0 6px 28px rgba(102, 126, 234, 0.4);
}

.filters-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

/* ========================================
   GROUPES DE FILTRES
   ======================================== */

.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    padding: 6px;
    border-radius: 12px;
}

/* Labels de filtres (icônes indicateurs) */
.filter-label {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.95);
    font-size: 14px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    flex-shrink: 0;
}

/* Séparateur vertical */
.filter-divider {
    width: 2px;
    height: 32px;
    background: linear-gradient(
        to bottom,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
    );
    margin: 0 4px;
    flex-shrink: 0;
}

/* ========================================
   PLATEFORMES - ICÔNES MINI
   ======================================== */

.platform-filters-mini {
    display: flex;
    gap: 6px;
}

.platform-btn-mini {
    width: 36px;
    height: 36px;
    padding: 0;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: rgba(255, 255, 255, 0.9);
    position: relative;
    flex-shrink: 0;
}

.platform-btn-mini svg,
.platform-btn-mini i {
    width: 16px;
    height: 16px;
}

.platform-btn-mini:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px) scale(1.05);
    border-color: rgba(255, 255, 255, 0.3);
}

.platform-btn-mini.active {
    background: white;
    border-color: white;
    color: var(--platform-color, #667eea);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transform: scale(1.05);
}

/* ========================================
   QUALITÉ - ICÔNES MINI (pas d'emojis)
   ======================================== */

.tier-filters-mini {
    display: flex;
    gap: 6px;
}

.tier-btn-mini {
    width: 36px;
    height: 36px;
    padding: 0;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
    flex-shrink: 0;
}

.tier-btn-mini svg,
.tier-btn-mini i {
    width: 14px;
    height: 14px;
}

.tier-btn-mini:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px) scale(1.05);
}

.tier-btn-mini.active {
    background: white;
    border-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transform: scale(1.05);
}

/* Couleurs spécifiques par tier */
.tier-btn-mini.tier-budget.active {
    color: #f59e0b;
}

.tier-btn-mini.tier-standard.active {
    color: #3b82f6;
}

.tier-btn-mini.tier-premium.active {
    color: #8b5cf6;
}

.tier-btn-mini.tier-ultimate.active {
    color: #ec4899;
}

/* ========================================
   SELECTS COMPACTS
   ======================================== */

.filter-select-mini {
    height: 36px;
    padding: 0 32px 0 12px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid transparent;
    border-radius: 10px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.25s ease;
    min-width: 130px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

.filter-select-mini:hover {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.2);
}

.filter-select-mini:focus {
    outline: none;
    background-color: white;
    color: #667eea;
    border-color: white;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
}

.filter-select-mini option {
    background: white;
    color: #374151;
    padding: 8px;
}

/* ========================================
   PRIX MIN-MAX
   ======================================== */

.price-range-group {
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
}

.price-input-mini {
    width: 75px;
    height: 36px;
    padding: 0 10px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid transparent;
    border-radius: 10px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    text-align: center;
    transition: all 0.25s ease;
}

.price-input-mini::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.price-input-mini:hover {
    background: rgba(255, 255, 255, 0.2);
}

.price-input-mini:focus {
    outline: none;
    background: white;
    color: #667eea;
    border-color: white;
}

.price-separator {
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    font-size: 16px;
    flex-shrink: 0;
}

/* ========================================
   WRAPPER RECHERCHE AVEC ICÔNE
   ======================================== */

.search-wrapper-mini {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}

.search-wrapper-mini svg,
.search-wrapper-mini i {
    position: absolute;
    left: 12px;
    color: rgba(102, 126, 234, 0.6);
    font-size: 14px;
    pointer-events: none;
    z-index: 1;
    width: 14px;
    height: 14px;
}

.search-input-mini {
    padding-left: 38px !important;
}

/* ========================================
   RECHERCHE COMPACTE
   ======================================== */

.search-group-compact {
    flex: 1;
    min-width: 180px;
    max-width: 300px;
}

.search-input-mini {
    width: 100%;
    height: 36px;
    padding: 0 16px;
    background: rgba(255, 255, 255, 0.12);
    border: 2px solid transparent;
    border-radius: 10px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.25s ease;
}

.search-input-mini::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.search-input-mini:hover {
    background: rgba(255, 255, 255, 0.2);
}

.search-input-mini:focus {
    outline: none;
    background: white;
    color: #667eea;
    border-color: white;
}

.search-input-mini:focus::placeholder {
    color: #9ca3af;
}

/* Changer la couleur de l'icône au focus */
.search-wrapper-mini:has(.search-input-mini:focus) svg,
.search-wrapper-mini:has(.search-input-mini:focus) i {
    color: #667eea;
}

/* ========================================
   BOUTON RESET
   ======================================== */

.filter-reset-btn {
    width: 36px;
    height: 36px;
    padding: 0;
    background: rgba(239, 68, 68, 0.25);
    border: 2px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.filter-reset-btn svg,
.filter-reset-btn i {
    width: 16px;
    height: 16px;
}

.filter-reset-btn:hover {
    background: #ef4444;
    border-color: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg) scale(1.1);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.filter-reset-btn:active {
    transform: rotate(90deg) scale(0.95);
}

/* ========================================
   COMPTEUR RÉSULTATS
   ======================================== */

.filter-results {
    margin-left: auto;
    padding: 0 16px;
    height: 36px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    color: white;
    min-width: 50px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}

.filter-results::before {
    content: "📊 ";
    margin-right: 6px;
    font-size: 14px;
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */

@media (max-width: 1600px) {
    .services-filters-compact {
        padding: 14px 20px;
    }
    
    .filters-row {
        gap: 12px;
    }
}

@media (max-width: 1400px) {
    .filters-row {
        gap: 10px;
    }
    
    .platform-btn-mini,
    .tier-btn-mini,
    .filter-reset-btn {
        width: 34px;
        height: 34px;
    }
    
    .filter-select-mini,
    .search-input-mini,
    .price-input-mini,
    .filter-results {
        height: 34px;
        font-size: 12px;
    }
    
    .filter-label {
        width: 26px;
        height: 26px;
        font-size: 13px;
    }
}

@media (max-width: 1200px) {
    .services-filters-compact {
        padding: 12px 16px;
    }
    
    .filter-divider {
        display: none;
    }
    
    .filter-select-mini {
        min-width: 120px;
    }
    
    .search-group-compact {
        min-width: 150px;
        max-width: 250px;
    }
}

@media (max-width: 1024px) {
    .services-filters-compact {
        top: 60px;
        border-radius: 12px;
    }
    
    .filters-row {
        gap: 8px;
    }
    
    .filter-group {
        padding: 4px;
        gap: 6px;
    }
    
    .platform-btn-mini,
    .tier-btn-mini,
    .filter-reset-btn {
        width: 32px;
        height: 32px;
    }
    
    .filter-label {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
}

@media (max-width: 768px) {
    .services-filters-compact {
        border-radius: 0;
        margin-left: -24px;
        margin-right: -24px;
        padding: 10px 12px;
        top: 70px; /* Ajusté pour header fixe */
        /* Fix sticky mobile Chrome/Safari */
        position: -webkit-sticky;
        position: sticky;
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        backface-visibility: hidden;
    }
}
    
    .filters-row {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
        padding-bottom: 4px;
        gap: 8px;
    }
    
    .filters-row::-webkit-scrollbar {
        height: 4px;
    }
    
    .filters-row::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 2px;
    }
    
    .filters-row::-webkit-scrollbar-track {
        background: transparent;
    }
    
    /* Réduire encore les tailles sur mobile */
    .platform-btn-mini,
    .tier-btn-mini,
    .filter-reset-btn {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .filter-select-mini {
        min-width: 110px;
        font-size: 11px;
    }
    
    .search-group-compact {
        min-width: 130px;
        max-width: 180px;
    }
    
    .price-input-mini {
        width: 65px;
    }
    
    .filter-results {
        min-width: 45px;
        padding: 0 12px;
    }
    
    .filter-results::before {
        content: "";
        margin-right: 0;
    }
    
    /* Empêcher le shrink des groupes importants */
    .filter-group {
        flex-shrink: 0;
    }
}

/* ========================================
   ANCIENS STYLES (à conserver)
   ======================================== */

/* Filtres Modernes */
.services-filters-modern {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.filter-section {
    margin-bottom: 30px;
}

.filter-section:last-child {
    margin-bottom: 0;
}

.filter-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Filtres Plateformes */
.platform-filters {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px;
}

.platform-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #f9fafb;
    border: 2px solid transparent;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.platform-btn:hover {
    background: white;
    border-color: #e5e7eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.platform-btn.active {
    background: white;
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}

.platform-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.platform-info {
    flex: 1;
    text-align: left;
}

.platform-name {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #111827;
}

.platform-count {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
}

/* Filtres Qualité */
.tier-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.tier-btn {
    flex: 1;
    min-width: 120px;
    padding: 12px 20px;
    background: #f9fafb;
    border: 2px solid transparent;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 600;
}

.tier-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.tier-btn.active {
    background: white;
    border-color: #2563eb;
    color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
}

.tier-btn.tier-budget:hover {
    border-color: #10b981;
    color: #10b981;
}

.tier-btn.tier-standard:hover {
    border-color: #3b82f6;
    color: #3b82f6;
}

.tier-btn.tier-premium:hover {
    border-color: #8b5cf6;
    color: #8b5cf6;
}

.tier-btn.tier-ultimate:hover {
    border-color: #f59e0b;
    color: #f59e0b;
}

/* Barre de recherche */
.search-box-modern {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-input-modern {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.search-input-modern:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-btn-modern,
.clear-search-btn {
    padding: 14px 20px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn-modern:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.clear-search-btn {
    background: #ef4444;
}

.clear-search-btn:hover {
    background: #dc2626;
}

.filter-actions {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

/* Grid de Services Moderne */
.services-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.service-card-modern {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.tier-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    z-index: 1;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.tier-badge.tier-budget {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 2px solid #6ee7b7;
}

.tier-badge.tier-standard {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 2px solid #93c5fd;
}

.tier-badge.tier-premium {
    background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
    color: #6b21a8;
    border: 2px solid #c084fc;
}

.tier-badge.tier-ultimate {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 2px solid #fcd34d;
}

.service-platform-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.platform-icon-large {
    font-size: 32px;
}

.service-platform-name {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
}

.service-category {
    font-size: 13px;
    color: #6b7280;
}

.service-name-modern {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 16px;
    line-height: 1.4;
}

.service-price-modern {
    display: flex;
    align-items: baseline;
    gap: 6px;
    margin-bottom: 20px;
}

.price-value {
    font-size: 28px;
    font-weight: 800;
    color: #2563eb;
}

.price-unit {
    font-size: 14px;
    color: #6b7280;
}

.service-metrics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
}

.metric-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #6b7280;
    padding: 8px 12px;
    background: #f9fafb;
    border-radius: 8px;
}

.service-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-no-drop, .badge-nodrop {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.badge-low-drop, .badge-lowdrop {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fcd34d;
}

.badge-high-drop, .badge-highdrop {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.badge-refill {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.badge-unknown {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #d1d5db;
}

.service-description-modern {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 20px;
}

.btn-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 24px;
    font-weight: 600;
}

.btn-arrow {
    margin-left: auto;
    transition: transform 0.3s ease;
}

.btn-modern:hover .btn-arrow {
    transform: translateX(4px);
}

/* Empty State */
.empty-state-modern {
    text-align: center;
    padding: 80px 20px;
    grid-column: 1 / -1;
}

.empty-icon-modern {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-state-modern h3 {
    font-size: 24px;
    color: #111827;
    margin-bottom: 10px;
}

.empty-state-modern p {
    color: #6b7280;
    margin-bottom: 30px;
}

/* Pagination Moderne */
.pagination-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 40px;
}

.pagination-btn,
.pagination-number {
    padding: 10px 18px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    color: #374151;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.pagination-btn:hover,
.pagination-number:hover {
    border-color: #2563eb;
    color: #2563eb;
    transform: translateY(-2px);
}

.pagination-number.active {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
}

.pagination-numbers {
    display: flex;
    gap: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .platform-filters {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
    
    .tier-filters {
        flex-direction: column;
    }
    
    .tier-btn {
        min-width: 100%;
    }
    
    .services-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .pagination-modern {
        flex-wrap: wrap;
    }
}

/* Compteur de résultats */
.results-info {
    padding: 12px 20px;
    background: #f9fafb;
    border-radius: 12px;
    text-align: center;
}

.results-count {
    font-size: 14px;
    font-weight: 600;
    color: #2563eb;
}

/* Sélecteur de tri */
.sort-select-modern {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sort-select-modern:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* ========================================
   LOADING STATE MINIMALISTE
   ======================================== */

.loading-state-mini {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: white;
    padding: 12px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    display: none; /* Masqué par défaut */
    align-items: center;
    gap: 12px;
    z-index: 1000;
    animation: slideInUp 0.3s ease-out;
}

.loading-state-mini.active {
    display: flex;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.loading-spinner-mini {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-text-mini {
    color: #374151;
    font-size: 13px;
    font-weight: 600;
}

/* Ancien loading state (conservé pour compatibilité) */
.loading-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    display: none; /* Masqué par défaut maintenant */
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

.loading-progress {
    max-width: 400px;
    height: 8px;
    background: #e5e7eb;
    border-radius: 10px;
    margin: 20px auto;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 10px;
    width: 0%;
    transition: width 0.3s ease;
}

.loading-count {
    display: block;
    margin-top: 10px;
    color: #6b7280;
    font-size: 14px;
    font-weight: 600;
}

.load-more-section {
    text-align: center;
    padding: 40px 20px;
}

.btn-lg {
    padding: 16px 40px;
    font-size: 18px;
}

/* Infinite Scroll Sentinel */
.infinite-scroll-sentinel {
    padding: 0;
    min-height: auto;
}

.skeleton-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    padding: 0 20px 40px 20px;
}

/* Skeleton Card - Structure identique à service-card-modern */
.service-card-skeleton {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

/* Skeleton Elements */
.skeleton-tier-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 80px;
    height: 24px;
    border-radius: 12px;
}

.skeleton-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.skeleton-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    flex-shrink: 0;
}

.skeleton-text-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.skeleton-text {
    height: 12px;
    border-radius: 6px;
}

.skeleton-text-lg {
    width: 120px;
}

.skeleton-text-sm {
    width: 80px;
}

.skeleton-title {
    height: 20px;
    border-radius: 8px;
    margin-bottom: 16px;
    width: 85%;
}

.skeleton-price {
    height: 32px;
    border-radius: 8px;
    margin-bottom: 20px;
    width: 120px;
}

.skeleton-metrics {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.skeleton-metric {
    height: 40px;
    border-radius: 8px;
    flex: 1;
}

.skeleton-button {
    height: 48px;
    border-radius: 12px;
    width: 100%;
}

/* Animation Shimmer - Effet de vague lumineuse */
.skeleton-shimmer {
    background: linear-gradient(
        90deg,
        #f3f4f6 0%,
        #e5e7eb 20%,
        #f3f4f6 40%,
        #f3f4f6 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* Animations de fade pour transitions douces */
.skeleton-grid {
    opacity: 1;
    transition: opacity 0.3s ease-out;
}

.skeleton-grid.fading-out {
    opacity: 0;
    pointer-events: none;
}

.service-card-modern {
    opacity: 0;
    animation: fadeInUp 0.4s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation staggered pour les nouvelles cartes */
.service-card-modern:nth-child(1) { animation-delay: 0.05s; }
.service-card-modern:nth-child(2) { animation-delay: 0.1s; }
.service-card-modern:nth-child(3) { animation-delay: 0.15s; }
.service-card-modern:nth-child(4) { animation-delay: 0.2s; }
.service-card-modern:nth-child(5) { animation-delay: 0.25s; }
.service-card-modern:nth-child(6) { animation-delay: 0.3s; }

/* Responsive Skeleton Grid */
@media (max-width: 1200px) {
    .skeleton-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .skeleton-grid {
        grid-template-columns: 1fr;
        gap: 16px;
        padding: 0 15px 30px 15px;
    }
}

.sentinel-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.sentinel-loader .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #2563eb;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.sentinel-loader p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

/* ========================================
   FAVORITE BUTTON SYSTEM
   ======================================== */

.service-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.service-favorite-btn {
    background: transparent;
    border: 2px solid #e5e7eb;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    color: #9ca3af;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.service-favorite-btn:hover {
    border-color: #fbbf24;
    color: #fbbf24;
    transform: scale(1.05);
}

.service-favorite-btn.active {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-color: #fcd34d;
    color: #f59e0b;
}

.service-favorite-btn.active i {
    font-weight: 900; /* Solid star */
}

.service-favorite-btn.active:hover {
    transform: scale(1.1) rotate(15deg);
}

.service-order-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.service-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
}

</style>

<script>
/* ⚠️ CODE INLINE DÉSACTIVÉ - Utilisation de services-manager-multiline.js à la place
// 🚀 CHARGEMENT PROGRESSIF + FILTRAGE CÔTÉ CLIENT
// Services chargés par paquets de 50 via API

const ServicesManager = {
    allServices: [],
    filteredServices: [],
    filters: {
        platform: '',
        tier: '',
        search: '',
        actionType: '',      // 🆕 Nouveau filtre
        features: '',        // 🆕 Nouveau filtre
        priceMin: 0,         // 🆕 Nouveau filtre
        priceMax: null       // 🆕 Nouveau filtre
    },
    sortBy: 'popular',
    loading: false,
    currentPage: 0,
    totalPages: 0,
    totalServices: 0,
    perPage: 50,
    
    // Configuration des plateformes
    platformConfig: {
        'Instagram': { icon: 'fa-brands fa-instagram', color: '#E4405F' },
        'YouTube': { icon: 'fa-brands fa-youtube', color: '#FF0000' },
        'TikTok': { icon: 'fa-brands fa-tiktok', color: '#000000' },
        'Facebook': { icon: 'fa-brands fa-facebook', color: '#1877F2' },
        'Twitter': { icon: 'fa-brands fa-twitter', color: '#1DA1F2' },
        'LinkedIn': { icon: 'fa-brands fa-linkedin', color: '#0A66C2' },
        'Telegram': { icon: 'fa-solid fa-paper-plane', color: '#0088cc' },
        'Spotify': { icon: 'fa-brands fa-spotify', color: '#1DB954' },
        'Snapchat': { icon: 'fa-brands fa-snapchat', color: '#FFFC00' }
    },
    
    async init() {
        console.log('🚀 Initialisation du chargement avec filtres...');
        
        // Détecter le filtre plateforme actif par défaut
        const activeBtn = document.querySelector('.platform-btn.active');
        if (activeBtn) {
            this.filters.platform = activeBtn.dataset.platform || '';
        }
        
        console.log('🎯 Plateforme sélectionnée:', this.filters.platform || 'Toutes');
        
        // Attacher les événements
        this.attachEvents();
        
        // Charger le premier paquet avec le filtre
        await this.loadMoreServices();
    },
    
    async loadMoreServices() {
        if (this.loading) return;
        
        this.loading = true;
        this.currentPage++;
        
        const loadingState = document.getElementById('loadingState');
        const scrollSentinel = document.getElementById('scrollSentinel');
        const skeletonContainer = document.getElementById('skeletonLoaders');
        
        // 🆕 Afficher le loading state minimaliste
        if (this.currentPage === 1) {
            // Premier chargement : masquer le loading initial
            loadingState.style.display = 'none';
        } else {
            // Chargements suivants : afficher le mini loader en bas à droite
            loadingState.classList.add('active');
        }
        
        // Afficher les skeleton loaders AVANT le chargement (sauf pour la 1ère page)
        if (this.currentPage > 1) {
            this.showSkeletonLoaders(this.perPage);
        }
        
        try {
            // Construire l'URL avec TOUS les filtres
            let url = `../api/services.php?page=${this.currentPage}&per_page=${this.perPage}`;
            
            if (this.filters.platform) {
                url += `&platform=${encodeURIComponent(this.filters.platform)}`;
            }
            if (this.filters.tier) {
                url += `&tier=${encodeURIComponent(this.filters.tier)}`;
            }
            if (this.filters.search) {
                url += `&search=${encodeURIComponent(this.filters.search)}`;
            }
            // 🆕 Nouveaux filtres
            if (this.filters.actionType) {
                url += `&action_type=${encodeURIComponent(this.filters.actionType)}`;
            }
            if (this.filters.features) {
                url += `&features=${encodeURIComponent(this.filters.features)}`;
            }
            if (this.filters.priceMin > 0) {
                url += `&price_min=${this.filters.priceMin}`;
            }
            if (this.filters.priceMax) {
                url += `&price_max=${this.filters.priceMax}`;
            }
            
            console.log(`📦 Chargement page ${this.currentPage} avec filtres:`, this.filters);
            console.log(`🔗 URL:`, url);
            
            const response = await fetch(url);
            console.log(`📡 Response status: ${response.status}`);
            
            const text = await response.text();
            console.log(`📄 Response length:`, text.length, 'chars');
            
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('❌ JSON Parse Error:', e);
                console.error('Raw response:', text.substring(0, 500));
                throw new Error('Invalid JSON response');
            }
            
            console.log(`📊 Data:`, data);
            
            if (data.success) {
                console.log(`✅ Received ${data.services.length} services`);
                
                // Mettre à jour le total pour ce filtre
                this.totalServices = data.pagination.total;
                this.totalPages = data.pagination.total_pages;
                
                // Petit délai avant de masquer les skeletons et afficher les nouvelles cartes
                // Cela permet une transition plus douce
                setTimeout(() => {
                    // Ajouter les nouveaux services
                    data.services.forEach(service => {
                        this.addService(service);
                    });
                    
                    // Mettre à jour la barre de progression
                    const progress = (this.allServices.length / this.totalServices) * 100;
                    document.getElementById('loadingProgress').style.width = progress + '%';
                    document.getElementById('loadingCount').textContent = 
                        `${this.allServices.length} / ${this.totalServices}`;
                    
                    console.log(`✅ ${data.services.length} services chargés (${this.allServices.length} / ${this.totalServices})`);
                    
                    // Si c'est le premier chargement, masquer le loading et afficher les services
                    if (this.currentPage === 1) {
                        loadingState.style.display = 'none';
                        this.renderAllServices();
                    } else {
                        // Sinon, juste afficher les nouveaux services
                        this.renderAllServices();
                    }
                }, 150); // Petit délai pour synchroniser avec le fade-out des skeletons
                
                // 🆕 Masquer le mini loader après chargement
                loadingState.classList.remove('active');
                
                // Afficher/masquer la sentinelle selon s'il y a plus de données
                if (data.pagination.has_more) {
                    scrollSentinel.style.display = 'block';
                } else {
                    scrollSentinel.style.display = 'none';
                    console.log('🎉 Tous les services ont été chargés !');
                }
                
            } else {
                console.error('❌ API Error:', data);
                
                // 🆕 Masquer le mini loader
                loadingState.classList.remove('active');
                
                // Masquer les skeleton loaders en cas d'erreur
                this.hideSkeletonLoaders();
                
                alert('Erreur lors du chargement des services: ' + (data.error || 'Unknown error'));
            }
            
        } catch (error) {
            console.error('❌ Erreur complète:', error);
            console.error('Stack:', error.stack);
            
            // 🆕 Masquer le mini loader
            loadingState.classList.remove('active');
            
            // Masquer les skeleton loaders en cas d'erreur
            this.hideSkeletonLoaders();
            
            alert('⚠️ Erreur de connexion: ' + error.message);
            
        } finally {
            // 🆕 Toujours masquer le mini loader à la fin
            const loadingState = document.getElementById('loadingState');
            if (loadingState) {
                loadingState.classList.remove('active');
            }
            
            // Masquer les skeleton loaders
            this.hideSkeletonLoaders();
            this.loading = false;
        }
    },
    
    showSkeletonLoaders(count = 6) {
        const container = document.getElementById('skeletonLoaders');
        const template = document.getElementById('skeletonCardTemplate');
        
        if (!container || !template) return;
        
        // Vider le conteneur
        container.innerHTML = '';
        
        // Créer les skeleton cards
        for (let i = 0; i < count; i++) {
            const skeleton = template.content.cloneNode(true);
            container.appendChild(skeleton);
        }
        
        console.log(`💀 ${count} skeleton loaders affichés`);
    },
    
    hideSkeletonLoaders() {
        const container = document.getElementById('skeletonLoaders');
        if (!container) return;

        // Ajouter classe de fade-out
        const grid = container.querySelector('.skeleton-grid');
        if (grid) {
            grid.classList.add('fading-out');
            console.log('💀 Fade-out des skeleton loaders...');
            
            // Attendre la fin de l'animation avant de supprimer
            setTimeout(() => {
                container.innerHTML = '';
                console.log('💀 Skeleton loaders masqués');
            }, 300); // Correspond à la durée du transition CSS
        } else {
            container.innerHTML = '';
            console.log('💀 Skeleton loaders masqués (pas de grid)');
        }
    },
    
    addService(serviceData) {
        // Créer l'élément de service depuis le template
        const template = document.getElementById('serviceCardTemplate');
        const card = template.content.cloneNode(true).querySelector('.service-card-modern');
        
        // Récupérer la config de la plateforme
        const platformData = this.platformConfig[serviceData.platform] || 
            { icon: 'fa-solid fa-globe', color: '#6b7280' };
        
        // Remplir les données
        card.dataset.serviceId = serviceData.id;
        card.dataset.platform = serviceData.platform;
        card.dataset.tier = serviceData.tier;
        card.dataset.category = serviceData.category;
        card.dataset.price = serviceData.sell_price;
        card.dataset.minQuantity = serviceData.min_quantity;
        card.dataset.maxQuantity = serviceData.max_quantity;
        card.dataset.dropRate = serviceData.drop_rate || '';
        card.dataset.refillDays = serviceData.refill_days || 0;
        
        // Badge tier avec icône
        const tierBadge = card.querySelector('.tier-badge');
        tierBadge.className = `tier-badge tier-${serviceData.tier}`;
        
        // Ajouter l'icône appropriée selon le tier
        const tierIcons = {
            'budget': '💰',
            'standard': '⭐',
            'premium': '👑',
            'ultimate': '💎'
        };
        tierBadge.innerHTML = `${tierIcons[serviceData.tier] || '✨'} ${this.getTierLabel(serviceData.tier)}`;
        
        // Header plateforme
        const header = card.querySelector('.service-platform-header');
        header.style.background = platformData.color + '15';
        
        const icon = card.querySelector('.platform-icon-element');
        icon.className = platformData.icon;
        icon.style.color = platformData.color;
        
        card.querySelector('.service-platform-name').textContent = serviceData.platform;
        card.querySelector('.service-category').textContent = serviceData.category;
        
        // Nom et prix
        card.querySelector('.service-name-modern').textContent = serviceData.name;
        card.querySelector('.price-value').textContent = this.formatPrice(serviceData.sell_price);
        
        // Métriques
        card.querySelector('.metric-min').textContent = 'Min: ' + this.formatNumber(serviceData.min_quantity);
        card.querySelector('.metric-max').textContent = 'Max: ' + this.formatNumber(serviceData.max_quantity);
        
        // Badges avec icônes
        const badgesContainer = card.querySelector('.service-badges');
        if (serviceData.drop_rate && serviceData.drop_rate !== 'Unknown') {
            const badge = document.createElement('span');
            const dropClass = serviceData.drop_rate.toLowerCase().replace(/ /g, '-');
            badge.className = `badge badge-${dropClass}`;
            
            // Ajouter l'icône selon le drop rate
            let dropIcon = '🛡️';
            if (serviceData.drop_rate === 'No Drop') dropIcon = '✅';
            else if (serviceData.drop_rate === 'Low Drop') dropIcon = '⚠️';
            else if (serviceData.drop_rate === 'High Drop') dropIcon = '⚠️';
            
            badge.innerHTML = `${dropIcon} ${serviceData.drop_rate}`;
            badgesContainer.appendChild(badge);
        }
        
        if (serviceData.refill_days) {
            const badge = document.createElement('span');
            badge.className = 'badge badge-refill';
            badge.innerHTML = `♻️ Refill ${serviceData.refill_days}j`;
            badgesContainer.appendChild(badge);
        }
        
        // Description
        if (serviceData.description) {
            card.querySelector('.service-description-modern').textContent = serviceData.description;
        } else {
            card.querySelector('.service-description-modern').style.display = 'none';
        }
        
        // Bouton commander (href="#" car géré par event listener)
        card.querySelector('.service-order-btn').href = '#';
        card.querySelector('.service-order-btn').dataset.serviceId = serviceData.id;
        
        // Ajouter au DOM
        document.getElementById('servicesGrid').appendChild(card);
        
        // Stocker les données du service
        this.allServices.push({
            element: card,
            id: serviceData.id,
            platform: serviceData.platform,
            tier: serviceData.tier,
            category: serviceData.category,
            name: serviceData.name,
            description: serviceData.description || '',
            price: parseFloat(serviceData.sell_price)
        });
    },
    
    getTierLabel(tier) {
        const labels = {
            'budget': 'Budget',
            'standard': 'Standard',
            'premium': 'Premium',
            'ultimate': 'Ultimate'
        };
        return labels[tier] || tier;
    },
    
    formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    },
    
    formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
    },
    
    attachEvents() {
        // Filtres plateformes (version mini)
        document.querySelectorAll('.platform-btn-mini').forEach(btn => {
            btn.addEventListener('click', () => {
                const platform = btn.dataset.platform || '';
                
                // Marquer comme actif
                document.querySelectorAll('.platform-btn-mini').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Recharger avec le nouveau filtre
                this.filters.platform = platform;
                this.reloadWithFilters();
            });
        });
        
        // Filtres tiers (version mini)
        document.querySelectorAll('.tier-btn-mini').forEach(btn => {
            btn.addEventListener('click', () => {
                const tier = btn.dataset.tier || '';
                
                // Marquer comme actif
                document.querySelectorAll('.tier-btn-mini').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Recharger avec le nouveau filtre
                this.filters.tier = tier;
                this.reloadWithFilters();
            });
        });
        
        // 🆕 Filtre Type d'Action
        document.getElementById('actionTypeFilter')?.addEventListener('change', (e) => {
            this.filters.actionType = e.target.value;
            this.reloadWithFilters();
        });
        
        // 🆕 Filtre Features (Drop, Refill, etc.)
        document.getElementById('featuresFilter')?.addEventListener('change', (e) => {
            this.filters.features = e.target.value;
            this.reloadWithFilters();
        });
        
        // 🆕 Filtre Prix Min
        document.getElementById('priceMin')?.addEventListener('change', (e) => {
            this.filters.priceMin = parseFloat(e.target.value) || 0;
            this.reloadWithFilters();
        });
        
        // 🆕 Filtre Prix Max
        document.getElementById('priceMax')?.addEventListener('change', (e) => {
            this.filters.priceMax = parseFloat(e.target.value) || null;
            this.reloadWithFilters();
        });
        
        // Recherche
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        searchInput?.addEventListener('input', (e) => {
            const value = e.target.value;
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.filters.search = value;
                this.reloadWithFilters();
            }, 500);
        });
        
        // Tri
        document.getElementById('sortSelect')?.addEventListener('change', (e) => {
            this.sortBy = e.target.value;
            this.renderAllServices();
        });
        
        // Reset tous les filtres
        document.getElementById('resetFiltersBtn')?.addEventListener('click', () => {
            this.resetFilters();
        });
        
        document.getElementById('emptyStateResetBtn')?.addEventListener('click', () => {
            this.resetFilters();
        });
        
        // Infinite Scroll avec Intersection Observer
        this.setupInfiniteScroll();
    },
    
    setupInfiniteScroll() {
        const sentinel = document.getElementById('scrollSentinel');
        
        if (!sentinel) {
            console.warn('⚠️ Sentinelle non trouvée');
            return;
        }
        
        // Créer l'Intersection Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Si la sentinelle est visible et qu'on ne charge pas déjà
                if (entry.isIntersecting && !this.loading) {
                    console.log('👁️ Sentinelle visible - Chargement automatique...');
                    this.loadMoreServices();
                }
            });
        }, {
            root: null, // viewport
            rootMargin: '200px', // Charger 200px avant d'atteindre la sentinelle
            threshold: 0.1
        });
        
        // Observer la sentinelle
        observer.observe(sentinel);
        console.log('✅ Infinite Scroll activé avec sentinelle');
        
        // Sauvegarder l'observer pour pouvoir le déconnecter si besoin
        this.scrollObserver = observer;
    },
    
    async reloadWithFilters() {
        console.log('🔄 Rechargement avec filtres:', this.filters);
        
        // Vider les services actuels
        this.allServices.forEach(service => {
            service.element.remove();
        });
        this.allServices = [];
        this.filteredServices = [];
        this.currentPage = 0;
        
        // Afficher le loading
        document.getElementById('loadingState').style.display = 'flex';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('scrollSentinel').style.display = 'none';
        
        // Recharger depuis le début
        await this.loadMoreServices();
    },
    
    setFilter(key, value) {
        this.filters[key] = value;
        this.reloadWithFilters();
    },
    
    renderAllServices() {
        console.log('🎨 Affichage de tous les services chargés:', this.allServices.length);
        
        const emptyState = document.getElementById('emptyState');
        
        if (this.allServices.length === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
            
            // Trier selon le mode de tri actuel
            const tierOrder = { 'budget': 1, 'standard': 2, 'premium': 3, 'ultimate': 4 };
            const sortedServices = [...this.allServices];
            
            switch (this.sortBy) {
                case 'price-asc':
                    sortedServices.sort((a, b) => a.price - b.price);
                    break;
                case 'price-desc':
                    sortedServices.sort((a, b) => b.price - a.price);
                    break;
                case 'name':
                    sortedServices.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'popular':
                default:
                    sortedServices.sort((a, b) => {
                        const tierDiff = (tierOrder[a.tier] || 0) - (tierOrder[b.tier] || 0);
                        return tierDiff !== 0 ? tierDiff : a.price - b.price;
                    });
                    break;
            }
            
            // Afficher tous les services avec animation
            sortedServices.forEach((service, index) => {
                service.element.style.display = 'block';
                service.element.style.order = index;
                
                service.element.style.animation = 'none';
                setTimeout(() => {
                    service.element.style.animation = `fadeInUp 0.4s ease ${index * 0.02}s both`;
                }, 10);
            });
        }
    },
    
    applyFilters() {
        this.filteredServices = this.allServices.filter(service => {
            if (this.filters.platform && service.platform !== this.filters.platform) {
                return false;
            }
            
            if (this.filters.tier && service.tier !== this.filters.tier) {
                return false;
            }
            
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                const searchable = `${service.name} ${service.description} ${service.platform} ${service.category}`.toLowerCase();
                if (!searchable.includes(search)) {
                    return false;
                }
            }
            
            return true;
        });
        
        this.sortServices();
        this.renderServices();
        this.updateUI();
    },
    
    sortServices() {
        const tierOrder = { 'budget': 1, 'standard': 2, 'premium': 3, 'ultimate': 4 };
        
        switch (this.sortBy) {
            case 'price-asc':
                this.filteredServices.sort((a, b) => a.price - b.price);
                break;
            case 'price-desc':
                this.filteredServices.sort((a, b) => b.price - a.price);
                break;
            case 'name':
                this.filteredServices.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'popular':
            default:
                this.filteredServices.sort((a, b) => {
                    const tierDiff = (tierOrder[a.tier] || 0) - (tierOrder[b.tier] || 0);
                    return tierDiff !== 0 ? tierDiff : a.price - b.price;
                });
                break;
        }
    },
    
    renderServices() {
        const emptyState = document.getElementById('emptyState');
        
        this.allServices.forEach(service => {
            service.element.style.display = 'none';
        });
        
        if (this.filteredServices.length === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
            
            this.filteredServices.forEach((service, index) => {
                service.element.style.display = 'block';
                service.element.style.order = index;
                
                service.element.style.animation = 'none';
                setTimeout(() => {
                    service.element.style.animation = `fadeInUp 0.4s ease ${index * 0.03}s both`;
                }, 10);
            });
        }
    },
    
    updateUI() {
        const count = this.filteredServices.length;
        const countEl = document.getElementById('resultsCount');
        if (countEl) {
            countEl.textContent = `${count} service${count > 1 ? 's' : ''} trouvé${count > 1 ? 's' : ''}`;
            countEl.style.transform = 'scale(1.1)';
            setTimeout(() => {
                countEl.style.transform = 'scale(1)';
            }, 200);
        }
        
        const hasFilters = this.filters.platform || this.filters.tier || this.filters.search;
        const resetSection = document.getElementById('resetFiltersSection');
        if (resetSection) {
            resetSection.style.display = hasFilters ? 'block' : 'none';
        }
    },
    
    resetFilters() {
        // Réinitialiser TOUS les filtres
        this.filters = { 
            platform: '', 
            tier: '', 
            search: '',
            actionType: '',      // 🆕
            features: '',        // 🆕
            priceMin: 0,         // 🆕
            priceMax: null       // 🆕
        };
        this.sortBy = 'popular';
        
        // Réinitialiser les champs UI
        document.getElementById('searchInput').value = '';
        document.getElementById('sortSelect').value = 'popular';
        document.getElementById('actionTypeFilter').value = '';
        document.getElementById('featuresFilter').value = '';
        document.getElementById('priceMin').value = '0';
        document.getElementById('priceMax').value = '';
        
        // Réinitialiser les boutons plateformes (version mini)
        document.querySelectorAll('.platform-btn-mini').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.platform === '') btn.classList.add('active');
        });
        
        // Réinitialiser les boutons tiers (version mini)
        document.querySelectorAll('.tier-btn-mini').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.tier === '') btn.classList.add('active');
        });
        
        this.reloadWithFilters();
    }
};

// CSS Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .service-card-modern {
        transition: all 0.3s ease;
    }
    
    .results-count {
        transition: transform 0.2s ease;
    }
    
    #clearSearchBtn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    #clearSearchBtn:hover {
        background: #dc2626;
    }
    
    .search-box-modern {
        position: relative;
    }
`;
document.head.appendChild(style);

// Initialiser
document.addEventListener('DOMContentLoaded', () => {
    ServicesManager.init();
});
*/ // FIN DU CODE INLINE DÉSACTIVÉ
</script>

<!-- ========================================
     SCRIPTS JAVASCRIPT RÉORGANISÉS
     ======================================== -->
<!-- Gestionnaire Principal des Services -->
<script src="js/services-manager.js?v=<?php echo time(); ?>"></script>
<script>
    /**
     * INITIALISATION DU GESTIONNAIRE DE SERVICES
     * 
     * Appelé au chargement du DOM
     * Configure les filtres, le grid et le scroll infini
     */
    document.addEventListener('DOMContentLoaded', () => {
        ServicesManagerMultiline.init();
        
        // ===== TOGGLE COLLAPSE FILTRES (Desktop + Mobile avec languette) =====
        const toggleTab = document.getElementById('filtersToggleTab');
        const filtersBar = document.getElementById('filtersBar');
        
        // Toggle collapse au clic (desktop + mobile)
        if (toggleTab) {
            toggleTab.addEventListener('click', () => {
                filtersBar.classList.toggle('filters-collapsed');
                const isCollapsed = filtersBar.classList.contains('filters-collapsed');
                localStorage.setItem('filtersCollapsed', isCollapsed);
            });
        }
        
        // Restaurer l'état depuis localStorage
        const savedState = localStorage.getItem('filtersCollapsed');
        if (savedState === 'true') {
            filtersBar.classList.add('filters-collapsed');
        }
    });
    
    // ========================================
    // FAVORITES SYSTEM
    // ========================================
    
    document.addEventListener('click', async (e) => {
        const favoriteBtn = e.target.closest('.service-favorite-btn');
        if (!favoriteBtn) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        // Récupérer le service ID depuis la card
        const card = favoriteBtn.closest('.service-card-modern');
        if (!card) return;
        
        const serviceId = parseInt(card.dataset.serviceId); // Convertir en nombre
        const isFavorite = favoriteBtn.dataset.favorite === 'true';
        
        console.log('🔍 Toggle favorite pour service ID:', serviceId, 'Type:', typeof serviceId);
        
        try {
            // Désactiver le bouton temporairement
            favoriteBtn.disabled = true;
            favoriteBtn.style.opacity = '0.5';
            
            if (isFavorite) {
                // Retirer des favoris
                const response = await fetch('/smm/api/favorites/remove.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ service_id: serviceId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    favoriteBtn.dataset.favorite = 'false';
                    favoriteBtn.classList.remove('active');
                    favoriteBtn.querySelector('i').className = 'far fa-star';
                    favoriteBtn.title = 'Add to favorites';
                    
                    // Retirer de la variable globale
                    window.userFavoritesIds.delete(serviceId);
                    
                    console.log('✅ Removed from favorites');
                } else {
                    throw new Error(data.message || 'Failed to remove favorite');
                }
            } else {
                // Ajouter aux favoris
                const response = await fetch('/smm/api/favorites/add.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ service_id: serviceId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    favoriteBtn.dataset.favorite = 'true';
                    favoriteBtn.classList.add('active');
                    favoriteBtn.querySelector('i').className = 'fas fa-star';
                    favoriteBtn.title = 'Remove from favorites';
                    
                    // Ajouter à la variable globale
                    window.userFavoritesIds.add(serviceId);
                    
                    // Animation
                    favoriteBtn.style.transform = 'scale(1.3) rotate(15deg)';
                    setTimeout(() => {
                        favoriteBtn.style.transform = '';
                    }, 200);
                    
                    console.log('⭐ Added to favorites');
                } else {
                    if (data.already_exists) {
                        // Déjà en favoris, synchroniser UI
                        favoriteBtn.dataset.favorite = 'true';
                        favoriteBtn.classList.add('active');
                        favoriteBtn.querySelector('i').className = 'fas fa-star';
                    } else {
                        throw new Error(data.message || 'Failed to add favorite');
                    }
                }
            }
        } catch (error) {
            console.error('❌ Favorite error:', error);
            alert(error.message || 'An error occurred. Please try again.');
        } finally {
            // Réactiver le bouton
            favoriteBtn.disabled = false;
            favoriteBtn.style.opacity = '1';
        }
    });
    
    // ========================================
    // FAVORITES SYSTEM - Variable globale pour stocker les IDs
    // ========================================
    window.userFavoritesIds = new Set();
    
    // Debounce pour éviter les appels multiples
    let favoritesLoadTimeout = null;
    
    // Charger l'état des favoris au chargement de la page
    async function loadFavoritesState() {
        // Annuler tout timeout en attente
        if (favoritesLoadTimeout) {
            clearTimeout(favoritesLoadTimeout);
        }
        
        // Debounce de 100ms pour éviter les appels multiples
        favoritesLoadTimeout = setTimeout(async () => {
            try {
                console.log('🔄 Loading favorites state...');
                const response = await fetch('/smm/api/favorites/list.php');
                const data = await response.json();
                
                console.log('📊 API Response:', data);
                
                if (data.success && data.favorites) {
                    // Stocker les IDs des favoris dans la variable globale
                    window.userFavoritesIds.clear();
                    data.favorites.forEach(fav => {
                        // ✅ L'API retourne fav.service.id (pas fav.service_id)
                        const serviceIdNum = parseInt(fav.service.id);
                        console.log('🔍 Ajout favori ID:', serviceIdNum, 'Type:', typeof serviceIdNum);
                        window.userFavoritesIds.add(serviceIdNum);
                    });
                    
                    console.log(`⭐ Stored ${window.userFavoritesIds.size} favorite IDs:`, Array.from(window.userFavoritesIds));
                
                let markedCount = 0;
                const totalCards = document.querySelectorAll('[data-service-id]').length;
                
                console.log(`📋 Services in DOM: ${totalCards}, Favorites to mark: ${data.favorites.length}`);
                
                // Marquer les services favoris dans la page
                data.favorites.forEach(fav => {
                    const serviceId = fav.service.id;
                    const card = document.querySelector(`[data-service-id="${serviceId}"]`);
                    
                    console.log(`  Looking for service #${serviceId}:`, card ? '✅ Found' : '❌ Not found');
                    
                    if (card) {
                        const favoriteBtn = card.querySelector('.service-favorite-btn');
                        if (favoriteBtn) {
                            favoriteBtn.dataset.favorite = 'true';
                            favoriteBtn.classList.add('active');
                            const icon = favoriteBtn.querySelector('i');
                            if (icon) {
                                icon.className = 'fas fa-star';
                            }
                            favoriteBtn.title = 'Remove from favorites';
                            markedCount++;
                            console.log(`    ⭐ Marked as favorite`);
                        } else {
                            console.log(`    ❌ Button not found in card`);
                        }
                    }
                });
                
                    console.log(`⭐ Loaded ${data.favorites.length} favorites (${markedCount} marked in page)`);
                    
                    // Si pas tous marqués ET qu'il y a des cards dans le DOM, réessayer
                    if (markedCount < data.favorites.length && totalCards > 0) {
                        console.log('🔄 Some favorites not marked, will retry when more services load...');
                    } else if (markedCount === 0 && data.favorites.length > 0) {
                        console.log('⚠️ No services in DOM yet, retrying in 1s...');
                        setTimeout(loadFavoritesState, 1000);
                    }
                }
            } catch (error) {
                console.error('❌ Failed to load favorites state:', error);
            }
        }, 100); // Debounce de 100ms
    }
    
    // Charger l'état après un délai pour laisser les services charger
    setTimeout(loadFavoritesState, 1500);
    
    // Aussi charger quand de nouveaux services sont ajoutés (infinite scroll)
    window.addEventListener('servicesLoaded', (e) => {
        console.log('📢 Event servicesLoaded received:', e.detail);
        loadFavoritesState();
    });
</script>

<!-- ========================================
     MODAL DE COMMANDE
     ======================================== -->
<!-- Le CSS est déjà inclus en haut -->
<!-- Configuration des icônes centralisée -->
<script src="../assets/js/icons-config.js?v=<?php echo time(); ?>"></script>
<!-- Gestionnaire Modal de Commande -->
<script src="js/order-modal.js?v=<?php echo time(); ?>"></script>

<script>
/**
 * INITIALISATION MODAL & BOUTONS COMMANDE
 * 
 * Gère l'ouverture du modal et l'interaction avec les boutons Buy
 */
// ========================================

// Initialize modal when DOM is ready
let orderModal;

// Fix sticky pour mobile
function initMobileStickyFix() {
    const filtersBar = document.querySelector('.services-filters-compact');
    if (!filtersBar) return;
    
    // Détecter mobile
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        let ticking = false;
        
        function updatePosition() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const headerHeight = 70; // Hauteur header fixe
            
            // Si sticky natif ne fonctionne pas, utiliser position fixed
            if (!CSS.supports('position', 'sticky')) {
                if (scrollTop > 100) {
                    filtersBar.style.position = 'fixed';
                    filtersBar.style.top = headerHeight + 'px';
                    filtersBar.style.left = '0';
                    filtersBar.style.right = '0';
                } else {
                    filtersBar.style.position = 'relative';
                    filtersBar.style.top = 'auto';
                }
            }
            
            ticking = false;
        }
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updatePosition);
                ticking = true;
            }
        });
        
        console.log('🔧 Mobile sticky fix initialized');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Initializing Order Modal...');
    
    // Initialize mobile sticky fix
    initMobileStickyFix();
    
    // Create modal instance
    orderModal = new OrderModal('orderModal');
    console.log('✅ Order Modal initialized');
    
    // Check if URL has ?service=X parameter
    const urlParams = new URLSearchParams(window.location.search);
    const serviceIdFromURL = urlParams.get('service');
    
    if (serviceIdFromURL) {
        console.log(`🔗 URL parameter detected: service=${serviceIdFromURL}`);
        
        // Wait for services to load, then open modal
        const checkServiceLoaded = setInterval(() => {
            const card = document.querySelector(`[data-service-id="${serviceIdFromURL}"]`);
            if (card) {
                console.log('✅ Service card found, opening modal...');
                clearInterval(checkServiceLoaded);
                
                // Extract service data from card
                const serviceData = {
                    id: card.dataset.serviceId,
                    provider_id: card.dataset.providerId,
                    platform: card.querySelector('.platform-name')?.textContent.trim() || '',
                    name: card.querySelector('.service-card-title')?.textContent.trim() || '',
                    description: card.dataset.description || '',
                    location: card.dataset.location || '',
                    price: parseFloat(card.dataset.price || 0),
                    min_quantity: parseInt(card.dataset.minQuantity || 1000),
                    max_quantity: parseInt(card.dataset.maxQuantity || 10000),
                    tier: card.dataset.tier || '',
                    quality: card.dataset.quality || '',
                    refill_days: card.dataset.refillDays || '0',
                    refill_type: card.dataset.refillType || 'No Refill',
                    drop_rate: card.dataset.dropRate || 'No Drop',
                    speed: card.dataset.speed || '',
                    average_time: card.dataset.averageTime || '',
                    dripfeed: card.dataset.dripfeed === '1' || card.dataset.dripfeed === 'true',
                    cancel: card.dataset.cancel === '1' || card.dataset.cancel === 'true'
                };
                
                // Open modal with service data
                orderModal.open(serviceData, true);
            }
        }, 100);
        
        // Timeout after 5 seconds
        setTimeout(() => {
            clearInterval(checkServiceLoaded);
            console.warn('⚠️ Timeout: Service not found after 5s');
        }, 5000);
    }
});

// ========================================
// BUY BUTTON CLICK HANDLER (Event Delegation)
// ========================================

console.log('🎯 Setting up Buy button click handler...');

document.addEventListener('click', (e) => {
    // Debug: log every click
    if (e.target.classList.contains('service-order-btn') || e.target.closest('.service-order-btn')) {
        console.log('🔍 Click detected on Buy button or its child');
    }
    
    const buyBtn = e.target.closest('.service-order-btn');
    if (!buyBtn) return;
    
    console.log('✅ Buy button found:', buyBtn);
    
    e.preventDefault();
    e.stopPropagation();
    
    console.log('🛒 Buy button clicked - preventDefault called');
    
    // Get service card
    const card = buyBtn.closest('.service-card-modern');
    if (!card) {
        console.error('❌ Service card not found');
        console.log('Button HTML:', buyBtn.outerHTML);
        return;
    }
    
    const serviceId = card.dataset.serviceId;
    console.log(`📦 Opening modal for service ID: ${serviceId}`);
    console.log('Card dataset:', card.dataset);
    
    // Extract service data from card
    const serviceData = {
        id: card.dataset.serviceId,
        provider_id: card.dataset.providerId,
        platform: card.querySelector('.platform-name')?.textContent.trim() || '',
        name: card.querySelector('.service-card-title')?.textContent.trim() || '',
        description: card.dataset.description || '',
        location: card.dataset.location || '',
        price: parseFloat(card.dataset.price || 0),
        min_quantity: parseInt(card.dataset.minQuantity || 1000),
        max_quantity: parseInt(card.dataset.maxQuantity || 10000),
        tier: card.dataset.tier || '',
        quality: card.dataset.quality || '',
        refill_days: card.dataset.refillDays || '0',
        refill_type: card.dataset.refillType || 'No Refill',
        drop_rate: card.dataset.dropRate || 'No Drop',
        speed: card.dataset.speed || '',
        average_time: card.dataset.averageTime || '',
        dripfeed: card.dataset.dripfeed === '1' || card.dataset.dripfeed === 'true',
        cancel: card.dataset.cancel === '1' || card.dataset.cancel === 'true'
    };
    
    console.log('📋 Service data extracted:', serviceData);
    
    // Check if modal exists
    console.log('Modal exists?', typeof orderModal !== 'undefined');
    console.log('Modal instance:', orderModal);
    
    // Open modal
    if (orderModal) {
        console.log('🚀 Calling orderModal.open()...');
        orderModal.open(serviceData);
        console.log('✅ Modal opened');
    } else {
        console.error('❌ Order modal not initialized');
    }
});

console.log('✅ Buy button click handler setup complete');
</script>

</div> <!-- Fin container-fluid -->

<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>
