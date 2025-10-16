<!-- TOP BAR GLOBAL - Header minimaliste avec hamburger intégré -->
<div class="top-bar-global">
    <div class="top-bar-left">
        <!-- Bouton Hamburger (visible sur mobile uniquement) -->
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        <!-- Titre de la page -->
        <?php if (isset($page_title_bar)): ?>
            <h1 class="page-title-bar"><?php echo clean($page_title_bar); ?></h1>
        <?php endif; ?>
    </div>
    
    <!-- Droite: Balance & Actions -->
    <div class="top-bar-right">
        <!-- Badge Balance avec bouton + intégré -->
        <a href="<?php echo SITE_URL; ?>/dashboard/finances/balance.php" class="top-bar-balance">
            <i class="fa-solid fa-wallet"></i>
            <span class="balance-amount"><?php echo formatCurrency($user['balance'] ?? 0); ?></span>
            <span class="balance-add-btn">
                <i class="fa-solid fa-plus"></i>
            </span>
        </a>
        
        <!-- Notifications (optionnel) -->
        <button class="top-bar-btn" id="notificationsBtn">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>
        
        <!-- Widget Multi-langue v3.0 FINAL -->
        <?php include __DIR__ . '/../widgets/google-translate-widget-v3-final.php'; ?>
        
        <!-- Menu utilisateur -->
        <div class="top-bar-user">
            <button class="user-avatar-btn" id="userMenuBtn">
                <i class="fa-solid fa-user-circle"></i>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <a href="<?php echo SITE_URL; ?>/dashboard/account/profile.php">
                    <i class="fa-solid fa-user"></i> Mon Profil
                </a>
                <a href="<?php echo SITE_URL; ?>/dashboard/account/settings.php">
                    <i class="fa-solid fa-cog"></i> Paramètres
                </a>
                <hr>
                <a href="<?php echo SITE_URL; ?>/auth/logout.php">
                    <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   TOP BAR GLOBAL - Styles optimisés avec hamburger intégré
   ============================================ */

.top-bar-global {
    position: sticky;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 10000; /* AU-DESSUS des filtres (998) - CRITIQUE */
    /* Gradient bleu identique au sidebar */
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1e3a8a 100%);
    background-size: 300% 100%;
    animation: metallicShine 3s ease-in-out infinite;
    padding: 0 16px;
    margin: 0; /* Pas de marge */
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    /* Box shadows prononcées pour effet chromé 3D */
    box-shadow: 
        0 8px 16px rgba(30, 58, 138, 0.4),
        0 4px 8px rgba(30, 64, 175, 0.3),
        inset 0 2px 4px rgba(255, 255, 255, 0.3),
        inset 0 -2px 4px rgba(0, 0, 0, 0.2);
    border-bottom: 3px solid rgba(96, 165, 250, 0.5);
    overflow: visible; /* CRITICAL: permet aux dropdowns de s'afficher en dehors */
}

/* Effet miroir/reflet lumineux qui traverse le header */
.top-bar-global::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 80px;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.8) 50%, 
        transparent 100%);
    transform: skewX(-20deg);
    animation: shineEffect 3s ease-in-out infinite;
    z-index: 1; /* En dessous des éléments interactifs */
    pointer-events: none;
}

/* Effet de lueur diffuse qui pulse */
.top-bar-global::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, 
        rgba(96, 165, 250, 0.3) 0%, 
        transparent 70%);
    animation: glowPulse 4s ease-in-out infinite;
    pointer-events: none;
    z-index: 1; /* En dessous des éléments interactifs */
}

@keyframes metallicShine {
    0%, 100% {
        background-position: 0% 50%;
        box-shadow: 
            0 8px 16px rgba(30, 58, 138, 0.4),
            0 4px 8px rgba(30, 64, 175, 0.3),
            inset 0 2px 4px rgba(255, 255, 255, 0.3),
            inset 0 -2px 4px rgba(0, 0, 0, 0.2);
    }
    50% {
        background-position: 100% 50%;
        box-shadow: 
            0 12px 24px rgba(30, 58, 138, 0.5),
            0 6px 12px rgba(30, 64, 175, 0.4),
            inset 0 2px 4px rgba(255, 255, 255, 0.5),
            inset 0 -2px 4px rgba(0, 0, 0, 0.3);
    }
}

@keyframes shineEffect {
    0% {
        left: -100%;
        opacity: 0;
    }
    20% {
        opacity: 1;
    }
    50% {
        left: 100%;
        opacity: 1;
    }
    100% {
        left: 100%;
        opacity: 0;
    }
}

@keyframes glowPulse {
    0%, 100% {
        opacity: 0.3;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.1);
    }
}

.top-bar-left {
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    z-index: 1;
}

/* Bouton Hamburger - Masqué par défaut, visible sur mobile */
.hamburger-btn {
    display: none;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.hamburger-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    transform: scale(1.05);
}

.hamburger-btn i {
    display: block;
}

/* Titre de la page - Blanc sur fond bleu, compact */
.page-title-bar {
    font-size: 17px;
    font-weight: 700;
    color: white;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.top-bar-right {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 10; /* Au-dessus des effets ::before/::after mais sous les dropdowns */
}

/* Balance Badge avec bouton + intégré - Version compacte */
.top-bar-balance {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px 6px 12px;
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.95) 0%, 
        rgba(239, 246, 255, 0.95) 50%,
        rgba(255, 255, 255, 0.95) 100%);
    background-size: 200% 100%;
    border: 2px solid rgba(255, 255, 255, 0.8);
    border-radius: 20px;
    color: #1e40af;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 
        0 4px 12px rgba(0, 0, 0, 0.3),
        0 2px 6px rgba(96, 165, 250, 0.4),
        inset 0 1px 2px rgba(255, 255, 255, 1);
    position: relative;
    overflow: hidden;
    animation: balancePulse 3s ease-in-out infinite;
}

@keyframes balancePulse {
    0%, 100% {
        background-position: 0% 50%;
        box-shadow: 
            0 4px 12px rgba(0, 0, 0, 0.3),
            0 2px 6px rgba(96, 165, 250, 0.4),
            inset 0 1px 2px rgba(255, 255, 255, 1);
    }
    50% {
        background-position: 100% 50%;
        box-shadow: 
            0 6px 16px rgba(0, 0, 0, 0.4),
            0 4px 12px rgba(96, 165, 250, 0.6),
            inset 0 1px 2px rgba(255, 255, 255, 1);
    }
}

/* Reflet lumineux qui traverse le badge */
.top-bar-balance::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.9) 50%, 
        transparent 100%);
    animation: balanceShine 4s ease-in-out infinite;
    transform: skewX(-20deg);
}

@keyframes balanceShine {
    0%, 30% { 
        left: -100%; 
        opacity: 0;
    }
    40% {
        opacity: 1;
    }
    60% { 
        left: 120%; 
        opacity: 1;
    }
    100% { 
        left: 120%; 
        opacity: 0;
    }
}

.top-bar-balance:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 
        0 8px 20px rgba(0, 0, 0, 0.4),
        0 4px 12px rgba(96, 165, 250, 0.6),
        inset 0 1px 2px rgba(255, 255, 255, 1);
    border-color: rgba(96, 165, 250, 1);
}

.top-bar-balance i {
    font-size: 14px;
    position: relative;
    z-index: 1;
    color: #1e40af;
}

.balance-amount {
    font-size: 14px;
    position: relative;
    z-index: 1;
    color: #1e3a8a;
}

/* Bouton + intégré dans le badge - Version compacte */
.balance-add-btn {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
    box-shadow: 
        0 4px 8px rgba(16, 185, 129, 0.5),
        inset 0 1px 2px rgba(255, 255, 255, 0.3);
    animation: plusPulse 2s ease-in-out infinite;
}

@keyframes plusPulse {
    0%, 100% {
        box-shadow: 
            0 4px 8px rgba(16, 185, 129, 0.5),
            inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }
    50% {
        box-shadow: 
            0 6px 12px rgba(16, 185, 129, 0.7),
            0 0 20px rgba(16, 185, 129, 0.4),
            inset 0 1px 2px rgba(255, 255, 255, 0.5);
    }
}

.top-bar-balance:hover .balance-add-btn {
    transform: scale(1.2) rotate(90deg);
    box-shadow: 
        0 6px 16px rgba(16, 185, 129, 0.7),
        0 0 25px rgba(16, 185, 129, 0.5),
        inset 0 1px 2px rgba(255, 255, 255, 0.5);
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.balance-add-btn i {
    font-size: 12px;
    color: white;
}

/* Boutons - Style lumineux compact */
.top-bar-btn {
    position: relative;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 
        0 2px 4px rgba(0, 0, 0, 0.2),
        inset 0 1px 1px rgba(255, 255, 255, 0.2);
}

.top-bar-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px) scale(1.05);
    box-shadow: 
        0 4px 8px rgba(0, 0, 0, 0.3),
        0 0 15px rgba(255, 255, 255, 0.2),
        inset 0 1px 1px rgba(255, 255, 255, 0.3);
}

.top-bar-btn i {
    font-size: 18px;
}

/* Notification badge - Plus visible */
.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    box-shadow: 
        0 2px 6px rgba(239, 68, 68, 0.6),
        0 0 10px rgba(239, 68, 68, 0.4);
    animation: notificationPulse 2s ease-in-out infinite;
}

@keyframes notificationPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 
            0 2px 6px rgba(239, 68, 68, 0.6),
            0 0 10px rgba(239, 68, 68, 0.4);
    }
    50% {
        transform: scale(1.1);
        box-shadow: 
            0 4px 10px rgba(239, 68, 68, 0.8),
            0 0 20px rgba(239, 68, 68, 0.6);
    }
}

/* User Menu - Compact */
.top-bar-user {
    position: relative;
    z-index: 10000; /* Z-index élevé pour être au-dessus du widget traduction */
}

.user-avatar-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.9) 0%, 
        rgba(239, 246, 255, 0.9) 100%);
    border: 2px solid rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    color: #1e40af;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 
        0 4px 8px rgba(0, 0, 0, 0.3),
        inset 0 1px 2px rgba(255, 255, 255, 1);
}

.user-avatar-btn:hover {
    transform: scale(1.1);
    box-shadow: 
        0 6px 12px rgba(0, 0, 0, 0.4),
        0 0 20px rgba(255, 255, 255, 0.6),
        inset 0 1px 2px rgba(255, 255, 255, 1);
    border-color: white;
}

.user-avatar-btn i {
    font-size: 20px;
}

.user-dropdown {
    position: fixed; /* CHANGED: fixed pour échapper au stacking context */
    top: 64px; /* Position sous le header (56px + 8px margin) */
    right: 16px; /* Aligné à droite comme le bouton */
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 999999999; /* SAME as language dropdown - 999 millions */
}

.user-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.user-dropdown a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.2s ease;
}

.user-dropdown a:hover {
    background: #f3f4f6;
    color: #111827;
}

.user-dropdown hr {
    margin: 8px 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}

/* Responsive - Top Bar */
/* Tablette (1024px-769px) - Hamburger MASQUÉ */
@media (max-width: 1024px) and (min-width: 769px) {
    /* Hamburger MASQUÉ sur tablette (sidebar visible) */
    .hamburger-btn {
        display: none !important;
    }
    
    .top-bar-global {
        padding: 0 16px;
        height: 60px;
    }
    
    .page-title-bar {
        font-size: 18px;
    }
}

/* Mobile (768px et moins) - Hamburger VISIBLE */
@media (max-width: 768px) {
    /* Hamburger VISIBLE sur mobile (sidebar masquée) */
    .hamburger-btn {
        display: flex !important;
    }
    
    .top-bar-global {
        padding: 0 12px;
        height: 56px;
        /* CRITICAL: Maintenir sticky sur mobile */
        position: sticky !important;
        top: 0 !important;
        z-index: 10000 !important;
    }
    
    .page-title-bar {
        font-size: 16px;
    }
    
    .top-bar-balance {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .balance-amount {
        font-size: 14px;
    }
    
    .balance-add-btn {
        width: 22px;
        height: 22px;
    }
    
    .balance-add-btn i {
        font-size: 11px;
    }
    
    .top-bar-btn {
        width: 36px;
        height: 36px;
    }
    
    .top-bar-btn i {
        font-size: 16px;
    }
}

/* Mobile très petit (375px) - Optimisation maximale */
@media (max-width: 480px) {
    .top-bar-global {
        padding: 0 6px; /* Réduit de 8px à 6px */
        height: 48px; /* Réduit de 52px à 48px */
        /* CRITICAL: Maintenir sticky sur petit mobile */
        position: sticky !important;
        top: 0 !important;
        z-index: 10000 !important;
    }
    
    .hamburger-btn {
        padding: 4px; /* Réduit de 6px à 4px */
        font-size: 18px; /* Réduit de 20px à 18px */
        width: 36px;
        height: 36px;
    }
    
    .page-title-bar {
        font-size: 13px; /* Réduit de 14px à 13px */
        max-width: 80px; /* Limite largeur titre */
        overflow: hidden;
        white-space: nowrap;
    }
    
    /* Cache certains boutons sur très petit écran */
    #notificationsBtn {
        display: none !important;
    }
    
    .top-bar-balance {
        padding: 3px 6px; /* Réduit encore */
        font-size: 10px; /* Plus petit */
        min-width: auto;
    }
    
    .balance-amount {
        font-size: 11px; /* Réduit de 12px à 11px */
    }
    
    .balance-add-btn {
        width: 16px; /* Réduit de 18px à 16px */
        height: 16px;
    }
    
    .balance-add-btn i {
        font-size: 8px; /* Réduit de 9px à 8px */
    }
    
    /* Widget traduction plus compact */
    .smm-translate-btn {
        padding: 6px 10px !important; /* Très compact */
        font-size: 11px !important;
        height: 32px !important;
    }
    
    .smm-translate-icon {
        font-size: 14px !important;
    }
    
    /* Boutons génériques plus petits */
    .top-bar-btn {
        width: 30px; /* Réduit de 32px à 30px */
        height: 30px;
    }
    
    .top-bar-btn i {
        font-size: 12px; /* Réduit de 14px à 12px */
    }
    
    /* Icône utilisateur plus petite */
    .user-avatar-btn {
        width: 30px !important; /* Réduit de 32px à 30px */
        height: 30px !important;
        font-size: 16px !important; /* Réduit de 18px à 16px */
    }
    
    .user-avatar-btn i {
        font-size: 16px !important;
    }
    
    /* Gap ultra-réduit entre éléments */
    .top-bar-right {
        gap: 4px !important; /* Réduit de 8px à 4px */
    }
    
    .top-bar-left {
        gap: 6px !important;
    }
}

/* Mobile ultra-petit (360px et moins) - Cas extrême */
@media (max-width: 375px) {
    .top-bar-global {
        padding: 0 4px; /* Padding minimal */
        /* CRITICAL: Maintenir sticky sur ultra-petit mobile */
        position: sticky !important;
        top: 0 !important;
        z-index: 10000 !important;
    }
    
    .page-title-bar {
        display: none; /* Masquer titre si vraiment trop petit */
    }
    
    .top-bar-balance {
        font-size: 9px;
        padding: 2px 4px;
    }
    
    .balance-amount {
        font-size: 10px;
    }
}
</style>

<script>
/**
 * SMM Mastery - Top Bar Interactive Script
 * Gestion des dropdowns: Menu Utilisateur + Widget Traduction
 * @version 1.0
 * @date 14/10/2025
 */

(function() {
    'use strict';
    
    console.log('[Top Bar] Initialisation des dropdowns...');
    
    // === MENU UTILISATEUR ===
    function initUserMenu() {
        const userBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        
        if (!userBtn || !userDropdown) {
            console.warn('[Top Bar] Menu utilisateur non trouvé');
            return;
        }
        
        console.log('[Top Bar] Menu utilisateur détecté ✅');
        
        // Toggle dropdown au clic
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Fermer widget traduction s'il est ouvert
            const translateDropdown = document.getElementById('smmTranslateDropdown');
            if (translateDropdown && translateDropdown.style.display === 'block') {
                if (typeof debugToggleDropdown === 'function') {
                    debugToggleDropdown();
                }
            }
            
            // Toggle menu utilisateur
            const isActive = userDropdown.classList.contains('active');
            userDropdown.classList.toggle('active');
            
            // Si ouverture, calculer position dynamique (position fixed)
            if (!isActive) {
                const btnRect = userBtn.getBoundingClientRect();
                userDropdown.style.top = (btnRect.bottom + 8) + 'px';
                userDropdown.style.right = (window.innerWidth - btnRect.right) + 'px';
                console.log('[Top Bar] User dropdown position:', {
                    top: btnRect.bottom + 8,
                    right: window.innerWidth - btnRect.right
                });
            }
            
            console.log('[Top Bar] Menu utilisateur:', userDropdown.classList.contains('active') ? 'ouvert' : 'fermé');
        });
        
        // Fermer si clic extérieur
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                if (userDropdown.classList.contains('active')) {
                    userDropdown.classList.remove('active');
                    console.log('[Top Bar] Menu utilisateur fermé (clic extérieur)');
                }
            }
        });
        
        // Fermer avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
                console.log('[Top Bar] Menu utilisateur fermé (ESC)');
            }
        });
        
        // Repositionner sur scroll (pour position fixed)
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (!userDropdown.classList.contains('active')) return;
            
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const btnRect = userBtn.getBoundingClientRect();
                userDropdown.style.top = (btnRect.bottom + 8) + 'px';
                userDropdown.style.right = (window.innerWidth - btnRect.right) + 'px';
            }, 10);
        });
        
        // Repositionner sur resize
        window.addEventListener('resize', function() {
            if (!userDropdown.classList.contains('active')) return;
            
            const btnRect = userBtn.getBoundingClientRect();
            userDropdown.style.top = (btnRect.bottom + 8) + 'px';
            userDropdown.style.right = (window.innerWidth - btnRect.right) + 'px';
        });
    }
    
    // === AMÉLIORATION WIDGET TRADUCTION ===
    // Fermer le widget traduction si on ouvre le menu utilisateur
    window.addEventListener('click', function(e) {
        const userBtn = document.getElementById('userMenuBtn');
        const translateBtn = document.getElementById('smmTranslateBtn');
        const translateDropdown = document.getElementById('smmTranslateDropdown');
        
        if (userBtn && userBtn.contains(e.target) && translateDropdown && translateDropdown.style.display === 'block') {
            if (typeof debugToggleDropdown === 'function') {
                debugToggleDropdown();
            }
        }
    });
    
    // === INITIALISATION ===
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initUserMenu();
            console.log('[Top Bar] Dropdowns initialisés ✅');
        });
    } else {
        initUserMenu();
        console.log('[Top Bar] Dropdowns initialisés ✅');
    }
    
})();
</script>
