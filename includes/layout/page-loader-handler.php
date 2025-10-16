<?php
/**
 * SMM Page Loader Handler - Intégration système loading
 * Créé le 14 Octobre 2025
 * Documentation: SYSTEME_LOADING_SUBTIL_TRADUCTION.md
 * 
 * @description Gère l'inclusion automatique du loader pour pages avec traduction
 * @usage Include dans dashboard-header-simple.php et public-header.php
 */

/**
 * Inclut le système de loading subtil si traduction nécessaire
 * 
 * @param array $options Options de configuration loader
 * @return void
 */
function includePageLoader($options = []) {
    // Détecter si traduction nécessaire
    $currentLang = $_GET['lang'] ?? $_COOKIE['smm_language'] ?? 'fr';
    $needsTranslation = $currentLang !== 'fr';
    
    // Détecter thème utilisateur
    $isDarkMode = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'];
    $theme = $isDarkMode ? 'dark' : 'light';
    
    // Options par défaut
    $defaultOptions = [
        'theme' => $theme,
        'translationDelay' => 2200,
        'debug' => ($_SERVER['HTTP_HOST'] === 'localhost'),
        'compact' => false,
        'animation' => 'float'
    ];
    
    $config = array_merge($defaultOptions, $options);
    
    // Toujours inclure (même en français pour navigation vers autres langues)
    echo generatePageLoaderHTML($config, $currentLang, $needsTranslation);
}

/**
 * Génère le HTML complet du loader
 * 
 * @param array $config Configuration
 * @param string $currentLang Langue actuelle
 * @param bool $needsTranslation Si traduction nécessaire
 * @return string HTML complet
 */
function generatePageLoaderHTML($config, $currentLang, $needsTranslation) {
    // Chemins des assets
    $cssPath = getAssetPath('styles/page-loader.css');
    $jsPath = getAssetPath('js/page-loader.js');
    
    // Configuration JavaScript
    $jsConfig = json_encode($config, JSON_UNESCAPED_UNICODE);
    
    ob_start();
    ?>
    
    <!-- SMM Page Loader - Système loading subtil -->
    <?php if ($needsTranslation): ?>
    <!-- Traduction active: <?= htmlspecialchars($currentLang) ?> -->
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?= $cssPath ?>">
    
    <script>
    // Configuration loader
    window.smmLoaderConfig = <?= $jsConfig ?>;
    
    // Log initialization
    console.log('[SMM Loader] 🎨 Configuration chargée:', window.smmLoaderConfig);
    <?php if ($needsTranslation): ?>
    console.log('[SMM Loader] 🌍 Traduction nécessaire pour:', '<?= $currentLang ?>');
    <?php endif; ?>
    </script>
    
    <script src="<?= $jsPath ?>"></script>
    
    <?php if ($needsTranslation): ?>
    <script>
    // Hook spécial pour traduction active
    document.addEventListener('DOMContentLoaded', function() {
        if (window.smmPageLoader) {
            // Afficher loader initial si page pas encore traduite
            const isPageTranslated = document.documentElement.lang === '<?= $currentLang ?>';
            if (!isPageTranslated) {
                window.smmPageLoader.show('loading', {
                    title: '🌍 Application traduction',
                    subtitle: 'Préparation de la page...'
                });
                
                // Masquer après délai
                setTimeout(() => {
                    if (window.smmPageLoader.isVisible()) {
                        window.smmPageLoader.hide();
                    }
                }, 1500);
            }
        }
    });
    </script>
    <?php endif; ?>
    
    <?php
    return ob_get_clean();
}

/**
 * Obtient le chemin correct des assets selon structure projet
 * 
 * @param string $relativePath Chemin relatif depuis includes/
 * @return string Chemin complet vers asset
 */
function getAssetPath($relativePath) {
    // Détecter si on est dans un sous-dossier
    $currentDir = dirname($_SERVER['SCRIPT_NAME']);
    $includesPath = '../includes/';
    
    // Ajuster selon profondeur
    $depth = substr_count($currentDir, '/') - 1;
    if ($depth > 1) {
        $includesPath = str_repeat('../', $depth - 1) . 'includes/';
    }
    
    // Chemin physique correct pour filemtime (depuis includes/)
    $physicalPath = dirname(__DIR__) . '/' . $relativePath;
    
    // Vérifier si le fichier existe
    if (file_exists($physicalPath)) {
        return $includesPath . $relativePath . '?v=' . filemtime($physicalPath);
    } else {
        // Fallback sans version si fichier introuvable
        return $includesPath . $relativePath;
    }
}

/**
 * Version simplifiée pour inclusion rapide
 * Utilise dans les headers existants
 * 
 * @return void
 */
function quickPageLoader() {
    $currentLang = $_GET['lang'] ?? $_COOKIE['smm_language'] ?? 'fr';
    
    if ($currentLang !== 'fr') {
        includePageLoader(['compact' => true]);
    } else {
        // Même en français, inclure pour navigation vers autres langues
        includePageLoader(['showDelay' => 500]);
    }
}

/**
 * Hook pour widget traduction existant
 * À insérer dans google-translate-widget-v3-final.php
 * 
 * @return string JavaScript hook
 */
function getTranslationWidgetHook() {
    return "
    <!-- Hook SMM Page Loader pour widget traduction -->
    <script>
    // Hook dans fonction smmChangeLanguage si elle existe
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.smmChangeLanguage === 'function' && window.smmPageLoader) {
            const originalChange = window.smmChangeLanguage;
            
            window.smmChangeLanguage = function(langCode, langName) {
                // Afficher loader avant traduction
                window.smmPageLoader.showForTranslation(langName);
                
                // Appeler fonction originale
                const result = originalChange.apply(this, arguments);
                
                console.log('[SMM Loader] 🔗 Hook widget traduction activé:', langCode, langName);
                return result;
            };
        }
    });
    </script>
    ";
}

/**
 * Injecte CSS inline si fichier externe indisponible
 * Fallback pour environnements contraints
 * 
 * @return void
 */
function inlinePageLoaderCSS() {
    $cssPath = __DIR__ . '/styles/page-loader.css';
    
    if (file_exists($cssPath)) {
        echo "<style>\n";
        echo file_get_contents($cssPath);
        echo "\n</style>\n";
    }
}

/**
 * Détecte si loader doit être affiché selon contexte
 * 
 * @return array Informations contexte
 */
function detectLoaderContext() {
    $context = [
        'current_lang' => $_GET['lang'] ?? $_COOKIE['smm_language'] ?? 'fr',
        'needs_translation' => false,
        'is_ajax' => false,
        'is_mobile' => false,
        'page_type' => 'unknown'
    ];
    
    // Détection traduction nécessaire
    $context['needs_translation'] = $context['current_lang'] !== 'fr';
    
    // Détection AJAX
    $context['is_ajax'] = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    // Détection mobile
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $context['is_mobile'] = !empty($userAgent) && 
                           preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent);
    
    // Type de page
    $scriptName = basename($_SERVER['SCRIPT_NAME'] ?? 'unknown.php', '.php');
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    if (strpos($requestUri, '/admin/') !== false) {
        $context['page_type'] = 'admin';
    } elseif (strpos($requestUri, '/dashboard/') !== false) {
        $context['page_type'] = 'dashboard';
    } elseif (in_array($scriptName, ['login', 'register', 'logout'])) {
        $context['page_type'] = 'auth';
    } else {
        $context['page_type'] = 'public';
    }
    
    return $context;
}

/**
 * Génère configuration adaptée selon contexte
 * 
 * @param array $context Contexte détecté
 * @return array Configuration optimisée
 */
function getOptimizedConfig($context) {
    $config = [
        'theme' => 'light',
        'translationDelay' => 2200,
        'debug' => false,
        'compact' => false,
        'animation' => 'float'
    ];
    
    // Adaptations contexte
    if ($context['is_mobile']) {
        $config['compact'] = true;
        $config['animation'] = 'pulse'; // Plus performant mobile
    }
    
    if ($context['page_type'] === 'admin') {
        $config['theme'] = 'dark';
        $config['translationDelay'] = 1800; // Admin plus rapide
    }
    
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') {
        $config['debug'] = true;
    }
    
    return $config;
}

/**
 * API complète pour inclusion automatique intelligente
 * Une seule fonction à appeler dans les headers
 * 
 * @param array $customOptions Options personnalisées
 * @return void
 */
function smartPageLoader($customOptions = []) {
    $context = detectLoaderContext();
    $config = getOptimizedConfig($context);
    $finalConfig = array_merge($config, $customOptions);
    
    // Log contexte si debug
    if ($finalConfig['debug']) {
        echo "<!-- SMM Loader Debug: " . json_encode($context) . " -->\n";
    }
    
    // Inclure loader avec configuration optimisée
    includePageLoader($finalConfig);
    
    // Hook widget si page avec traduction
    if ($context['needs_translation']) {
        echo getTranslationWidgetHook();
    }
}

// === UTILISATION SIMPLE ===
// Dans dashboard-header-simple.php:
// include_once __DIR__ . '/../includes/layout/page-loader-handler.php';
// smartPageLoader();

// Dans public-header.php:
// include_once __DIR__ . '/includes/layout/page-loader-handler.php';
// smartPageLoader(['theme' => 'light']);
?>