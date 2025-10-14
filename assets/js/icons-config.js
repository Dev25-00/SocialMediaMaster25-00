/**
 * SMM Mastery - Configuration des Icônes (Version JavaScript)
 * Date: 14 Octobre 2025
 * Version: 1.0
 * 
 * Configuration centralisée des icônes Font Awesome
 * Synchronisée avec includes/icons-config.php
 * Utilisée dans tout le projet pour cohérence visuelle
 */

const IconsConfig = {

    // ====================================
    // TIERS DE SERVICES
    // ====================================
    tier: {
        budget: {
            icon: 'fas fa-piggy-bank',
            emoji: '💰',
            color: '#6b7280',
            label: 'Budget'
        },
        standard: {
            icon: 'fas fa-star',
            emoji: '⭐',
            color: '#3b82f6',
            label: 'Standard'
        },
        premium: {
            icon: 'fas fa-gem',
            emoji: '💎',
            color: '#8b5cf6',
            label: 'Premium'
        },
        ultimate: {
            icon: 'fas fa-crown',
            emoji: '👑',
            color: '#f59e0b',
            label: 'Ultimate'
        }
    },

    // ====================================
    // QUALITÉ DE SERVICES
    // ====================================
    quality: {
        low: {
            icon: 'fas fa-arrow-down',
            emoji: '📉',
            color: '#ef4444',
            label: 'Faible'
        },
        medium: {
            icon: 'fas fa-minus',
            emoji: '➖',
            color: '#f59e0b',
            label: 'Moyenne'
        },
        high: {
            icon: 'fas fa-arrow-up',
            emoji: '📈',
            color: '#10b981',
            label: 'Haute'
        },
        premium: {
            icon: 'fas fa-gem',
            emoji: '💎',
            color: '#8b5cf6',
            label: 'Premium'
        }
    },

    // ====================================
    // PLATEFORMES SOCIALES
    // ====================================
    platform: {
        Instagram: {
            icon: 'fa-brands fa-instagram',
            color: '#E4405F',
            gradient: 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)'
        },
        YouTube: {
            icon: 'fa-brands fa-youtube',
            color: '#FF0000',
            gradient: 'linear-gradient(135deg, #FF0000 0%, #CC0000 100%)'
        },
        TikTok: {
            icon: 'fa-brands fa-tiktok',
            color: '#000000',
            gradient: 'linear-gradient(135deg, #000000 0%, #69C9D0 50%, #EE1D52 100%)'
        },
        Facebook: {
            icon: 'fa-brands fa-facebook',
            color: '#1877F2',
            gradient: 'linear-gradient(135deg, #1877F2 0%, #0d5cb6 100%)'
        },
        Twitter: {
            icon: 'fa-brands fa-twitter',
            color: '#1DA1F2',
            gradient: 'linear-gradient(135deg, #1DA1F2 0%, #0d8bd9 100%)'
        },
        LinkedIn: {
            icon: 'fa-brands fa-linkedin',
            color: '#0A66C2',
            gradient: 'linear-gradient(135deg, #0A66C2 0%, #004182 100%)'
        },
        Telegram: {
            icon: 'fa-solid fa-paper-plane',
            color: '#0088cc',
            gradient: 'linear-gradient(135deg, #0088cc 0%, #006699 100%)'
        },
        Spotify: {
            icon: 'fa-brands fa-spotify',
            color: '#1DB954',
            gradient: 'linear-gradient(135deg, #1DB954 0%, #1ed760 100%)'
        },
        Snapchat: {
            icon: 'fa-brands fa-snapchat',
            color: '#FFFC00',
            gradient: 'linear-gradient(135deg, #FFFC00 0%, #FFF700 100%)'
        },
        Twitch: {
            icon: 'fa-brands fa-twitch',
            color: '#9146FF',
            gradient: 'linear-gradient(135deg, #9146FF 0%, #772ce8 100%)'
        },
        Discord: {
            icon: 'fa-brands fa-discord',
            color: '#5865F2',
            gradient: 'linear-gradient(135deg, #5865F2 0%, #4752c4 100%)'
        },
        Reddit: {
            icon: 'fa-brands fa-reddit',
            color: '#FF4500',
            gradient: 'linear-gradient(135deg, #FF4500 0%, #cc3700 100%)'
        },
        Pinterest: {
            icon: 'fa-brands fa-pinterest',
            color: '#E60023',
            gradient: 'linear-gradient(135deg, #E60023 0%, #bd081c 100%)'
        },
        SoundCloud: {
            icon: 'fa-brands fa-soundcloud',
            color: '#FF5500',
            gradient: 'linear-gradient(135deg, #FF5500 0%, #ff3300 100%)'
        },
        Medium: {
            icon: 'fa-brands fa-medium',
            color: '#000000',
            gradient: 'linear-gradient(135deg, #000000 0%, #333333 100%)'
        },
        Quora: {
            icon: 'fa-brands fa-quora',
            color: '#B92B27',
            gradient: 'linear-gradient(135deg, #B92B27 0%, #8b1f1c 100%)'
        },
        Tumblr: {
            icon: 'fa-brands fa-tumblr',
            color: '#35465C',
            gradient: 'linear-gradient(135deg, #35465C 0%, #2a3849 100%)'
        },
        default: {
            icon: 'fa-solid fa-globe',
            color: '#6b7280',
            gradient: 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)'
        }
    },

    // ====================================
    // STATUTS
    // ====================================
    status: {
        success: {
            icon: 'fas fa-check-circle',
            color: '#10b981',
            label: 'Succès'
        },
        error: {
            icon: 'fas fa-times-circle',
            color: '#ef4444',
            label: 'Erreur'
        },
        warning: {
            icon: 'fas fa-exclamation-triangle',
            color: '#f59e0b',
            label: 'Attention'
        },
        info: {
            icon: 'fas fa-info-circle',
            color: '#3b82f6',
            label: 'Information'
        },
        pending: {
            icon: 'fas fa-clock',
            color: '#6b7280',
            label: 'En attente'
        },
        processing: {
            icon: 'fas fa-spinner fa-spin',
            color: '#3b82f6',
            label: 'Traitement'
        },
        completed: {
            icon: 'fas fa-check-double',
            color: '#10b981',
            label: 'Terminé'
        },
        cancelled: {
            icon: 'fas fa-ban',
            color: '#ef4444',
            label: 'Annulé'
        }
    },

    // ====================================
    // MÉTRIQUES
    // ====================================
    metric: {
        followers: {
            icon: 'fas fa-users',
            color: '#3b82f6',
            label: 'Abonnés'
        },
        likes: {
            icon: 'fas fa-heart',
            color: '#ef4444',
            label: 'J\'aime'
        },
        views: {
            icon: 'fas fa-eye',
            color: '#8b5cf6',
            label: 'Vues'
        },
        comments: {
            icon: 'fas fa-comments',
            color: '#10b981',
            label: 'Commentaires'
        },
        shares: {
            icon: 'fas fa-share-alt',
            color: '#f59e0b',
            label: 'Partages'
        },
        subscribers: {
            icon: 'fas fa-user-plus',
            color: '#3b82f6',
            label: 'Abonnés'
        }
    },

    // ====================================
    // ACTIONS
    // ====================================
    action: {
        add: { icon: 'fas fa-plus-circle', color: '#10b981' },
        edit: { icon: 'fas fa-edit', color: '#3b82f6' },
        delete: { icon: 'fas fa-trash-alt', color: '#ef4444' },
        view: { icon: 'fas fa-eye', color: '#6b7280' },
        download: { icon: 'fas fa-download', color: '#8b5cf6' },
        upload: { icon: 'fas fa-upload', color: '#3b82f6' },
        copy: { icon: 'fas fa-copy', color: '#6b7280' },
        share: { icon: 'fas fa-share-alt', color: '#3b82f6' },
        favorite: { icon: 'fas fa-star', color: '#f59e0b' },
        unfavorite: { icon: 'far fa-star', color: '#6b7280' }
    },

    // ====================================
    // HELPER METHODS
    // ====================================

    /**
     * Obtenir l'icône d'un tier
     * @param {string} tier - Nom du tier (budget, standard, premium, ultimate)
     * @param {boolean} withEmoji - Retourner emoji au lieu d'icône FA
     * @returns {string} Classe d'icône ou emoji
     */
    getTierIcon(tier, withEmoji = false) {
        const t = this.tier[tier?.toLowerCase()] || this.tier.standard;
        return withEmoji ? t.emoji : t.icon;
    },

    /**
     * Obtenir la couleur d'un tier
     * @param {string} tier - Nom du tier
     * @returns {string} Code couleur hex
     */
    getTierColor(tier) {
        const t = this.tier[tier?.toLowerCase()] || this.tier.standard;
        return t.color;
    },

    /**
     * Obtenir l'icône d'une plateforme
     * @param {string} platform - Nom de la plateforme
     * @returns {string} Classe d'icône Font Awesome
     */
    getPlatformIcon(platform) {
        const p = this.platform[platform] || this.platform.default;
        return p.icon;
    },

    /**
     * Obtenir la couleur d'une plateforme
     * @param {string} platform - Nom de la plateforme
     * @param {boolean} useGradient - Retourner gradient au lieu de couleur solide
     * @returns {string} Code couleur ou gradient CSS
     */
    getPlatformColor(platform, useGradient = false) {
        const p = this.platform[platform] || this.platform.default;
        return useGradient ? p.gradient : p.color;
    },

    /**
     * Obtenir l'icône d'un statut
     * @param {string} status - Nom du statut
     * @returns {string} Classe d'icône Font Awesome
     */
    getStatusIcon(status) {
        const s = this.status[status?.toLowerCase()] || this.status.info;
        return s.icon;
    },

    /**
     * Obtenir la couleur d'un statut
     * @param {string} status - Nom du statut
     * @returns {string} Code couleur hex
     */
    getStatusColor(status) {
        const s = this.status[status?.toLowerCase()] || this.status.info;
        return s.color;
    },

    /**
     * Générer le HTML d'une icône
     * @param {string} iconClass - Classes Font Awesome
     * @param {Object} options - Options (color, size, style)
     * @returns {string} HTML de l'icône
     */
    renderIcon(iconClass, options = {}) {
        const {
            color = '',
            size = '',
            style = '',
            extraClasses = ''
        } = options;

        const styleAttr = [
            color ? `color: ${color}` : '',
            size ? `font-size: ${size}` : '',
            style
        ].filter(Boolean).join('; ');

        return `<i class="${iconClass} ${extraClasses}" ${styleAttr ? `style="${styleAttr}"` : ''}></i>`;
    },

    /**
     * Générer un badge tier avec icône
     * @param {string} tier - Nom du tier
     * @returns {string} HTML du badge complet
     */
    renderTierBadge(tier) {
        const t = this.tier[tier?.toLowerCase()] || this.tier.standard;
        return `
            <span class="tier-badge tier-${tier?.toLowerCase()}" style="color: ${t.color}">
                <i class="${t.icon}"></i>
                <span>${t.label}</span>
            </span>
        `;
    },

    /**
     * Générer un badge plateforme avec icône
     * @param {string} platform - Nom de la plateforme
     * @param {boolean} showName - Afficher le nom
     * @returns {string} HTML du badge complet
     */
    renderPlatformBadge(platform, showName = true) {
        const p = this.platform[platform] || this.platform.default;
        return `
            <span class="platform-badge" style="color: ${p.color}">
                <i class="${p.icon}"></i>
                ${showName ? `<span>${platform}</span>` : ''}
            </span>
        `;
    }
};

// Export pour modules ES6
if (typeof module !== 'undefined' && module.exports) {
    module.exports = IconsConfig;
}

// Export global pour utilisation directe
window.IconsConfig = IconsConfig;
