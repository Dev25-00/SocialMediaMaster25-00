/**
 * SMM Mastery - Services Cards Enhancement
 * Date: 14 Octobre 2025
 * Version: 3.0 - Réorganisé et commenté
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\
 * 
 * FONCTIONNALITÉS:
 * - Affichage enrichi des métadonnées de service dans les cards
 * - Badges de qualité (High, Real, Premium, Verified)
 * - Badges de localisation (Global, USA, Europe, etc.)
 * - Indicateurs de vitesse (Instant, Fast, Slow)
 * - Temps moyen d'exécution
 * - Support Dripfeed et annulation
 * - Types de refill détaillés (Lifetime, Button, Days)
 * 
 * INJECTION:
 * Ce code s'injecte dans ServicesManager.renderServices()
 * après la génération des features boxes de base
 * 
 * UTILISATION:
 * Les fonctions ci-dessous sont appelées automatiquement lors du
 * rendu de chaque service card dans services-manager.js
 */

/* ========================================
   FONCTIONS D'ENRICHISSEMENT DES CARDS
   ======================================== */

/**
 * AFFICHAGE BADGE QUALITÉ
 * 
 * Ajoute un badge visuel indiquant le niveau de qualité du service
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter le badge
 * 
 * Qualités supportées:
 * - High: Service haute qualité (⭐)
 * - Real: Utilisateurs réels (✓)
 * - Premium: Service premium (💎)
 * - Verified: Service vérifié (🏆)
 * 
 * Classes CSS: .feature-quality, .feature-quality-high
 */
if (service.quality) {
    const qualityIcons = {
        'high': 'fas fa-star',
        'real': 'fas fa-user-check',
        'premium': 'fas fa-gem',
        'verified': 'fas fa-certificate'
    };
    const qualityLower = service.quality.toLowerCase();
    const icon = qualityIcons[qualityLower] || 'fas fa-check-circle';
    const qualityClass = qualityLower === 'high' || qualityLower === 'premium' ? 'feature-quality-high' : 'feature-quality';
    features.innerHTML += `<span class="service-feature-item ${qualityClass}"><i class="${icon}"></i> ${service.quality}</span>`;
}

/**
 * AFFICHAGE BADGE LOCALISATION
 * 
 * Ajoute un badge indiquant la zone géographique ciblée
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter le badge
 * 
 * Localisations supportées:
 * - Global/Worldwide: Service mondial (🌍)
 * - USA/US: États-Unis (🇺🇸)
 * - Europe: Zone européenne (🇪🇺)
 * - Asia: Zone asiatique (🌏)
 * - Autres: Marqueur générique (📍)
 * 
 * Classe CSS: .feature-location
 */
if (service.location) {
    const locationIcons = {
        'global': 'fas fa-globe',
        'worldwide': 'fas fa-globe-americas',
        'usa': 'fas fa-flag-usa',
        'us': 'fas fa-flag-usa',
        'europe': 'fas fa-flag',
        'asia': 'fas fa-globe-asia'
    };
    const locationLower = service.location.toLowerCase();
    const icon = locationIcons[locationLower] || 'fas fa-map-marker-alt';
    features.innerHTML += `<span class="service-feature-item feature-location"><i class="${icon}"></i> ${service.location}</span>`;
}

/**
 * AFFICHAGE INDICATEUR DE VITESSE
 * 
 * Affiche la vitesse d'exécution du service avec icône appropriée
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter l'indicateur
 * 
 * Vitesses détectées:
 * - Instant: Livraison immédiate (⚡)
 * - Fast: Livraison rapide (🚀)
 * - Slow: Livraison lente (⏳)
 * - Autre: Vitesse standard (📊)
 * 
 * Classe CSS: .feature-speed
 */
if (service.speed && service.speed !== 'Variable') {
    const speedLower = service.speed.toLowerCase();
    let icon = 'fas fa-tachometer-alt';
    let label = service.speed;

    if (speedLower.includes('instant')) {
        icon = 'fas fa-bolt';
        label = 'Instant';
    } else if (speedLower.includes('fast')) {
        icon = 'fas fa-rocket';
    } else if (speedLower.includes('slow')) {
        icon = 'fas fa-hourglass-half';
    }

    features.innerHTML += `<span class="service-feature-item feature-speed"><i class="${icon}"></i> ${label}</span>`;
}

/**
 * AFFICHAGE TEMPS MOYEN D'EXÉCUTION
 * 
 * Affiche le temps moyen estimé pour la livraison complète
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter l'indicateur
 * 
 * Format: "X hours", "X-Y days", etc.
 * Classe CSS: .feature-time
 */
if (service.average_time) {
    features.innerHTML += `<span class="service-feature-item feature-time"><i class="fas fa-clock"></i> ${service.average_time}</span>`;
}

/**
 * AFFICHAGE BADGE DRIPFEED
 * 
 * Indique si le service supporte le dripfeed (livraison progressive)
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter le badge
 * 
 * Dripfeed: Permet de répartir la livraison sur plusieurs jours
 * pour un aspect plus naturel (💧)
 * 
 * Classe CSS: .feature-dripfeed
 */
if (service.dripfeed === true || service.dripfeed === 1) {
    features.innerHTML += '<span class="service-feature-item feature-dripfeed"><i class="fas fa-water"></i> Dripfeed</span>';
}

/**
 * AFFICHAGE BADGE ANNULATION
 * 
 * Indique si la commande peut être annulée après création
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter le badge
 * 
 * Cancel: Permet d'annuler une commande en cours (❌)
 * Utile pour les services longs
 * 
 * Classe CSS: .feature-cancel
 */
if (service.cancel === true || service.cancel === 1) {
    features.innerHTML += '<span class="service-feature-item feature-cancel"><i class="fas fa-times-circle"></i> Cancellable</span>';
}

/**
 * AFFICHAGE TYPE DE REFILL (REMPLISSAGE)
 * 
 * Affiche la politique de remplacement automatique en cas de perte
 * 
 * @param {object} service - Objet service contenant les données
 * @param {HTMLElement} features - Container où injecter l'indicateur
 * 
 * Types de refill:
 * - Lifetime/Guaranteed: Remplacement à vie (♾️)
 * - Button: Remplacement sur demande (🔄)
 * - X Days: Remplacement pendant X jours (📅)
 * 
 * Classe CSS: .feature-refill
 */
if (service.refill_type) {
    const refillLower = service.refill_type.toLowerCase();
    let icon = 'fas fa-redo';
    let label = service.refill_type;

    if (refillLower.includes('lifetime') || refillLower.includes('guaranteed')) {
        icon = 'fas fa-infinity';
        label = refillLower.includes('button') ? 'Button + Lifetime' : 'Lifetime';
    } else if (refillLower.includes('button')) {
        icon = 'fas fa-sync-alt';
        label = 'Button Refill';
    } else if (refillLower.includes('days')) {
        icon = 'fas fa-calendar-alt';
    }

    // Limiter la longueur du label pour éviter débordement
    if (label.length > 20) {
        label = label.substring(0, 20) + '...';
    }

    features.innerHTML += `<span class="service-feature-item feature-refill"><i class="${icon}"></i> ${label}</span>`;
}

// CSS STYLES À AJOUTER dans filters-2lines.css:
/*
.feature-quality-high {
    background: linear-gradient(135deg, #f59e0b22, #f59e0b11) !important;
    color: #f59e0b !important;
    border-color: #f59e0b33 !important;
}

.feature-location {
    background: linear-gradient(135deg, #3b82f622, #3b82f611) !important;
    color: #3b82f6 !important;
    border-color: #3b82f633 !important;
}

.feature-speed {
    background: linear-gradient(135deg, #8b5cf622, #8b5cf611) !important;
    color: #8b5cf6 !important;
    border-color: #8b5cf633 !important;
}

.feature-time {
    background: linear-gradient(135deg, #10b98122, #10b98111) !important;
    color: #10b981 !important;
    border-color: #10b98133 !important;
}

.feature-dripfeed {
    background: linear-gradient(135deg, #06b6d422, #06b6d411) !important;
    color: #06b6d4 !important;
    border-color: #06b6d433 !important;
}

.feature-cancel {
    background: linear-gradient(135deg, #ef444422, #ef444411) !important;
    color: #ef4444 !important;
    border-color: #ef444433 !important;
}

.feature-refill {
    background: linear-gradient(135deg, #ec489922, #ec489911) !important;
    color: #ec4899 !important;
    border-color: #ec489933 !important;
}
*/
