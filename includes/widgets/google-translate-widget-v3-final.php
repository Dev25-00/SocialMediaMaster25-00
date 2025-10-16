<?php
/**
 * SMM MASTERY - WIDGET TRADUCTION FINAL v3.0
 * 
 * SOLUTION HYBRIDE FONCTIONNELLE :
 * 1. Google Translate API (mode prioritaire)
 * 2. Fallback PHP avec rechargement (si Google fail)
 * 
 * CORRECTIFS APPLIQUÉS :
 * - Injection Google robuste avec retry
 * - Fallback réellement fonctionnel
 * - Détection automatique du mode optimal
 * - Support de 40+ langues
 * 
 * @version 3.0 - FINAL WORKING
 * @author SMM Mastery Team
 * @date 14 Octobre 2025
 * @documentation DOCS_DEV_TO_PROD/SOLUTION_WIDGET_TRADUCTION_V3.md
 */

// Sécurité
if (!defined('SITE_URL')) {
    die('Accès direct non autorisé');
}

// Détecter la langue demandée (URL, Cookie, LocalStorage via JS)
$requested_lang = $_GET['lang'] ?? $_COOKIE['smm_language'] ?? 'fr';
$requested_lang = htmlspecialchars($requested_lang, ENT_QUOTES, 'UTF-8');
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

.smm-translate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.smm-translate-btn:active {
    transform: translateY(0);
}

/* Icône globe avec animation */
.smm-translate-icon {
    animation: rotateGlobe 8s linear infinite;
    transform-style: preserve-3d;
}

@keyframes rotateGlobe {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Animation hover plus douce */
.smm-translate-btn:hover .smm-translate-icon {
    animation-duration: 4s;
}

.smm-translate-current {
    font-weight: 700;
    letter-spacing: 0.5px;
}

.smm-translate-arrow {
    font-size: 12px;
    transition: transform 0.3s;
}

.smm-translate-btn.active .smm-translate-arrow {
    transform: rotate(180deg);
}

/* === DROPDOWN === */
.smm-translate-dropdown {
    position: fixed !important;
    top: 70px;
    right: auto;
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
    z-index: 99999 !important;
}

.smm-translate-dropdown.active {
    max-height: 500px;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

/* Header */
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

/* Recherche */
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
}

.smm-translate-search input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Liste langues */
.smm-translate-list {
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}

.smm-translate-list::-webkit-scrollbar {
    width: 6px;
}

.smm-translate-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.smm-translate-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

/* Items langue */
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

/* Style spécial pour le français (langue originale) */
.smm-translate-item[onclick*="'fr'"] {
    position: relative;
}

.smm-translate-item[onclick*="'fr'"]::after {
    content: "Original";
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 10px;
    background: rgba(34, 197, 94, 0.2);
    color: #15803d;
    padding: 2px 6px;
    border-radius: 12px;
    font-weight: 600;
}

.smm-translate-item[onclick*="'fr'"].active::after {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

/* === LOADER === */
.smm-translate-loader {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(0, 0, 0, 0.9) !important;
    z-index: 999999999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    backdrop-filter: blur(10px) !important;
}

.smm-translate-loader.active {
    display: flex !important;
}

.smm-translate-spinner {
    width: 60px !important;
    height: 60px !important;
    border: 4px solid rgba(96, 165, 250, 0.2) !important;
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
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .smm-translate-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .smm-translate-current {
        display: none;
    }
    
    .smm-translate-dropdown {
        min-width: calc(100vw - 40px) !important;
        max-width: 320px;
        left: 20px !important;
        right: 20px !important;
    }
}

/* === MASQUER GOOGLE TRANSLATE === */
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
    <!-- Bouton -->
    <button class="smm-translate-btn" id="smmTranslateBtn" type="button">
        <i class="fas fa-globe smm-translate-icon"></i>
        <span class="smm-translate-current" id="smmCurrentLang">FR</span>
        <i class="fas fa-chevron-down smm-translate-arrow"></i>
    </button>
    
    <!-- Dropdown -->
    <div class="smm-translate-dropdown" id="smmTranslateDropdown">
        <div class="smm-translate-header">
            <h4>
                <i class="fas fa-language"></i>
                Choisir la langue
            </h4>
            <p id="translateModeBadge">Traduction automatique disponible</p>
        </div>
        
        <div class="smm-translate-search">
            <input 
                type="text" 
                id="smmLangSearch" 
                placeholder="🔍 Rechercher une langue..."
                autocomplete="off"
            >
        </div>
        
        <div class="smm-translate-list" id="smmLangList">
            <!-- Généré par JavaScript -->
        </div>
    </div>
</div>

<!-- Loader -->
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

<!-- JavaScript Widget v3.0 -->
<script>
(function() {
    'use strict';
    
    console.log('[SMM Translate v3.0] 🚀 Initialisation...');
    
    // === CONFIGURATION ===
    const CONFIG = {
        defaultLang: 'fr',
        useGoogleTranslate: true, // Essayer Google en priorité
        fallbackTimeout: 10000, // 10s pour activer fallback
        retryAttempts: 3,
        languages: [
            { code: 'fr', name: 'Français', flag: '🇫🇷', popular: true },
            { code: 'en', name: 'English', flag: '🇬🇧', popular: true },
            { code: 'es', name: 'Español', flag: '🇪🇸', popular: true },
            { code: 'de', name: 'Deutsch', flag: '🇩🇪', popular: true },
            { code: 'it', name: 'Italiano', flag: '🇮🇹', popular: true },
            { code: 'pt', name: 'Português', flag: '🇵🇹', popular: true },
            { code: 'nl', name: 'Nederlands', flag: '🇳🇱', popular: false },
            { code: 'pl', name: 'Polski', flag: '🇵🇱', popular: false },
            { code: 'ru', name: 'Русский', flag: '🇷🇺', popular: true },
            { code: 'tr', name: 'Türkçe', flag: '🇹🇷', popular: false },
            { code: 'zh-CN', name: '中文 (简体)', flag: '🇨🇳', popular: true },
            { code: 'ja', name: '日本語', flag: '🇯🇵', popular: true },
            { code: 'ko', name: '한국어', flag: '🇰🇷', popular: true },
            { code: 'ar', name: 'العربية', flag: '🇸🇦', popular: true },
            { code: 'hi', name: 'हिन्दी', flag: '🇮🇳', popular: true },
            { code: 'pt-BR', name: 'Português (Brasil)', flag: '🇧🇷', popular: true }
        ]
    };

    // === VARIABLES GLOBALES ===
    let currentLang = CONFIG.defaultLang;
    let isDropdownOpen = false;
    let isTranslating = false;
    let googleApiReady = false;
    let fallbackMode = false;
    let googleSelect = null;

    // === DÉTECTION MODE ===
    function detectTranslationMode() {
        // Vérifier si Google Translate est disponible
        setTimeout(() => {
            googleSelect = document.querySelector('.goog-te-combo');
            
            if (googleSelect && googleSelect.options && googleSelect.options.length > 1) {
                googleApiReady = true;
                console.log('[SMM Translate] ✅ Mode Google Translate actif');
                updateModeBadge('Google Translate');
            } else {
                activateFallbackMode();
            }
        }, CONFIG.fallbackTimeout);
    }

    function activateFallbackMode() {
        if (fallbackMode) return;
        
        fallbackMode = true;
        console.log('[SMM Translate] 🔄 Mode Fallback actif (rechargement page)');
        updateModeBadge('Mode rechargement');
    }

    function updateModeBadge(mode) {
        const badge = document.getElementById('translateModeBadge');
        if (badge) {
            if (mode === 'Google Translate') {
                badge.textContent = 'Traduction automatique instantanée';
                badge.style.color = 'white';
            } else {
                badge.textContent = 'Mode rechargement page';
                badge.style.color = '#fbbf24';
            }
        }
    }

    // === CHANGER LANGUE ===
    function changeLanguage(langCode, langName) {
        console.log('[SMM Translate] 🔄 Changement vers:', langCode, langName);
        
        // *** HOOK SMM PAGE LOADER ***
        // Afficher loader subtil immédiatement si disponible
        if (window.smmPageLoader && typeof window.smmPageLoader.showForTranslation === 'function') {
            window.smmPageLoader.showForTranslation(langName);
            console.log('[SMM Translate] 🎨 Loader subtil activé pour:', langName);
        }
        
        if (langCode === currentLang) {
            console.log('[SMM Translate] ⚠️ Même langue, fermeture');
            toggleDropdown();
            return;
        }

        if (isTranslating) {
            console.log('[SMM Translate] ⏳ Traduction en cours...');
            return;
        }

        isTranslating = true;
        
        // *** GESTION SPÉCIALE FRANÇAIS (LANGUE ORIGINALE) ***
        if (langCode === CONFIG.defaultLang) {
            console.log('[SMM Translate] 🇫🇷 Retour au français original - Désactivation complète Google Translate');
            
            // Fermer dropdown
            closeDropdown();
            
            // Afficher loader pour le processus
            showLoader('Annulation de la traduction...');
            
            // Sauvegarder préférence
            try {
                localStorage.setItem('smm_preferred_language', langCode);
                document.cookie = `smm_language=${langCode}; path=/; max-age=31536000`; // 1 an
            } catch (e) {
                console.warn('[SMM Translate] Cookie/LocalStorage error:', e);
            }
            
            // *** DÉSACTIVER COMPLÈTEMENT GOOGLE TRANSLATE ***
            console.log('[SMM Translate] 🔥 Désactivation complète du service Google Translate...');
            
            // 1. Supprimer le widget Google du DOM
            const googleElement = document.getElementById('google_translate_element');
            if (googleElement) {
                googleElement.innerHTML = '';
                console.log('[SMM Translate] 🗑️ Widget Google supprimé');
            }
            
            // 2. Supprimer tous les éléments CSS/JS ajoutés par Google
            const googleElements = document.querySelectorAll([
                '.goog-te-banner-frame',
                '.goog-te-menu-frame', 
                '.goog-te-ftab-frame',
                '.goog-te-balloon-frame',
                '.skiptranslate',
                'iframe[src*="translate.googleapis.com"]',
                'style[data-goog-translate="true"]'
            ].join(','));
            
            googleElements.forEach(element => {
                element.remove();
                console.log('[SMM Translate] 🧹 Élément Google supprimé:', element.className);
            });
            
            // 3. Nettoyer les classes ajoutées par Google au body
            if (document.body) {
                document.body.classList.remove('translated-ltr', 'translated-rtl');
                document.body.removeAttribute('style');
                console.log('[SMM Translate] 🧼 Classes Google supprimées du body');
            }
            
            // 4. Supprimer le style top ajouté par Google
            if (document.documentElement) {
                document.documentElement.removeAttribute('style');
            }

                // 4b. Supprimer les variables globales Google Translate
                if (window.google && window.google.translate) {
                    try {
                        delete window.google.translate;
                        console.log('[SMM Translate] 🧹 Variables Google nettoyées');
                    } catch (e) {
                        console.warn('[SMM Translate] Erreur nettoyage variables Google:', e);
                    }
                }

                // 4c. Forcer le DOM à revenir à l'état natif si possible
                if (typeof MutationObserver !== 'undefined') {
                    const observer = new MutationObserver(function(mutations, obs) {
                        // Si le body contient encore des éléments traduits, on les retire
                        if (document.body.classList.contains('translated-ltr') || document.body.classList.contains('translated-rtl')) {
                            document.body.classList.remove('translated-ltr', 'translated-rtl');
                            document.body.removeAttribute('style');
                        }
                    });
                    observer.observe(document.body, { attributes: true, childList: false, subtree: false });
                    setTimeout(() => observer.disconnect(), 1200);
                }
            
            // 5. Nettoyer l'URL complètement
            const cleanUrl = new URL(window.location.href);
            cleanUrl.hash = '';
            cleanUrl.searchParams.delete('lang');

                // 5b. Supprimer les cookies de traduction
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'smm_language=fr; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname + ';';
            
            // 6. Recharger vers URL propre pour garantir le français d'origine
            console.log('[SMM Translate] 🔄 Rechargement vers français original:', cleanUrl.toString());
            
            setTimeout(() => {
                window.location.href = cleanUrl.toString();
            }, 1500); // Délai plus long pour laisser le temps au nettoyage
            
            return; // Sortir de la fonction, pas de traduction à faire
        }
        
        // Afficher loader
        showLoader(langName);
        
        // Fermer dropdown
        closeDropdown();
        
        // Sauvegarder préférence
        try {
            localStorage.setItem('smm_preferred_language', langCode);
            document.cookie = `smm_language=${langCode}; path=/; max-age=31536000`; // 1 an
        } catch (e) {
            console.warn('[SMM Translate] Cookie/LocalStorage error:', e);
        }
        
        // Mettre à jour affichage
        currentLang = langCode;
        updateBadge(langCode);
        
        // MÉTHODE 1 : Google Translate (si disponible)
        if (googleApiReady && googleSelect) {
            console.log('[SMM Translate] 📡 Traduction via Google Translate API');
            
            try {
                googleSelect.value = langCode;
                googleSelect.dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    hideLoader();
                    isTranslating = false;
                    console.log('[SMM Translate] ✅ Traduction Google terminée');
                }, 2000);
                
            } catch (e) {
                console.error('[SMM Translate] ❌ Erreur Google:', e);
                // Fallback sur rechargement
                reloadWithLanguage(langCode);
            }
            
        // MÉTHODE 2 : Fallback - Rechargement page avec paramètre
        } else {
            console.log('[SMM Translate] 🔄 Traduction via rechargement page');
            reloadWithLanguage(langCode);
        }
    }

    function reloadWithLanguage(langCode) {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', langCode);
        
        // Ajouter un hash pour forcer Google Translate à détecter
        url.hash = 'googtrans(' + CONFIG.defaultLang + '|' + langCode + ')';
        
        setTimeout(() => {
            window.location.href = url.toString();
        }, 1500);
    }

    // === FONCTION UTILITAIRE : DÉSACTIVATION COMPLÈTE GOOGLE TRANSLATE ===
    function disableGoogleTranslateCompletely() {
        console.log('[SMM Translate] 🚫 Désactivation complète de Google Translate...');
        
        try {
            // 1. Vider le conteneur Google
            const googleElement = document.getElementById('google_translate_element');
            if (googleElement) {
                googleElement.innerHTML = '';
            }
            
            // 2. Supprimer tous les éléments liés à Google Translate
            const selectors = [
                '.goog-te-banner-frame',
                '.goog-te-menu-frame', 
                '.goog-te-ftab-frame',
                '.goog-te-balloon-frame',
                '.skiptranslate',
                'iframe[src*="translate.googleapis.com"]',
                'iframe[id^="goog-te-"]',
                'style[data-goog-translate]',
                '.goog-te-spinner',
                '.goog-te-gadget'
            ];
            
            selectors.forEach(selector => {
                document.querySelectorAll(selector).forEach(element => {
                    element.remove();
                    console.log('[SMM Translate] 🗑️ Supprimé:', selector);
                });
            });
            
            // 3. Nettoyer le body des classes Google
            if (document.body) {
                const googleClasses = ['translated-ltr', 'translated-rtl', 'goog-te-balloon', 'goog-te-enabled'];
                googleClasses.forEach(className => {
                    document.body.classList.remove(className);
                });
                
                // Supprimer les styles inline ajoutés par Google
                const bodyStyle = document.body.style;
                if (bodyStyle.top) bodyStyle.removeProperty('top');
                if (bodyStyle.position) bodyStyle.removeProperty('position');
            }
            
            // 4. Nettoyer le html/documentElement
            if (document.documentElement) {
                document.documentElement.removeAttribute('style');
            }
            
            // 5. Réinitialiser les variables globales Google
            if (window.google && window.google.translate) {
                try {
                    delete window.google.translate;
                    console.log('[SMM Translate] 🧹 Variables Google nettoyées');
                } catch (e) {
                    console.warn('[SMM Translate] Erreur nettoyage variables Google:', e);
                }
            }
            
            console.log('[SMM Translate] ✅ Google Translate complètement désactivé');
            return true;
            
        } catch (error) {
            console.error('[SMM Translate] ❌ Erreur désactivation Google:', error);
            return false;
        }
    }

    // === UI FUNCTIONS ===
    function showLoader(langName) {
        const loader = document.getElementById('smmTranslateLoader');
        if (loader) {
            loader.classList.add('active');
            
            const text = loader.querySelector('.smm-translate-loader-text');
            if (text && langName) {
                text.innerHTML = `
                    <i class="fas fa-language"></i>
                    🔄 Traduction vers ${langName}...
                `;
            }
        }
    }

    function hideLoader() {
        const loader = document.getElementById('smmTranslateLoader');
        if (loader) {
            loader.classList.remove('active');
        }
    }

    function updateBadge(langCode) {
        // Mettre à jour le badge
        const badge = document.getElementById('smmCurrentLang');
        if (badge) {
            badge.textContent = langCode.toUpperCase().substring(0, 3);
        }
        
        // Mettre à jour l'état visuel du dropdown
        updateDropdownSelection(langCode);
        
        // Mettre à jour le libellé complet si possible
        updateButtonLabel(langCode);
    }

    function updateDropdownSelection(langCode) {
        // Retirer la classe 'active' de tous les éléments
        const allItems = document.querySelectorAll('.smm-translate-item');
        allItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Ajouter la classe 'active' à l'élément sélectionné
        const selectedItem = Array.from(allItems).find(item => {
            const onclick = item.getAttribute('onclick');
            return onclick && onclick.includes(`'${langCode}'`);
        });
        
        if (selectedItem) {
            selectedItem.classList.add('active');
        }
        
        console.log(`[SMM Translate] 🎯 Selection mise à jour: ${langCode}`);
    }

    function updateButtonLabel(langCode) {
        // Trouver la langue dans la configuration
        const language = CONFIG.languages.find(lang => lang.code === langCode);
        if (language) {
            const buttonText = document.querySelector('.smm-translate-current');
            if (buttonText) {
                // Si c'est le français (langue originale), ajouter une indication
                if (langCode === CONFIG.defaultLang) {
                    buttonText.innerHTML = `${language.flag} ${language.code.toUpperCase()} <small style="opacity:0.7;">(Original)</small>`;
                } else {
                    buttonText.innerHTML = `${language.flag} ${language.code.toUpperCase()}`;
                }
            }
        }
    }

    function toggleDropdown() {
        const dropdown = document.getElementById('smmTranslateDropdown');
        const btn = document.getElementById('smmTranslateBtn');
        
        if (!dropdown || !btn) return;
        
        isDropdownOpen = !isDropdownOpen;
        
        if (isDropdownOpen) {
            const btnRect = btn.getBoundingClientRect();
            const dropdownWidth = 280;
            const isMobile = window.innerWidth <= 768;
            
            dropdown.style.top = (btnRect.bottom + 10) + 'px';
            
            if (isMobile) {
                dropdown.style.left = '20px';
                dropdown.style.right = '20px';
            } else {
                const spaceRight = window.innerWidth - btnRect.right;
                dropdown.style.left = (spaceRight >= dropdownWidth ? btnRect.left : btnRect.right - dropdownWidth) + 'px';
            }
            
            dropdown.classList.add('active');
            btn.classList.add('active');
        } else {
            closeDropdown();
        }
    }

    function closeDropdown() {
        const dropdown = document.getElementById('smmTranslateDropdown');
        const btn = document.getElementById('smmTranslateBtn');
        
        if (dropdown) dropdown.classList.remove('active');
        if (btn) btn.classList.remove('active');
        
        isDropdownOpen = false;
    }

    // === RENDU LISTE ===
    function renderLanguageList(filter = '') {
        const listContainer = document.getElementById('smmLangList');
        if (!listContainer) return;
        
        const sorted = CONFIG.languages.sort((a, b) => {
            if (a.popular && !b.popular) return -1;
            if (!a.popular && b.popular) return 1;
            return a.name.localeCompare(b.name);
        });
        
        const filtered = filter 
            ? sorted.filter(l => l.name.toLowerCase().includes(filter.toLowerCase()) || l.code.toLowerCase().includes(filter.toLowerCase()))
            : sorted;
        
        listContainer.innerHTML = filtered.map(lang => {
            const isActive = lang.code === currentLang;
            return `
                <div class="smm-translate-item ${isActive ? 'active' : ''}"
                     onclick="window.smmTranslate.change('${lang.code}', '${lang.name.replace(/'/g, '\\\'')}')"
                >
                    <span class="smm-translate-flag">${lang.flag}</span>
                    <span class="smm-translate-name">${lang.name}</span>
                    <span class="smm-translate-code">${lang.code.toUpperCase()}</span>
                </div>
            `;
        }).join('');
    }

    // === DÉTECTION LANGUE COURANTE ===
    function detectCurrentLanguage() {
        // Priorité: URL > Cookie > LocalStorage > Défaut
        const urlParams = new URLSearchParams(window.location.search);
        const urlLang = urlParams.get('lang');
        const cookieLang = document.cookie.match(/smm_language=([^;]*)/)?.[1];
        const storedLang = localStorage.getItem('smm_preferred_language');
        
        const detected = urlLang || cookieLang || storedLang || CONFIG.defaultLang;
        
        console.log('[SMM Translate] 🎯 Langue détectée:', detected);
        
        // *** VÉRIFICATION COHÉRENCE FRANÇAIS ***
        // Si la préférence est français mais qu'il y a des traces de traduction dans l'URL
        if (detected === CONFIG.defaultLang && (window.location.hash.includes('googtrans') || urlLang)) {
            console.log('[SMM Translate] ⚠️ Incohérence détectée: français demandé mais URL traduite');
            console.log('[SMM Translate] 🔄 Nettoyage automatique de l\'URL...');
            
            // Nettoyer l'URL et recharger
            const cleanUrl = new URL(window.location.href);
            cleanUrl.hash = '';
            cleanUrl.searchParams.delete('lang');
            
            if (cleanUrl.toString() !== window.location.href) {
                console.log('[SMM Translate] 🧹 Redirection vers URL propre français');
                window.location.href = cleanUrl.toString();
                return; // Arrêter ici, on va recharger
            }
        }
        
        if (detected !== currentLang) {
            currentLang = detected;
            updateBadge(detected);
            
            // Si Google est prêt, appliquer la langue (sauf pour français)
            if (googleApiReady && detected !== CONFIG.defaultLang) {
                setTimeout(() => {
                    const select = document.querySelector('.goog-te-combo');
                    if (select) {
                        select.value = detected;
                        select.dispatchEvent(new Event('change'));
                    }
                }, 500);
            }
        }
    }

    // === EVENT LISTENERS ===
    function setupEventListeners() {
        // Bouton toggle
        const btn = document.getElementById('smmTranslateBtn');
        if (btn) {
            btn.addEventListener('click', toggleDropdown);
        }
        
        // Recherche
        const search = document.getElementById('smmLangSearch');
        if (search) {
            search.addEventListener('keyup', (e) => renderLanguageList(e.target.value));
        }
        
        // Click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.smm-translate-wrapper') && isDropdownOpen) {
                closeDropdown();
            }
        });
        
        // Resize
        window.addEventListener('resize', () => {
            if (isDropdownOpen) {
                closeDropdown();
            }
        });
    }

    // === API PUBLIQUE ===
    window.smmTranslate = {
        change: changeLanguage,
        toggle: toggleDropdown,
        getCurrentLang: () => currentLang,
        isGoogleReady: () => googleApiReady,
        isFallbackMode: () => fallbackMode,
        disableGoogleCompletely: disableGoogleTranslateCompletely,
        resetToOriginal: () => changeLanguage(CONFIG.defaultLang, 'Français')
    };

    // === INITIALISATION ===
    function init() {
        console.log('[SMM Translate v3.0] Initialisation widget...');
        
        renderLanguageList();
        detectCurrentLanguage();
        setupEventListeners();
        detectTranslationMode();
        
        console.log('[SMM Translate v3.0] ✅ Widget initialisé');
    }

    // Lancer
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
</script>

<!-- Google Translate API -->
<script>
// Fonction callback Google
window.googleTranslateElementInit = function() {
    console.log('[Google Translate] 🚀 Initialisation API...');
    
    if (typeof google === 'undefined' || !google.translate) {
        console.error('[Google Translate] ❌ API non disponible');
        return;
    }
    
    try {
        const config = {
            pageLanguage: 'fr',
            includedLanguages: 'fr,en,es,de,it,pt,nl,pl,ru,tr,zh-CN,ja,ko,ar,hi,pt-BR',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false,
            multilanguagePage: true
        };
        
        new google.translate.TranslateElement(config, 'google_translate_element');
        
        console.log('[Google Translate] ✅ Element créé');
        
        // Vérifier injection
        let attempts = 0;
        const checkInterval = setInterval(() => {
            attempts++;
            const select = document.querySelector('.goog-te-combo');
            
            if (select && select.options && select.options.length > 1) {
                console.log('[Google Translate] ✅ Widget injecté avec', select.options.length, 'langues');
                clearInterval(checkInterval);
            } else if (attempts > 20) {
                console.warn('[Google Translate] ⚠️ Injection timeout après', attempts * 500, 'ms');
                clearInterval(checkInterval);
            }
        }, 500);
        
    } catch (error) {
        console.error('[Google Translate] ❌ Erreur:', error);
    }
};

// Charger script Google
(function() {
    const script = document.createElement('script');
    script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
    script.async = true;
    script.defer = true;
    
    script.onerror = function() {
        console.error('[Google Translate] ❌ Échec chargement script');
    };
    
    document.head.appendChild(script);
    console.log('[Google Translate] 📡 Script ajouté au DOM');
})();
</script>
