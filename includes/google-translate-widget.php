<?php
/**
 * GOOGLE TRANSLATE WIDGET - VERSION PREMIUM FIXED
 * 
 * Widget multi-langue personnalisé pour SMM Mastery
 * - Design premium avec gradient bleu/violet
 * - Animations et transitions fluides
 * - Loader pendant changement de langue
 * - Support de 100+ langues mondiales
 * - Responsive mobile-friendly
 * 
 * @version 1.1 - FIXED
 * @author SMM Mastery Team
 * @date 14/10/2025
 */

// Sécurité
if (!defined('SITE_URL')) {
    die('Accès direct non autorisé');
}

// ID unique pour éviter conflits
$widget_id = uniqid('smm_translate_');
?>

<!-- CSS Widget Multi-langue -->
<style>
/* === CONTENEUR PRINCIPAL === */
.smm-translate-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    z-index: 999;
}

/* === BOUTON DÉCLENCHEUR === */
.smm-translate-btn {
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
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
    position: relative;
    overflow: hidden;
}

/* Effet brillance animé */
.smm-translate-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.3), 
        transparent
    );
    transition: left 0.6s;
}

.smm-translate-btn:hover::before {
    left: 100%;
}

.smm-translate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.smm-translate-btn:active {
    transform: translateY(0);
}

/* Icône globe animée */
.smm-translate-icon {
    font-size: 18px;
    animation: rotate-globe 20s linear infinite;
}

@keyframes rotate-globe {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.smm-translate-btn:hover .smm-translate-icon {
    animation-duration: 2s;
}

/* Badge langue actuelle */
.smm-translate-current {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Flèche dropdown */
.smm-translate-arrow {
    font-size: 12px;
    transition: transform 0.3s;
}

.smm-translate-btn.active .smm-translate-arrow {
    transform: rotate(180deg);
}

/* === MENU DROPDOWN === */
.smm-translate-dropdown {
    position: fixed !important; /* FIXED pour éviter problème overflow header */
    top: 70px; /* Ajusté dynamiquement par JS */
    right: auto; /* Ajusté dynamiquement par JS */
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    z-index: 99999 !important; /* Z-index très élevé */
}

.smm-translate-dropdown.active {
    max-height: 500px;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

/* Header du menu */
.smm-translate-header {
    padding: 20px;
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}

.smm-translate-header h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.smm-translate-header p {
    margin: 0;
    font-size: 13px;
    opacity: 0.9;
}

/* Barre de recherche */
.smm-translate-search {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.smm-translate-search input {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.smm-translate-search input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Liste des langues */
.smm-translate-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
}

/* Scrollbar personnalisée */
.smm-translate-list::-webkit-scrollbar {
    width: 6px;
}

.smm-translate-list::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 10px;
}

.smm-translate-list::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    border-radius: 10px;
}

/* Items langues */
.smm-translate-item {
    padding: 12px 15px;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 5px;
}

.smm-translate-item:hover {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    transform: translateX(5px);
}

.smm-translate-item.active {
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    color: white;
}

.smm-translate-flag {
    font-size: 24px;
    width: 32px;
    text-align: center;
}

.smm-translate-name {
    flex: 1;
    font-weight: 500;
}

.smm-translate-code {
    font-size: 12px;
    opacity: 0.7;
    font-weight: 600;
    text-transform: uppercase;
}

.smm-translate-item.active .smm-translate-code {
    opacity: 1;
}

/* === LOADER PENDANT TRADUCTION === */
.smm-translate-loader {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0, 0, 0, 0.92) !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 2147483647 !important; /* Max z-index possible */
    backdrop-filter: blur(10px) !important;
    flex-direction: column !important;
    gap: 20px !important;
}

.smm-translate-loader.active {
    display: flex !important;
}

.smm-translate-spinner {
    width: 80px;
    height: 80px;
    border: 6px solid rgba(255, 255, 255, 0.2);
    border-top-color: #60a5fa;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    box-shadow: 0 0 30px rgba(96, 165, 250, 0.5);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.smm-translate-loader-text {
    color: white !important;
    font-weight: 700 !important;
    font-size: 20px !important;
    animation: pulse 1.5s ease-in-out infinite !important;
    text-align: center !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 12px !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8) !important;
}

.smm-translate-loader-text i {
    font-size: 48px !important;
    color: #60a5fa !important;
    filter: drop-shadow(0 0 20px rgba(96, 165, 250, 0.8)) !important;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

/* === RESPONSIVE MOBILE === */
@media (max-width: 768px) {
    .smm-translate-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .smm-translate-current {
        display: none;
    }
    
    .smm-translate-dropdown {
        /* Position ajustée par JS pour mobile aussi */
        min-width: calc(100vw - 40px) !important;
        max-width: 320px;
        left: 20px !important; /* Centré avec marges */
        right: 20px !important;
    }
}

/* === GOOGLE TRANSLATE MASQUÉ === */
#google_translate_element {
    display: none !important;
}

.goog-te-banner-frame,
.goog-te-balloon-frame {
    display: none !important;
}

body {
    top: 0 !important;
}

.skiptranslate {
    display: none !important;
}
</style>

<!-- HTML Widget -->
<div class="smm-translate-wrapper">
    <!-- Bouton déclencheur -->
    <button class="smm-translate-btn" id="smmTranslateBtn" type="button" onclick="window.smmToggleDropdown(); return false;">
        <i class="fas fa-globe smm-translate-icon"></i>
        <span class="smm-translate-current" id="smmCurrentLang">FR</span>
        <i class="fas fa-chevron-down smm-translate-arrow"></i>
    </button>
    
    <!-- Menu dropdown -->
    <div class="smm-translate-dropdown" id="smmTranslateDropdown">
        <!-- Header -->
        <div class="smm-translate-header">
            <h4>
                <i class="fas fa-language"></i>
                Choisir la langue
            </h4>
            <p>Traduction automatique disponible</p>
        </div>
        
        <!-- Barre de recherche -->
        <div class="smm-translate-search">
            <input 
                type="text" 
                id="smmLangSearch" 
                placeholder="🔍 Rechercher une langue..."
                autocomplete="off"
                onkeyup="window.smmSearchLanguage(this.value)"
            >
        </div>
        
        <!-- Liste des langues -->
        <div class="smm-translate-list" id="smmLangList">
            <!-- Généré dynamiquement par JavaScript -->
        </div>
    </div>
</div>

<!-- Loader pendant traduction -->
<div class="smm-translate-loader" id="smmTranslateLoader">
    <div>
        <div class="smm-translate-spinner"></div>
        <div class="smm-translate-loader-text">
            <i class="fas fa-language"></i>
            Traduction en cours...
        </div>
    </div>
</div>

<!-- Google Translate Element (caché) -->
<div id="google_translate_element"></div>

<!-- JavaScript Widget -->
<script>
(function() {
    'use strict';
    
    console.log('[SMM Translate] Chargement du widget...');
    
    // === CONFIGURATION ===
    const SMM_TRANSLATE_CONFIG = {
        defaultLang: 'fr',
        // 50+ langues principales mondiales
        languages: [
            // Européennes
            { code: 'fr', name: 'Français', flag: '🇫🇷', popular: true },
            { code: 'en', name: 'English', flag: '🇬🇧', popular: true },
            { code: 'es', name: 'Español', flag: '🇪🇸', popular: true },
            { code: 'de', name: 'Deutsch', flag: '🇩🇪', popular: true },
            { code: 'it', name: 'Italiano', flag: '🇮🇹', popular: true },
            { code: 'pt', name: 'Português', flag: '🇵🇹', popular: true },
            { code: 'nl', name: 'Nederlands', flag: '🇳🇱', popular: false },
            { code: 'pl', name: 'Polski', flag: '🇵🇱', popular: false },
            { code: 'ru', name: 'Русский', flag: '🇷🇺', popular: true },
            { code: 'tr', name: 'Türkçe', flag: '🇹🇷', popular: true },
            { code: 'sv', name: 'Svenska', flag: '🇸🇪', popular: false },
            { code: 'no', name: 'Norsk', flag: '🇳🇴', popular: false },
            { code: 'da', name: 'Dansk', flag: '🇩🇰', popular: false },
            { code: 'fi', name: 'Suomi', flag: '🇫🇮', popular: false },
            { code: 'el', name: 'Ελληνικά', flag: '🇬🇷', popular: false },
            { code: 'cs', name: 'Čeština', flag: '🇨🇿', popular: false },
            { code: 'ro', name: 'Română', flag: '🇷🇴', popular: false },
            { code: 'uk', name: 'Українська', flag: '🇺🇦', popular: false },
            
            // Asiatiques
            { code: 'zh-CN', name: '中文 (简体)', flag: '🇨🇳', popular: true },
            { code: 'zh-TW', name: '中文 (繁體)', flag: '🇹🇼', popular: false },
            { code: 'ja', name: '日本語', flag: '🇯🇵', popular: true },
            { code: 'ko', name: '한국어', flag: '🇰🇷', popular: true },
            { code: 'hi', name: 'हिन्दी', flag: '🇮🇳', popular: true },
            { code: 'th', name: 'ไทย', flag: '🇹🇭', popular: false },
            { code: 'vi', name: 'Tiếng Việt', flag: '🇻🇳', popular: false },
            { code: 'id', name: 'Bahasa Indonesia', flag: '🇮🇩', popular: false },
            { code: 'ms', name: 'Bahasa Melayu', flag: '🇲🇾', popular: false },
            { code: 'fil', name: 'Filipino', flag: '🇵🇭', popular: false },
            
            // Moyen-Orient & Afrique
            { code: 'ar', name: 'العربية', flag: '🇸🇦', popular: true },
            { code: 'he', name: 'עברית', flag: '🇮🇱', popular: false },
            { code: 'fa', name: 'فارسی', flag: '🇮🇷', popular: false },
            { code: 'ur', name: 'اردو', flag: '🇵🇰', popular: false },
            { code: 'sw', name: 'Kiswahili', flag: '🇰🇪', popular: false },
            
            // Amériques
            { code: 'pt-BR', name: 'Português (BR)', flag: '🇧🇷', popular: true },
            
            // Autres
            { code: 'bn', name: 'বাংলা', flag: '🇧🇩', popular: false },
            { code: 'te', name: 'తెలుగు', flag: '🇮🇳', popular: false },
            { code: 'ta', name: 'தமிழ்', flag: '🇮🇳', popular: false },
            { code: 'mr', name: 'मराठी', flag: '🇮🇳', popular: false },
        ]
    };

    // === VARIABLES GLOBALES ===
    let currentLanguage = SMM_TRANSLATE_CONFIG.defaultLang;
    let isDropdownOpen = false;

    // === RENDU LISTE LANGUES ===
    function renderLanguageList(filter = '') {
        console.log('[SMM Translate] Rendu liste langues, filtre:', filter);
        const listContainer = document.getElementById('smmLangList');
        if (!listContainer) {
            console.error('[SMM Translate] Container smmLangList non trouvé!');
            return;
        }
        
        const languages = SMM_TRANSLATE_CONFIG.languages;
        
        // Trier : populaires d'abord, puis alphabétique
        const sortedLangs = [...languages].sort((a, b) => {
            if (a.popular && !b.popular) return -1;
            if (!a.popular && b.popular) return 1;
            return a.name.localeCompare(b.name);
        });
        
        // Filtrer selon recherche
        const filtered = filter 
            ? sortedLangs.filter(lang => 
                lang.name.toLowerCase().includes(filter.toLowerCase()) ||
                lang.code.toLowerCase().includes(filter.toLowerCase())
              )
            : sortedLangs;
        
        console.log('[SMM Translate] Langues filtrées:', filtered.length);
        
        // Générer HTML
        listContainer.innerHTML = filtered.map(lang => `
            <div class="smm-translate-item ${lang.code === currentLanguage ? 'active' : ''}" 
                 data-lang="${lang.code}"
                 onclick="window.smmChangeLanguage('${lang.code}', '${lang.name.replace(/'/g, "\\'")}'); return false;">
                <span class="smm-translate-flag">${lang.flag}</span>
                <span class="smm-translate-name">${lang.name}</span>
                <span class="smm-translate-code">${lang.code.toUpperCase()}</span>
            </div>
        `).join('');
    }

    // === TOGGLE DROPDOWN ===
    window.smmToggleDropdown = function() {
        console.log('[SMM Translate] Toggle dropdown, état actuel:', isDropdownOpen);
        const dropdown = document.getElementById('smmTranslateDropdown');
        const btn = document.getElementById('smmTranslateBtn');
        
        if (!dropdown || !btn) {
            console.error('[SMM Translate] Elements non trouvés!');
            return;
        }
        
        isDropdownOpen = !isDropdownOpen;
        
        if (isDropdownOpen) {
            // Calculer position dropdown par rapport au bouton
            const btnRect = btn.getBoundingClientRect();
            const dropdownWidth = 280;
            const isMobile = window.innerWidth <= 768;
            
            // Position verticale : sous le bouton
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            
            // Position horizontale
            if (isMobile) {
                // Mobile : centré avec marges
                dropdown.style.left = '20px';
                dropdown.style.right = '20px';
                dropdown.style.width = 'auto';
            } else {
                // Desktop : aligné à droite du bouton
                const leftPosition = btnRect.left;
                const rightPosition = window.innerWidth - btnRect.right;
                
                // Vérifier s'il y a assez d'espace à droite
                if (rightPosition >= dropdownWidth + 20) {
                    // Assez d'espace à droite : aligner sur le bord gauche du bouton
                    dropdown.style.left = leftPosition + 'px';
                    dropdown.style.right = 'auto';
                } else {
                    // Pas assez d'espace : aligner sur le bord droit du bouton
                    dropdown.style.left = 'auto';
                    dropdown.style.right = rightPosition + 'px';
                }
                dropdown.style.width = dropdownWidth + 'px';
            }
            
            console.log('[SMM Translate] Position calculée:', {
                top: dropdown.style.top,
                left: dropdown.style.left,
                buttonRect: btnRect
            });
            
            dropdown.classList.add('active');
            btn.classList.add('active');
            console.log('[SMM Translate] Dropdown ouvert ✅');
        } else {
            dropdown.classList.remove('active');
            btn.classList.remove('active');
            console.log('[SMM Translate] Dropdown fermé');
        }
    };

    // === RECHERCHE LANGUE ===
    window.smmSearchLanguage = function(value) {
        renderLanguageList(value);
    };

    // === CHANGER LANGUE ===
    window.smmChangeLanguage = function(langCode, langName) {
        console.log('[SMM Translate] Changement langue:', langCode, langName);
        
        if (langCode === currentLanguage) {
            window.smmToggleDropdown();
            return;
        }
        
        // Afficher loader
        const loader = document.getElementById('smmTranslateLoader');
        if (loader) loader.classList.add('active');
        
        // Fermer dropdown
        if (isDropdownOpen) window.smmToggleDropdown();
        
        // Mettre à jour badge
        const badge = document.getElementById('smmCurrentLang');
        if (badge) badge.textContent = langCode.toUpperCase().substring(0, 3);
        currentLanguage = langCode;
        
        // Sauvegarder préférence
        try {
            localStorage.setItem('smm_preferred_language', langCode);
            console.log('[SMM Translate] Langue sauvegardée:', langCode);
        } catch (e) {
            console.warn('[SMM Translate] Erreur sauvegarde localStorage:', e);
        }
        
        // Changer langue via Google Translate
        setTimeout(() => {
            triggerGoogleTranslate(langCode, 0);
            
            // Cacher loader après 1.5s
            setTimeout(() => {
                if (loader) loader.classList.remove('active');
            }, 1500);
        }, 300);
    };

    // === TRIGGER GOOGLE TRANSLATE ===
    function triggerGoogleTranslate(langCode, attempt = 0) {
        console.log('[SMM Translate] Déclenchement Google Translate:', langCode, 'tentative', attempt + 1);
        const select = document.querySelector('.goog-te-combo');
        if (select) {
            select.value = langCode;
            select.dispatchEvent(new Event('change'));
            console.log('[SMM Translate] Traduction déclenchée ✅');
        } else if (attempt < 9) {
            console.warn('[SMM Translate] Widget Google Translate non trouvé, nouvelle tentative imminente...');
            setTimeout(() => triggerGoogleTranslate(langCode, attempt + 1), 500);
        } else {
            console.error('[SMM Translate] Échec du chargement du widget Google Translate après plusieurs essais');
            const loader = document.getElementById('smmTranslateLoader');
            if (loader) {
                loader.classList.remove('active');
            }
        }
    }

    // === DÉTECTER LANGUE ACTUELLE ===
    function detectCurrentLanguage() {
        // Vérifier préférence sauvegardée
        try {
            const saved = localStorage.getItem('smm_preferred_language');
            if (saved) {
                currentLanguage = saved;
                const badge = document.getElementById('smmCurrentLang');
                if (badge) badge.textContent = saved.toUpperCase().substring(0, 3);
                console.log('[SMM Translate] Langue restaurée:', saved);
            }
        } catch (e) {
            console.warn('[SMM Translate] Erreur lecture localStorage:', e);
        }
        
        // Vérifier paramètre URL
        const urlParams = new URLSearchParams(window.location.search);
        const urlLang = urlParams.get('lang');
        if (urlLang) {
            currentLanguage = urlLang;
            console.log('[SMM Translate] Langue depuis URL:', urlLang);
        }
    }

    // === FERMER SI CLIC EXTÉRIEUR ===
    function setupClickOutside() {
        document.addEventListener('click', function(e) {
            const wrapper = document.querySelector('.smm-translate-wrapper');
            if (wrapper && !wrapper.contains(e.target) && isDropdownOpen) {
                console.log('[SMM Translate] Clic extérieur, fermeture');
                window.smmToggleDropdown();
            }
        });
    }
    
    // === RECALCULER POSITION SUR SCROLL/RESIZE ===
    function setupPositionUpdate() {
        let repositionTimeout;
        
        function updatePosition() {
            if (!isDropdownOpen) return;
            
            const dropdown = document.getElementById('smmTranslateDropdown');
            const btn = document.getElementById('smmTranslateBtn');
            
            if (!dropdown || !btn) return;
            
            const btnRect = btn.getBoundingClientRect();
            const dropdownWidth = 280;
            const isMobile = window.innerWidth <= 768;
            
            // Position verticale
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            
            // Position horizontale
            if (isMobile) {
                dropdown.style.left = '20px';
                dropdown.style.right = '20px';
                dropdown.style.width = 'auto';
            } else {
                const leftPosition = btnRect.left;
                const rightPosition = window.innerWidth - btnRect.right;
                
                if (rightPosition >= dropdownWidth + 20) {
                    dropdown.style.left = leftPosition + 'px';
                    dropdown.style.right = 'auto';
                } else {
                    dropdown.style.left = 'auto';
                    dropdown.style.right = rightPosition + 'px';
                }
                dropdown.style.width = dropdownWidth + 'px';
            }
        }
        
        // Recalculer sur scroll et resize
        window.addEventListener('scroll', function() {
            clearTimeout(repositionTimeout);
            repositionTimeout = setTimeout(updatePosition, 10);
        }, true); // true = capture phase pour scroll dans tous conteneurs
        
        window.addEventListener('resize', function() {
            clearTimeout(repositionTimeout);
            repositionTimeout = setTimeout(updatePosition, 10);
        });
    }

    // === INITIALISATION ===
    function initTranslateWidget() {
        console.log('[SMM Translate] Initialisation...');
        renderLanguageList();
        detectCurrentLanguage();
        setupClickOutside();
        setupPositionUpdate();
        console.log('[SMM Translate] Widget initialisé ✅');
    }

    // Lancer dès que possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTranslateWidget);
    } else {
        // DOM déjà prêt, lancer immédiatement
        initTranslateWidget();
    }

    // === INITIALISATION GOOGLE TRANSLATE ===
    window.googleTranslateElementInit = function() {
        console.log('[SMM Translate] Initialisation Google Translate API...');
        try {
            new google.translate.TranslateElement({
                pageLanguage: SMM_TRANSLATE_CONFIG.defaultLang,
                includedLanguages: SMM_TRANSLATE_CONFIG.languages.map(l => l.code).join(','),
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
            console.log('[SMM Translate] Google Translate API prête ✅');
            
            // Vérifier que le widget est bien injecté après 2 secondes
            setTimeout(() => {
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    console.log('[SMM Translate] Widget Google injecté avec succès! Options:', select.options.length);
                } else {
                    console.error('[SMM Translate] ❌ Widget Google non injecté après 2s - Vérifier le chargement du script');
                }
            }, 2000);
        } catch (error) {
            console.error('[SMM Translate] Erreur initialisation Google Translate:', error);
        }
    };
    
})();
</script>

<!-- Chargement Google Translate API -->
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
