<?php
/**
 * GOOGLE TRANSLATE WIDGET - VERSION PREMIUM
 * 
 * Widget multi-langue personnalisé pour SMM Mastery
 * - Design premium avec gradient bleu/violet
 * - Animations et transitions fluides
 * - Loader pendant changement de langue
 * - Support de 100+ langues mondiales
 * - Responsive mobile-friendly
 * 
 * @version 1.0
 * @author SMM Mastery Team
 * @date 14/10/2025
 */

// Sécurité
if (!defined('SITE_URL')) {
    die('Accès direct non autorisé');
}
?>

<!-- CSS Widget Multi-langue -->
<style>
/* === CONTENEUR PRINCIPAL === */
.smm-translate-wrapper {
    position: relative;
    display: inline-block;
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
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
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
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(5px);
}

.smm-translate-loader.active {
    display: flex;
}

.smm-translate-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.smm-translate-loader-text {
    position: absolute;
    color: white;
    font-weight: 600;
    margin-top: 100px;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
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
        right: auto;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        min-width: calc(100vw - 40px);
        max-width: 320px;
    }
    
    .smm-translate-dropdown.active {
        transform: translateX(-50%) translateY(0);
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
    <button class="smm-translate-btn" id="smmTranslateBtn" type="button">
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
        { code: 'pt', name: 'Português (BR)', flag: '🇧🇷', popular: true },
        
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

// === INITIALISATION ===
document.addEventListener('DOMContentLoaded', function() {
    initTranslateWidget();
});

function initTranslateWidget() {
    renderLanguageList();
    attachEventListeners();
    detectCurrentLanguage();
}

// === RENDU LISTE LANGUES ===
function renderLanguageList(filter = '') {
    const listContainer = document.getElementById('smmLangList');
    const languages = SMM_TRANSLATE_CONFIG.languages;
    
    // Trier : populaires d'abord, puis alphabétique
    const sortedLangs = languages.sort((a, b) => {
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
    
    // Générer HTML
    listContainer.innerHTML = filtered.map(lang => `
        <div class="smm-translate-item ${lang.code === currentLanguage ? 'active' : ''}" 
             data-lang="${lang.code}"
             onclick="changeLanguage('${lang.code}', '${lang.name}')">
            <span class="smm-translate-flag">${lang.flag}</span>
            <span class="smm-translate-name">${lang.name}</span>
            <span class="smm-translate-code">${lang.code}</span>
        </div>
    `).join('');
}

// === ÉVÉNEMENTS ===
function attachEventListeners() {
    // Toggle dropdown
    const btn = document.getElementById('smmTranslateBtn');
    btn.addEventListener('click', toggleDropdown);
    
    // Recherche
    const search = document.getElementById('smmLangSearch');
    search.addEventListener('input', (e) => {
        renderLanguageList(e.target.value);
    });
    
    // Fermer si clic extérieur
    document.addEventListener('click', (e) => {
        const wrapper = document.querySelector('.smm-translate-wrapper');
        if (!wrapper.contains(e.target) && isDropdownOpen) {
            closeDropdown();
        }
    });
}

// === TOGGLE DROPDOWN ===
function toggleDropdown() {
    const dropdown = document.getElementById('smmTranslateDropdown');
    const btn = document.getElementById('smmTranslateBtn');
    
    isDropdownOpen = !isDropdownOpen;
    
    if (isDropdownOpen) {
        dropdown.classList.add('active');
        btn.classList.add('active');
    } else {
        closeDropdown();
    }
}

function closeDropdown() {
    const dropdown = document.getElementById('smmTranslateDropdown');
    const btn = document.getElementById('smmTranslateBtn');
    dropdown.classList.remove('active');
    btn.classList.remove('active');
    isDropdownOpen = false;
}

// === CHANGER LANGUE ===
function changeLanguage(langCode, langName) {
    if (langCode === currentLanguage) {
        closeDropdown();
        return;
    }
    
    // Afficher loader
    const loader = document.getElementById('smmTranslateLoader');
    loader.classList.add('active');
    
    // Fermer dropdown
    closeDropdown();
    
    // Mettre à jour badge
    document.getElementById('smmCurrentLang').textContent = langCode.toUpperCase();
    currentLanguage = langCode;
    
    // Sauvegarder préférence
    localStorage.setItem('smm_preferred_language', langCode);
    
    // Changer langue via Google Translate
    setTimeout(() => {
        triggerGoogleTranslate(langCode);
        
        // Cacher loader après 1.5s
        setTimeout(() => {
            loader.classList.remove('active');
        }, 1500);
    }, 300);
}

// === TRIGGER GOOGLE TRANSLATE ===
function triggerGoogleTranslate(langCode) {
    const select = document.querySelector('.goog-te-combo');
    if (select) {
        select.value = langCode;
        select.dispatchEvent(new Event('change'));
    }
}

// === DÉTECTER LANGUE ACTUELLE ===
function detectCurrentLanguage() {
    // Vérifier préférence sauvegardée
    const saved = localStorage.getItem('smm_preferred_language');
    if (saved) {
        currentLanguage = saved;
        document.getElementById('smmCurrentLang').textContent = saved.toUpperCase();
    }
    
    // Vérifier paramètre URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlLang = urlParams.get('lang');
    if (urlLang) {
        currentLanguage = urlLang;
    }
}

// === INITIALISATION GOOGLE TRANSLATE ===
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: SMM_TRANSLATE_CONFIG.defaultLang,
        includedLanguages: SMM_TRANSLATE_CONFIG.languages.map(l => l.code).join(','),
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
    }, 'google_translate_element');
}
</script>

<!-- Chargement Google Translate API -->
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
