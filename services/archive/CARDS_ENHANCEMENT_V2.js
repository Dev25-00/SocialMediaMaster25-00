/**
 * SMM Mastery - Services Cards Enhancement V2
 * Date: 13 Octobre 2025
 * 
 * AJOUT AFFICHAGE MÉTADONNÉES V2:
 * - Quality badge (High, Real, Premium)
 * - Location badge (Global, USA, etc.)
 * - Speed enrichi (from API parsing)
 * - Dripfeed badge
 * - Cancel badge
 * - Refill type détaillé
 */

// Injection dans ServicesManagerMultiline.renderServices() 
// APRÈS la ligne 661 (features.innerHTML += boxes)

// 5. ✅ QUALITY Badge (NOUVEAU V2)
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

// 6. ✅ LOCATION Badge (NOUVEAU V2)
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

// 7. ✅ SPEED Enhanced (NOUVEAU V2 - remplace détection dans nom)
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

// 8. ✅ AVERAGE TIME (NOUVEAU V2)
if (service.average_time) {
    features.innerHTML += `<span class="service-feature-item feature-time"><i class="fas fa-clock"></i> ${service.average_time}</span>`;
}

// 9. ✅ DRIPFEED Badge (NOUVEAU V2)
if (service.dripfeed === true || service.dripfeed === 1) {
    features.innerHTML += '<span class="service-feature-item feature-dripfeed"><i class="fas fa-water"></i> Dripfeed</span>';
}

// 10. ✅ CANCEL Badge (NOUVEAU V2)
if (service.cancel === true || service.cancel === 1) {
    features.innerHTML += '<span class="service-feature-item feature-cancel"><i class="fas fa-times-circle"></i> Cancellable</span>';
}

// 11. ✅ REFILL TYPE Enhanced (NOUVEAU V2 - remplace refill_days basique)
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

    // Limiter la longueur du label
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
