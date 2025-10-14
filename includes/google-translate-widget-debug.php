<?php
/**
 * GOOGLE TRANSLATE WIDGET - VERSION DEBUG
 * Styles inline ultra-visibles pour debugging
 */

if (!defined('SITE_URL')) {
    die('Accès direct non autorisé');
}
?>

<!-- Widget Multi-langue (Mode Debug désactivé) -->
<div class="smm-translate-wrapper" style="position: relative; display: inline-block; z-index: 99999;">
    
    <!-- Style pour animation globe ET dropdown -->
    <style>
        @keyframes rotateGlobe {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .globe-icon-rotating {
            animation: rotateGlobe 3s linear infinite;
            display: inline-block;
        }
        
        /* Dropdown avec position FIXED pour être AU-DESSUS DE TOUT */
        /* ⚠️ POSITION 100% CALCULÉE EN JAVASCRIPT - PAS DE CSS FORCÉ */
        #smmTranslateDropdown {
            position: fixed !important;
            /* top, left, right calculés dynamiquement en JavaScript */
            display: none;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0;
            width: 320px;
            max-width: calc(100vw - 40px); /* Marge 20px de chaque côté */
            z-index: 999999999 !important; /* 999 millions - AU-DESSUS DES FILTRES */
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
            pointer-events: auto !important;
            /* Empêcher débordement */
            overflow-x: hidden;
            box-sizing: border-box;
        }
        
        /* Mobile - PAS de position forcée, JS gère tout */
        @media (max-width: 768px) {
            #smmTranslateDropdown {
                width: auto !important;
                max-width: calc(100vw - 40px) !important;
            }
        }
    </style>
    
    <!-- Bouton -->
    <button 
        id="smmTranslateBtn" 
        type="button"
        onclick="debugToggleDropdown(event)"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(37, 99, 235, 0.4)'"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(37, 99, 235, 0.3)'"
        style="
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transition: all 0.3s ease;
        ">
        <i class="fas fa-globe globe-icon-rotating"></i>
        <span id="smmCurrentLang">FR</span>
        <i class="fas fa-chevron-down" id="smmChevron" style="font-size: 12px; transition: transform 0.3s;"></i>
    </button>
    
    <!-- Dropdown - Position relative au wrapper -->
    <div id="smmTranslateDropdown">
        
        <div style="background: white; padding: 0; border-radius: 12px;">
            <!-- Header du dropdown -->
            <div style="
                padding: 20px;
                background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
                color: white;
                border-radius: 12px 12px 0 0;
            ">
                <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">🌍 Choisir une langue</h4>
                <p style="margin: 0; font-size: 12px; opacity: 0.9;">Traduction automatique</p>
            </div>
            
            <!-- Barre recherche -->
            <div style="padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                <input 
                    type="text" 
                    id="smmLangSearch"
                    placeholder="🔍 Rechercher une langue..."
                    onkeyup="debugSearch(this.value)"
                    style="
                        width: 100%;
                        padding: 10px 12px;
                        border: 2px solid #e5e7eb;
                        border-radius: 8px;
                        font-size: 14px;
                        box-sizing: border-box;
                        transition: border-color 0.2s;
                    "
                    onfocus="this.style.borderColor='#2563eb'"
                    onblur="this.style.borderColor='#e5e7eb'">
            </div>
            
            <!-- Liste langues -->
            <div id="smmLangList" style="
                max-height: 300px; 
                overflow-y: auto;
                padding: 10px;
            ">
                <!-- Rempli par JS -->
            </div>
        </div>
    </div>
</div>

<!-- Google Translate caché -->
<div id="google_translate_element" style="display: none;"></div>

<script>
console.log('[DEBUG] Script chargé');

// Configuration
const DEBUG_LANGUAGES = [
    { code: 'fr', name: 'Français', flag: '🇫🇷' },
    { code: 'en', name: 'English', flag: '🇬🇧' },
    { code: 'es', name: 'Español', flag: '🇪🇸' },
    { code: 'de', name: 'Deutsch', flag: '🇩🇪' },
    { code: 'it', name: 'Italiano', flag: '🇮🇹' },
    { code: 'ar', name: 'العربية', flag: '🇸🇦' },
    { code: 'zh-CN', name: '中文', flag: '🇨🇳' },
    { code: 'ja', name: '日本語', flag: '🇯🇵' },
];

let debugDropdownOpen = false;
let isUserAction = false; // Flag pour éviter ouvertures automatiques
let isChangingLanguage = false; // Flag pour bloquer pendant changement de langue
let eventListenersAttached = false; // Pour éviter multiple attachements

// Toggle dropdown
function debugToggleDropdown(event) {
    console.log('��� VERSION 3.1 - ' + new Date().toISOString() + ' 🔥🔥🔥');
    console.log('🚨🚨🚨 SI VOUS VOYEZ CE MESSAGE = CACHE VIDÉ ✅ 🚨🚨🚨');
    
    // Bloquer si on est en train de changer de langue
    if (isChangingLanguage) {
        console.log('[SMM Translate] Changement langue en cours, toggle ignoré');
        return;
    }
    
    // Empêcher propagation si c'est un événement
    if (event) {
        event.stopPropagation();
        event.preventDefault();
        isUserAction = true;
    }
    
    console.log('[SMM Translate] Toggle appelé, user action:', isUserAction);
    const dropdown = document.getElementById('smmTranslateDropdown');
    const btn = document.getElementById('smmTranslateBtn');
    
    if (!dropdown || !btn) {
        console.error('[SMM Translate] Éléments manquants!');
        return;
    }
    
    debugDropdownOpen = !debugDropdownOpen;
    console.log('[SMM Translate] État:', debugDropdownOpen);
    
    // Rotation du chevron
    const chevron = document.getElementById('smmChevron');
    if (chevron) {
        chevron.style.transform = debugDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    }
    
    if (debugDropdownOpen) {
        console.log('🎯 CALCUL POSITION - VERSION 3.1 INTELLIGENT');
        
        // Calculer position dynamique pour position FIXED
        const btn = document.getElementById('smmTranslateBtn');
        if (btn) {
            const btnRect = btn.getBoundingClientRect();
            const dropdownWidth = 320;
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;
            const isMobile = screenWidth <= 768;
            
            console.log('📐 Environnement:', {
                screenWidth: screenWidth,
                screenHeight: screenHeight,
                isMobile: isMobile,
                btnLeft: btnRect.left,
                btnRight: btnRect.right,
                btnBottom: btnRect.bottom
            });
            
            // Position verticale - sous le bouton
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            
            // ✅ Position horizontale INTELLIGENTE
            if (isMobile) {
                // Mobile: pleine largeur avec marges 20px
                dropdown.style.left = '20px';
                dropdown.style.right = '20px';
                dropdown.style.width = 'auto';
                dropdown.style.maxWidth = 'calc(100vw - 40px)';
                
                console.log('📱 MOBILE - Position pleine largeur:', {
                    left: '20px',
                    right: '20px',
                    top: dropdown.style.top,
                    width: 'auto'
                });
            } else {
                // Desktop: Logique intelligente anti-débordement
                let calculatedLeft = btnRect.left;
                
                // Vérifier si dropdown déborderait à droite
                if (calculatedLeft + dropdownWidth > screenWidth - 20) {
                    // Si débordement: aligner à droite avec marge de 20px
                    calculatedLeft = screenWidth - dropdownWidth - 20;
                    console.log('⚠️ Ajustement anti-débordement:', {
                        original: btnRect.left,
                        adjusted: calculatedLeft,
                        reason: 'Débordement écran évité'
                    });
                }
                
                // Vérifier aussi débordement à gauche
                if (calculatedLeft < 20) {
                    calculatedLeft = 20;
                    console.log('⚠️ Ajustement gauche:', {
                        adjusted: calculatedLeft,
                        reason: 'Trop proche bord gauche'
                    });
                }
                
                dropdown.style.left = calculatedLeft + 'px';
                dropdown.style.right = 'auto';
                dropdown.style.width = dropdownWidth + 'px';
                dropdown.style.maxWidth = 'calc(100vw - 40px)';
                
                console.log('💻 DESKTOP - Position finale:', {
                    left: dropdown.style.left,
                    right: dropdown.style.right,
                    width: dropdown.style.width,
                    top: dropdown.style.top,
                    btnLeft: btnRect.left,
                    btnRight: btnRect.right,
                    screenWidth: screenWidth,
                    willFit: (calculatedLeft + dropdownWidth <= screenWidth - 20)
                });
                
                // VÉRIFICATION CRITIQUE après 100ms
                setTimeout(() => {
                    const computedStyle = window.getComputedStyle(dropdown);
                    const actualLeft = parseInt(computedStyle.left);
                    const actualWidth = parseInt(computedStyle.width);
                    const willOverflow = (actualLeft + actualWidth) > screenWidth;
                    
                    console.log('⚠️ VÉRIFICATION Position appliquée:', {
                        'Inline left': dropdown.style.left,
                        'Computed left': computedStyle.left,
                        'Computed width': computedStyle.width,
                        'Right edge': actualLeft + actualWidth + 'px',
                        'Screen width': screenWidth + 'px',
                        '🚨 Déborde?': willOverflow ? 'OUI ❌' : 'NON ✅'
                    });
                    
                    if (willOverflow) {
                        console.error('🚨 ATTENTION: Dropdown déborde encore!');
                    }
                }, 100);
            }
        }
        
        // Simple display toggle
        dropdown.style.display = 'block';
        console.log('[SMM Translate] Dropdown affiché');
        
        // Fermer menu utilisateur s'il est ouvert
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown && userDropdown.classList.contains('active')) {
            userDropdown.classList.remove('active');
            console.log('[SMM Translate] Menu utilisateur fermé');
        }
    } else {
        dropdown.style.display = 'none';
        console.log('[SMM Translate] Dropdown caché');
    }
    
    // Reset flag après un court délai
    setTimeout(() => { isUserAction = false; }, 100);
}

// Remplir liste
function debugRenderList(filter = '') {
    console.log('[DEBUG] Rendu liste, filtre:', filter);
    const list = document.getElementById('smmLangList');
    
    if (!list) {
        console.error('[DEBUG] Liste non trouvée!');
        return;
    }
    
    const filtered = filter 
        ? DEBUG_LANGUAGES.filter(l => l.name.toLowerCase().includes(filter.toLowerCase()))
        : DEBUG_LANGUAGES;
    
    list.innerHTML = filtered.map(lang => `
        <div 
            onclick="debugChangeLang('${lang.code}', '${lang.name}')"
            style="
                padding: 12px 16px;
                cursor: pointer;
                border-radius: 8px;
                margin-bottom: 4px;
                background: transparent;
                display: flex;
                gap: 12px;
                align-items: center;
                transition: all 0.2s ease;
            "
            onmouseover="this.style.background='#f3f4f6'; this.style.transform='translateX(4px)'"
            onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
            <span style="font-size: 24px; line-height: 1;">${lang.flag}</span>
            <span style="flex: 1; font-weight: 500; color: #111827;">${lang.name}</span>
            <span style="font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; background: #f3f4f6; padding: 2px 6px; border-radius: 4px;">${lang.code}</span>
        </div>
    `).join('');
    
    console.log('[DEBUG] Liste rendue:', filtered.length, 'langues');
}

// Recherche
function debugSearch(value) {
    console.log('[DEBUG] Recherche:', value);
    debugRenderList(value);
}

// Changer langue
function debugChangeLang(code, name) {
    console.log('[SMM Translate] Changement langue:', code, name);
    
    // VERROUILLER les interactions pendant 1 seconde SEULEMENT (réduit de 3s)
    isChangingLanguage = true;
    console.log('[SMM Translate] 🔒 Verrouillage activé (1s)');
    
    // Fermer dropdown IMMÉDIATEMENT
    if (debugDropdownOpen) {
        debugDropdownOpen = false;
        const dropdown = document.getElementById('smmTranslateDropdown');
        if (dropdown) dropdown.style.display = 'none';
        
        const chevron = document.getElementById('smmChevron');
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
    
    // Mettre à jour badge
    const badge = document.getElementById('smmCurrentLang');
    if (badge) {
        badge.textContent = code.toUpperCase().substring(0, 3);
    }
    
    // Déclencher Google Translate après un court délai
    setTimeout(() => {
        const select = document.querySelector('.goog-te-combo');
        if (select) {
            select.value = code;
            select.dispatchEvent(new Event('change'));
            console.log('[SMM Translate] Traduction déclenchée ✅');
        } else {
            console.warn('[SMM Translate] Widget Google non prêt, nouvelle tentative...');
            // Retry mais sans réactiver le verrouillage
            setTimeout(() => {
                const retrySelect = document.querySelector('.goog-te-combo');
                if (retrySelect) {
                    retrySelect.value = code;
                    retrySelect.dispatchEvent(new Event('change'));
                }
            }, 500);
        }
    }, 300);
    
    // DÉVERROUILLER après 1 seconde SEULEMENT (réduit de 3s → 1s)
    setTimeout(() => {
        isChangingLanguage = false;
        isUserAction = false;
        console.log('[SMM Translate] 🔓 Verrouillage désactivé');
    }, 1000);
}

// Initialisation
function debugInit() {
    console.log('[SMM Translate] Initialisation...');
    
    // Éviter réinitialisation multiple
    if (eventListenersAttached) {
        console.log('[SMM Translate] Déjà initialisé, skip');
        return;
    }
    
    // Vérifier éléments
    const btn = document.getElementById('smmTranslateBtn');
    const dropdown = document.getElementById('smmTranslateDropdown');
    const list = document.getElementById('smmLangList');
    
    console.log('[SMM Translate] Éléments:', {
        bouton: !!btn,
        dropdown: !!dropdown,
        liste: !!list
    });
    
    if (!btn || !dropdown || !list) {
        console.error('[SMM Translate] Éléments manquants!');
        return;
    }
    
    // Remplir liste
    debugRenderList();
    
    // Fermer si clic extérieur (avec protection contre auto-trigger)
    document.addEventListener('click', function(e) {
        // Ignorer si verrouillé ou pas d'action utilisateur
        if (isChangingLanguage) return;
        
        const wrapper = document.querySelector('.smm-translate-wrapper');
        if (wrapper && !wrapper.contains(e.target) && debugDropdownOpen && !isUserAction) {
            console.log('[SMM Translate] Clic extérieur, fermeture');
            debugDropdownOpen = false;
            dropdown.style.display = 'none';
            const chevron = document.getElementById('smmChevron');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    }, true); // true = capture phase
    
    // Fermer avec ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && debugDropdownOpen && !isChangingLanguage) {
            console.log('[SMM Translate] ESC pressé, fermeture');
            debugDropdownOpen = false;
            dropdown.style.display = 'none';
            const chevron = document.getElementById('smmChevron');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });
    
    // Marquer comme initialisé
    eventListenersAttached = true;
    
    // Fonction helper pour calculer position intelligente
    function calculateSmartPosition(btn, dropdown) {
        const btnRect = btn.getBoundingClientRect();
        const dropdownWidth = 320;
        const screenWidth = window.innerWidth;
        const isMobile = screenWidth <= 768;
        
        dropdown.style.top = (btnRect.bottom + 10) + 'px';
        
        if (isMobile) {
            dropdown.style.left = '20px';
            dropdown.style.right = '20px';
            dropdown.style.width = 'auto';
        } else {
            let calculatedLeft = btnRect.left;
            
            // Anti-débordement droite
            if (calculatedLeft + dropdownWidth > screenWidth - 20) {
                calculatedLeft = screenWidth - dropdownWidth - 20;
            }
            
            // Anti-débordement gauche
            if (calculatedLeft < 20) {
                calculatedLeft = 20;
            }
            
            dropdown.style.left = calculatedLeft + 'px';
            dropdown.style.right = 'auto';
            dropdown.style.width = dropdownWidth + 'px';
        }
    }
    
    // Repositionner dropdown si scroll (pour suivre le header sticky)
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (!debugDropdownOpen || isChangingLanguage) return;
        
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const btn = document.getElementById('smmTranslateBtn');
            const dropdown = document.getElementById('smmTranslateDropdown');
            
            if (btn && dropdown) {
                calculateSmartPosition(btn, dropdown);
            }
        }, 10);
    }, true);
    
    // Repositionner aussi sur resize
    window.addEventListener('resize', function() {
        if (!debugDropdownOpen || isChangingLanguage) return;
        
        const btn = document.getElementById('smmTranslateBtn');
        const dropdown = document.getElementById('smmTranslateDropdown');
        
        if (btn && dropdown) {
            calculateSmartPosition(btn, dropdown);
        }
    });
                dropdown.style.right = 'auto';
            }
        }
    });
    
    console.log('[SMM Translate] Widget initialisé ✅');
}

// Lancer immédiatement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', debugInit);
} else {
    debugInit();
}

// Google Translate
function googleTranslateElementInit() {
    console.log('[DEBUG] Google Translate init');
    new google.translate.TranslateElement({
        pageLanguage: 'fr',
        includedLanguages: DEBUG_LANGUAGES.map(l => l.code).join(','),
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
    }, 'google_translate_element');
}
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- Masquer éléments Google -->
<style>
.goog-te-banner-frame,
.goog-te-balloon-frame,
.skiptranslate {
    display: none !important;
}
body {
    top: 0 !important;
}
</style>
