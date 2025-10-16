<?php
/**
 * Configuration des icônes professionnelles
 * Utilise Font Awesome 6 (CDN gratuit)
 * Remplace tous les emojis par des icônes animées
 */

// CDN Font Awesome (à inclure dans <head>)
define('ICON_CDN', '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />');

/**
 * Mapping emojis → Font Awesome icons
 * Chaque icône a une classe de base + classe d'animation optionnelle
 */
$ICON_MAP = [
    // Tiers de services
    'budget' => '<i class="fas fa-piggy-bank icon-budget"></i>',
    'standard' => '<i class="fas fa-star icon-standard"></i>',
    'premium' => '<i class="fas fa-gem icon-premium"></i>',
    'ultimate' => '<i class="fas fa-crown icon-ultimate"></i>',
    
    // Statuts
    'success' => '<i class="fas fa-check-circle icon-success"></i>',
    'error' => '<i class="fas fa-times-circle icon-error"></i>',
    'warning' => '<i class="fas fa-exclamation-triangle icon-warning"></i>',
    'info' => '<i class="fas fa-info-circle icon-info"></i>',
    'pending' => '<i class="fas fa-clock icon-pending"></i>',
    
    // Actions
    'add' => '<i class="fas fa-plus-circle icon-add"></i>',
    'edit' => '<i class="fas fa-edit icon-edit"></i>',
    'delete' => '<i class="fas fa-trash-alt icon-delete"></i>',
    'view' => '<i class="fas fa-eye icon-view"></i>',
    'download' => '<i class="fas fa-download icon-download"></i>',
    'upload' => '<i class="fas fa-upload icon-upload"></i>',
    
    // Navigation
    'home' => '<i class="fas fa-home icon-nav"></i>',
    'dashboard' => '<i class="fas fa-tachometer-alt icon-nav"></i>',
    'services' => '<i class="fas fa-shopping-bag icon-nav"></i>',
    'orders' => '<i class="fas fa-shopping-cart icon-nav"></i>',
    'balance' => '<i class="fas fa-wallet icon-nav"></i>',
    'support' => '<i class="fas fa-headset icon-nav"></i>',
    'settings' => '<i class="fas fa-cog icon-nav"></i>',
    'logout' => '<i class="fas fa-sign-out-alt icon-nav"></i>',
    
    // Réseaux sociaux - Principales plateformes
    'instagram' => '<i class="fab fa-instagram icon-social"></i>',
    'youtube' => '<i class="fab fa-youtube icon-social"></i>',
    'tiktok' => '<i class="fab fa-tiktok icon-social"></i>',
    'facebook' => '<i class="fab fa-facebook icon-social"></i>',
    'twitter' => '<i class="fab fa-twitter icon-social"></i>',
    'linkedin' => '<i class="fab fa-linkedin icon-social"></i>',
    'spotify' => '<i class="fab fa-spotify icon-social"></i>',
    'snapchat' => '<i class="fab fa-snapchat icon-social"></i>',
    'twitch' => '<i class="fab fa-twitch icon-social"></i>',
    'discord' => '<i class="fab fa-discord icon-social"></i>',
    'reddit' => '<i class="fab fa-reddit icon-social"></i>',
    'pinterest' => '<i class="fab fa-pinterest icon-social"></i>',
    
    // ✅ NOUVELLES PLATEFORMES V2 (13/10/2025)
    'play' => '<i class="fas fa-play-circle icon-social"></i>',        // Kick, Rutube
    'video' => '<i class="fas fa-video icon-social"></i>',             // Rumble
    'cloud' => '<i class="fas fa-cloud icon-social"></i>',             // BlueSky
    'film' => '<i class="fas fa-film icon-social"></i>',               // Kwai
    'bullhorn' => '<i class="fas fa-bullhorn icon-social"></i>',       // Truth Social
    'headphones' => '<i class="fas fa-headphones icon-social"></i>',   // Audiomack
    'quora' => '<i class="fab fa-quora icon-social"></i>',             // Quora
    'tumblr' => '<i class="fab fa-tumblr icon-social"></i>',           // Tumblr
    'soundcloud' => '<i class="fab fa-soundcloud icon-social"></i>',   // SoundCloud
    'medium' => '<i class="fab fa-medium icon-social"></i>',           // Medium
    'apple' => '<i class="fab fa-apple icon-social"></i>',             // Apple Music
    'broadcast' => '<i class="fas fa-broadcast-tower icon-social"></i>', // Chzzk
    'square' => '<i class="fas fa-square icon-social"></i>',           // Square
    'globe' => '<i class="fas fa-globe icon-social"></i>',             // Website
    'mobile' => '<i class="fas fa-mobile-alt icon-social"></i>',       // Mobile
    'globe-americas' => '<i class="fas fa-globe-americas icon-social"></i>', // Worldwide
    'music' => '<i class="fas fa-music icon-social"></i>',             // Music platforms
    'camera' => '<i class="fas fa-camera icon-social"></i>',           // Photo platforms
    
    // Métriques
    'followers' => '<i class="fas fa-users icon-metric"></i>',
    'likes' => '<i class="fas fa-heart icon-metric"></i>',
    'views' => '<i class="fas fa-eye icon-metric"></i>',
    'comments' => '<i class="fas fa-comments icon-metric"></i>',
    'shares' => '<i class="fas fa-share-alt icon-metric"></i>',
    'subscribers' => '<i class="fas fa-user-plus icon-metric"></i>',
    
    // Finances
    'money' => '<i class="fas fa-dollar-sign icon-money"></i>',
    'wallet' => '<i class="fas fa-wallet icon-money"></i>',
    'card' => '<i class="fas fa-credit-card icon-money"></i>',
    'paypal' => '<i class="fab fa-paypal icon-payment"></i>',
    'stripe' => '<i class="fab fa-stripe icon-payment"></i>',
    'bitcoin' => '<i class="fab fa-bitcoin icon-payment"></i>',
    
    // Autres
    'chart' => '<i class="fas fa-chart-line icon-chart"></i>',
    'stats' => '<i class="fas fa-chart-bar icon-chart"></i>',
    'rocket' => '<i class="fas fa-rocket icon-rocket"></i>',
    'bell' => '<i class="fas fa-bell icon-bell"></i>',
    'mail' => '<i class="fas fa-envelope icon-mail"></i>',
    'phone' => '<i class="fas fa-phone icon-phone"></i>',
    'user' => '<i class="fas fa-user icon-user"></i>',
    'star' => '<i class="fas fa-star icon-star"></i>',
    'shield' => '<i class="fas fa-shield-alt icon-shield"></i>',
    'lock' => '<i class="fas fa-lock icon-lock"></i>',
    'search' => '<i class="fas fa-search icon-search"></i>',
    'filter' => '<i class="fas fa-filter icon-filter"></i>',
    'calendar' => '<i class="fas fa-calendar-alt icon-calendar"></i>',
];

/**
 * Fonction helper pour obtenir une icône
 * 
 * @param string $key Clé de l'icône dans le mapping
 * @param bool $animated Ajouter animation de brillance
 * @param string $size Taille: sm, md, lg, xl
 * @return string HTML de l'icône
 */
function getIcon($key, $animated = false, $size = 'md') {
    global $ICON_MAP;
    
    if (!isset($ICON_MAP[$key])) {
        return '<i class="fas fa-question-circle"></i>'; // Icône par défaut
    }
    
    $icon = $ICON_MAP[$key];
    
    // Ajouter classe d'animation si demandé
    if ($animated) {
        $icon = str_replace('></i>', ' icon-shine></i>', $icon);
    }
    
    // Ajouter classe de taille
    $icon = str_replace('></i>', ' icon-' . $size . '></i>', $icon);
    
    return $icon;
}

/**
 * Fonction pour afficher une icône avec texte
 * 
 * @param string $key Clé de l'icône
 * @param string $text Texte à afficher
 * @param bool $animated Animation brillance
 * @return string HTML complet
 */
function iconText($key, $text, $animated = false) {
    return getIcon($key, $animated) . ' <span class="icon-text">' . htmlspecialchars($text) . '</span>';
}

/**
 * Badges avec icônes pour les tiers de services
 */
function tierBadge($tier) {
    $badges = [
        'budget' => '<span class="tier-badge tier-budget">' . getIcon('budget', true) . ' Budget</span>',
        'standard' => '<span class="tier-badge tier-standard">' . getIcon('standard', true) . ' Standard</span>',
        'premium' => '<span class="tier-badge tier-premium">' . getIcon('premium', true) . ' Premium</span>',
        'ultimate' => '<span class="tier-badge tier-ultimate">' . getIcon('ultimate', true) . ' Ultimate</span>',
    ];
    
    return $badges[strtolower($tier)] ?? $tier;
}

/**
 * Badge de statut avec icône
 */
function statusBadge($status) {
    $statusMap = [
        'pending' => ['icon' => 'pending', 'class' => 'status-pending', 'text' => 'En attente'],
        'processing' => ['icon' => 'info', 'class' => 'status-processing', 'text' => 'En cours'],
        'completed' => ['icon' => 'success', 'class' => 'status-completed', 'text' => 'Terminé'],
        'partial' => ['icon' => 'warning', 'class' => 'status-partial', 'text' => 'Partiel'],
        'canceled' => ['icon' => 'error', 'class' => 'status-canceled', 'text' => 'Annulé'],
        'refunded' => ['icon' => 'error', 'class' => 'status-refunded', 'text' => 'Remboursé'],
    ];
    
    $s = $statusMap[strtolower($status)] ?? ['icon' => 'info', 'class' => 'status-default', 'text' => $status];
    
    return '<span class="status-badge ' . $s['class'] . '">' . getIcon($s['icon']) . ' ' . $s['text'] . '</span>';
}

/**
 * Icône de plateforme sociale
 */
function platformIcon($platform, $withText = true) {
    $platforms = [
        'instagram' => ['key' => 'instagram', 'name' => 'Instagram', 'color' => '#E4405F'],
        'youtube' => ['key' => 'youtube', 'name' => 'YouTube', 'color' => '#FF0000'],
        'tiktok' => ['key' => 'tiktok', 'name' => 'TikTok', 'color' => '#000000'],
        'facebook' => ['key' => 'facebook', 'name' => 'Facebook', 'color' => '#1877F2'],
        'twitter' => ['key' => 'twitter', 'name' => 'Twitter', 'color' => '#1DA1F2'],
        'linkedin' => ['key' => 'linkedin', 'name' => 'LinkedIn', 'color' => '#0A66C2'],
    ];
    
    $p = $platforms[strtolower($platform)] ?? ['key' => 'services', 'name' => $platform, 'color' => '#666'];
    
    $icon = '<span class="platform-icon" style="color: ' . $p['color'] . '">' . getIcon($p['key'], true) . '</span>';
    
    if ($withText) {
        $icon .= ' <span class="platform-name">' . $p['name'] . '</span>';
    }
    
    return $icon;
}
