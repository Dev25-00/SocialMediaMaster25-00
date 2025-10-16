/**
 * SMM Page Loader - Système de loading subtil pour traductions
 * Créé le 14 Octobre 2025
 * Documentation: SYSTEME_LOADING_SUBTIL_TRADUCTION.md
 * 
 * @description Gère l'affichage d'un loader élégant pendant les traductions
 * @features Overlay translucide, animations fluides, messages contextuels
 * @compatibility ES6+, Mobile responsive, Cross-browser
 */

class SmmPageLoader {
    constructor(options = {}) {
        this.options = {
            showDelay: 200,         // Délai avant affichage (éviter flash)
            minDuration: 800,       // Durée minimum d'affichage
            translationDelay: 2000, // Délai traduction Google
            autoHide: true,         // Masquer auto après succès
            theme: 'light',         // light | dark | auto
            compact: false,         // Mode compact pour mobile
            animation: 'float',     // float | pulse
            debug: false,           // Logs de debug
            ...options
        };

        this.isActive = false;
        this.startTime = null;
        this.showTimeout = null;
        this.hideTimeout = null;
        this.loaderElement = null;

        // Messages par langue
        this.messages = {
            'fr': {
                navigation: '📄 Chargement de la page',
                navigationSub: 'Application de la traduction...',
                translation: '🌍 Traduction en cours',
                translationSub: 'Merci de patienter un instant...',
                form: '📨 Envoi en cours',
                formSub: 'Traitement de votre demande...',
                loading: '⚡ Chargement',
                loadingSub: 'Préparation de la page...'
            },
            'en': {
                navigation: '📄 Loading page',
                navigationSub: 'Applying translation...',
                translation: '🌍 Translating',
                translationSub: 'Please wait a moment...',
                form: '📨 Sending',
                formSub: 'Processing your request...',
                loading: '⚡ Loading',
                loadingSub: 'Preparing page...'
            },
            'es': {
                navigation: '📄 Cargando página',
                navigationSub: 'Aplicando traducción...',
                translation: '🌍 Traduciendo',
                translationSub: 'Por favor, espere un momento...',
                form: '📨 Enviando',
                formSub: 'Procesando su solicitud...',
                loading: '⚡ Cargando',
                loadingSub: 'Preparando página...'
            },
            'de': {
                navigation: '📄 Seite laden',
                navigationSub: 'Übersetzung anwenden...',
                translation: '🌍 Übersetzen',
                translationSub: 'Bitte warten Sie einen Moment...',
                form: '📨 Senden',
                formSub: 'Verarbeitung Ihrer Anfrage...',
                loading: '⚡ Laden',
                loadingSub: 'Seite vorbereiten...'
            },
            'it': {
                navigation: '📄 Caricamento pagina',
                navigationSub: 'Applicazione traduzione...',
                translation: '🌍 Traduzione',
                translationSub: 'Attendere un momento...',
                form: '📨 Invio',
                formSub: 'Elaborazione richiesta...',
                loading: '⚡ Caricamento',
                loadingSub: 'Preparazione pagina...'
            }
        };

        this.init();
    }

    init() {
        try {
            this.detectTheme();
            this.createLoaderHTML();
            this.attachEventListeners();
            this.detectTranslationContext();
            this.log('🎨 SMM Page Loader initialisé', this.options);
        } catch (error) {
            console.error('[SMM Loader] Erreur initialisation:', error);
        }
    }

    detectTheme() {
        if (this.options.theme === 'auto') {
            // Détecter thème depuis body, localStorage ou préférence système
            const isDark = document.body.classList.contains('dark-theme') ||
                localStorage.getItem('smm_dark_mode') === 'true' ||
                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

            this.options.theme = isDark ? 'dark' : 'light';
        }
    }

    createLoaderHTML() {
        // Éviter duplication
        if (document.getElementById('smmPageLoader')) {
            this.loaderElement = document.getElementById('smmPageLoader');
            return;
        }

        const loader = document.createElement('div');
        loader.id = 'smmPageLoader';

        // Classes CSS dynamiques
        const classes = ['smm-page-loader', this.options.theme];
        if (this.options.compact) classes.push('compact');
        loader.className = classes.join(' ');

        // Animation globe
        const animationClass = this.options.animation === 'pulse' ? 'pulse' : '';

        loader.innerHTML = `
            <div class="smm-loader-content">
                <div class="smm-loader-icon">
                    <span class="smm-loader-globe ${animationClass}">🌍</span>
                </div>
                <div class="smm-loader-title" id="smmLoaderTitle">
                    ${this.getMessage('loading')}
                </div>
                <div class="smm-loader-subtitle" id="smmLoaderSubtitle">
                    ${this.getMessage('loadingSub')}
                </div>
                <div class="smm-loader-progress">
                    <div class="smm-loader-bar"></div>
                </div>
            </div>
        `;

        document.body.appendChild(loader);
        this.loaderElement = loader;
        this.log('✨ HTML loader créé');
    }

    show(messageType = 'loading', customMessage = null) {
        if (this.isActive) {
            this.log('⚠️ Loader déjà actif, ignoré');
            return;
        }

        this.startTime = Date.now();
        this.isActive = true;

        // Mise à jour message
        if (customMessage) {
            this.updateMessage(customMessage);
        } else {
            this.updateMessage({
                title: this.getMessage(messageType),
                subtitle: this.getMessage(messageType + 'Sub')
            });
        }

        // Délai avant affichage (éviter flash pour actions rapides)
        this.showTimeout = setTimeout(() => {
            if (this.isActive && this.loaderElement) {
                this.loaderElement.classList.add('active');
                this.log('🎨 Loader affiché:', messageType);
            }
        }, this.options.showDelay);

        return this;
    }

    hide(force = false) {
        if (!this.isActive) {
            this.log('⚠️ Loader déjà inactif');
            return this;
        }

        // Annuler affichage si pas encore montré
        if (this.showTimeout) {
            clearTimeout(this.showTimeout);
            this.showTimeout = null;
        }

        const elapsed = Date.now() - this.startTime;
        const remainingTime = force ? 0 : Math.max(0, this.options.minDuration - elapsed);

        this.hideTimeout = setTimeout(() => {
            if (this.loaderElement) {
                this.loaderElement.classList.remove('active');
            }
            this.isActive = false;
            this.log('✨ Loader masqué après', elapsed + remainingTime + 'ms');
        }, remainingTime);

        return this;
    }

    updateMessage(config) {
        const title = document.getElementById('smmLoaderTitle');
        const subtitle = document.getElementById('smmLoaderSubtitle');

        if (title && config.title) title.textContent = config.title;
        if (subtitle && config.subtitle) subtitle.textContent = config.subtitle;

        this.log('📝 Message mis à jour:', config);
    }

    // === INTÉGRATION NAVIGATION ===
    attachEventListeners() {
        // Intercepter clics liens avec traduction active
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (link && this.shouldShowLoader(link)) {
                this.log('🔗 Clic lien détecté:', link.href);
                this.showForNavigation(link);
            }
        });

        // Intercepter soumissions formulaires
        document.addEventListener('submit', (e) => {
            if (this.shouldShowLoader(e.target)) {
                this.log('📝 Soumission form détectée:', e.target.action);
                this.showForForm();
            }
        });

        // Hook dans widget traduction s'il existe
        this.hookTranslationWidget();

        // Masquer loader quand page entièrement chargée
        if (document.readyState !== 'complete') {
            window.addEventListener('load', () => {
                setTimeout(() => this.hide(), 500);
            });
        }
    }

    shouldShowLoader(element) {
        // Ne pas afficher si français (pas de traduction nécessaire)
        const currentLang = this.getCurrentLanguage();
        if (currentLang === 'fr') {
            this.log('🇫🇷 Langue française, pas de loader nécessaire');
            return false;
        }

        // Vérifier si lien interne nécessitant traduction
        if (element.tagName === 'A') {
            const href = element.getAttribute('href') || element.href;

            // Ignorer liens externes, ancres, javascript:, mailto:, tel:
            if (!href ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                href.startsWith('mailto:') ||
                href.startsWith('tel:') ||
                href.includes('logout')) {
                return false;
            }

            // Accepter liens internes
            return href.includes(window.location.hostname) ||
                href.startsWith('/') ||
                href.startsWith('./') ||
                href.startsWith('../') ||
                !href.includes('://');
        }

        return true;
    }

    showForNavigation(link) {
        this.show('navigation');

        // Auto-masquer après délai traduction
        setTimeout(() => {
            if (this.isActive) this.hide();
        }, this.options.translationDelay);
    }

    showForTranslation(langName = null) {
        const message = langName ? {
            title: `🌍 Traduction vers ${langName}`,
            subtitle: this.getMessage('translationSub')
        } : null;

        this.show('translation', message);

        // Auto-masquer après traduction
        setTimeout(() => {
            if (this.isActive) this.hide();
        }, this.options.translationDelay);
    }

    showForForm() {
        this.show('form');
    }

    hookTranslationWidget() {
        // Hook dans widget traduction global s'il existe
        if (window.smmTranslate && typeof window.smmTranslate.change === 'function') {
            const originalChange = window.smmTranslate.change;

            window.smmTranslate.change = (langCode, langName) => {
                this.log('🌍 Changement langue détecté:', langCode, langName);
                this.showForTranslation(langName);

                // Appeler fonction originale
                return originalChange.call(window.smmTranslate, langCode, langName);
            };

            this.log('🔗 Hook widget traduction installé');
        }

        // Hook fonction globale si elle existe
        if (typeof window.smmChangeLanguage === 'function') {
            const originalGlobal = window.smmChangeLanguage;

            window.smmChangeLanguage = (langCode, langName) => {
                this.log('🌍 Fonction globale changement langue:', langCode, langName);
                this.showForTranslation(langName);
                return originalGlobal(langCode, langName);
            };

            this.log('🔗 Hook fonction globale installé');
        }
    }

    detectTranslationContext() {
        const currentLang = this.getCurrentLanguage();

        // Si page chargée avec traduction active et pas encore complète
        if (currentLang !== 'fr' && document.readyState === 'loading') {
            this.log('🌍 Page en cours de chargement avec traduction:', currentLang);

            this.show('loading', {
                title: '🌍 Initialisation traduction',
                subtitle: 'Préparation de la page...'
            });

            // Masquer quand DOM prêt
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    if (this.isActive) this.hide();
                }, 600);
            });
        }
    }

    getCurrentLanguage() {
        // Multiples sources de détection langue
        const urlParams = new URLSearchParams(window.location.search);
        const urlLang = urlParams.get('lang');
        const cookieLang = document.cookie.match(/smm_language=([^;]*)/)?.[1];
        const storedLang = localStorage.getItem('smm_preferred_language');
        const htmlLang = document.documentElement.lang;

        return urlLang || cookieLang || storedLang || htmlLang || 'fr';
    }

    getMessage(key) {
        const currentLang = this.getCurrentLanguage();
        const messages = this.messages[currentLang] || this.messages['fr'];
        return messages[key] || `Loading... (${key})`;
    }

    log(...args) {
        if (this.options.debug) {
            console.log('[SMM Loader]', ...args);
        }
    }

    // === API PUBLIQUE ===
    destroy() {
        if (this.showTimeout) clearTimeout(this.showTimeout);
        if (this.hideTimeout) clearTimeout(this.hideTimeout);

        if (this.loaderElement) {
            this.loaderElement.remove();
        }

        this.log('🗑️ Loader détruit');
    }

    setTheme(theme) {
        this.options.theme = theme;
        if (this.loaderElement) {
            this.loaderElement.className = this.loaderElement.className
                .replace(/\b(light|dark)\b/g, '')
                .trim() + ` ${theme}`;
        }
        this.log('🎨 Thème changé:', theme);
    }

    isVisible() {
        return this.isActive;
    }
}

// === INITIALISATION AUTOMATIQUE ===
// Créer instance globale dès que DOM prêt
document.addEventListener('DOMContentLoaded', function () {
    // Éviter duplication
    if (window.smmPageLoader) return;

    // Options par défaut avec détection environnement
    const defaultOptions = {
        theme: 'auto',
        debug: window.location.hostname === 'localhost',
        compact: window.innerWidth < 640,
        translationDelay: 2200 // Légèrement plus que Google Translate
    };

    // Initialiser loader global
    window.smmPageLoader = new SmmPageLoader(defaultOptions);

    console.log('[SMM Loader] 🚀 Système loading subtil initialisé');
});

// Export pour modules ES6 si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SmmPageLoader;
}