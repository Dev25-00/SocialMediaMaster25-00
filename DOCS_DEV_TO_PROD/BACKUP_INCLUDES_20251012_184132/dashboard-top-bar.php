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
        <a href="<?php echo SITE_URL; ?>/dashboard/balance.php" class="top-bar-balance">
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
        
        <!-- Menu utilisateur -->
        <div class="top-bar-user">
            <button class="user-avatar-btn" id="userMenuBtn">
                <i class="fa-solid fa-user-circle"></i>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <a href="<?php echo SITE_URL; ?>/dashboard/profile.php">
                    <i class="fa-solid fa-user"></i> Mon Profil
                </a>
                <a href="<?php echo SITE_URL; ?>/dashboard/settings.php">
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
    z-index: 100;
    /* Gradient bleu identique au sidebar */
    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #1e3a8a 100%);
    background-size: 300% 100%;
    animation: metallicShine 3s ease-in-out infinite;
    padding: 0 16px;
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
    overflow: hidden;
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
    z-index: 1;
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
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
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
@media (max-width: 1024px) {
    /* Hamburger visible sur tablet et mobile */
    .hamburger-btn {
        display: flex;
    }
    
    .top-bar-global {
        padding: 0 16px;
        height: 60px;
    }
    
    .page-title-bar {
        font-size: 18px;
    }
}

@media (max-width: 768px) {
    .top-bar-global {
        padding: 0 12px;
        height: 56px;
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
    
    .top-bar-btn {
        width: 36px;
        height: 36px;
    }
    
    .top-bar-btn i {
        font-size: 16px;
    }
    
    /* Masquer le texte "Solde" sur mobile, garder seulement le montant */
    .top-bar-balance i {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .top-bar-global {
        padding: 0 8px;
    }
    
    .hamburger-btn {
        padding: 6px;
    }
    
    .page-title-bar {
        font-size: 15px;
    }
    
    /* Cache certains boutons sur très petit écran */
    #notificationsBtn {
        display: none;
    }
    
    .top-bar-balance {
        padding: 4px 10px;
    }
    }
}
</style>

<script>
// Toggle user menu dropdown
document.addEventListener('DOMContentLoaded', function() {
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
    }
});
</script>
