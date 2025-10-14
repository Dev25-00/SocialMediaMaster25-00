/**
 * SMM Mastery - Services Manager Multi-lignes
 * Date: 12 Octobre 2025
 * Version: 2.0
 * Gestion des filtres multi-lignes et du grid responsive
 */

const ServicesManagerMultiline = {
    filters: {
        platform: '',
        tier: '',
        actionType: '',
        dropRate: '',      // No Drop, Low Drop, High Drop
        refill: '',        // 0, 30, 90, 365, lifetime
        priceMin: null,
        priceMax: null,
        sort: 'price-asc'  // Par défaut: prix croissant
    },

    currentPage: 1,
    itemsPerPage: 20,
    isLoading: false,
    hasMore: true,
    totalDisplayed: 0,  // Nombre total de services affichés (pour infinite scroll)
    totalAvailable: 0,  // Nombre total de services disponibles (depuis API)
    abortController: null,  // Contrôleur pour annuler les requêtes en cours
    loadTimeout: null,      // Timeout pour debounce des filtres rapides

    // Configuration des plateformes avec icônes et couleurs
    platformConfig: {
        // Principales plateformes
        'Instagram': { icon: 'fab fa-instagram', color: '#E4405F' },
        'YouTube': { icon: 'fab fa-youtube', color: '#FF0000' },
        'TikTok': { icon: 'fab fa-tiktok', color: '#000000' },
        'Facebook': { icon: 'fab fa-facebook', color: '#1877F2' },
        'Twitter': { icon: 'fab fa-twitter', color: '#1DA1F2' },
        'X': { icon: 'fab fa-x-twitter', color: '#000000' },
        'LinkedIn': { icon: 'fab fa-linkedin', color: '#0A66C2' },
        'Telegram': { icon: 'fas fa-paper-plane', color: '#0088cc' },
        'Spotify': { icon: 'fab fa-spotify', color: '#1DB954' },
        'Snapchat': { icon: 'fab fa-snapchat', color: '#FFFC00' },
        'Twitch': { icon: 'fab fa-twitch', color: '#9146FF' },
        'Discord': { icon: 'fab fa-discord', color: '#5865F2' },
        'Reddit': { icon: 'fab fa-reddit', color: '#FF4500' },
        'Pinterest': { icon: 'fab fa-pinterest', color: '#E60023' },
        'Threads': { icon: 'fas fa-at', color: '#000000' },
        'WhatsApp': { icon: 'fab fa-whatsapp', color: '#25D366' },

        // ✅ NOUVELLES PLATEFORMES V2 (13/10/2025)
        'Kick': { icon: 'fas fa-play-circle', color: '#53fc18' },
        'Rumble': { icon: 'fas fa-video', color: '#85C742' },
        'BlueSky': { icon: 'fas fa-cloud', color: '#1185fe' },
        'Kwai': { icon: 'fas fa-film', color: '#FF6B00' },
        'Truth Social': { icon: 'fas fa-bullhorn', color: '#E81C28' },
        'Audiomack': { icon: 'fas fa-headphones', color: '#FFA200' },
        'Quora': { icon: 'fab fa-quora', color: '#B92B27' },
        'Tumblr': { icon: 'fab fa-tumblr', color: '#35465C' },
        'SoundCloud': { icon: 'fab fa-soundcloud', color: '#FF5500' },
        'Medium': { icon: 'fab fa-medium', color: '#000000' },
        'Rutube': { icon: 'fas fa-play', color: '#2596be' },
        'Apple Music': { icon: 'fab fa-apple', color: '#FA243C' },
        'Chzzk': { icon: 'fas fa-broadcast-tower', color: '#00E7A0' },
        'Square': { icon: 'fas fa-square', color: '#3E4348' },
        'Website': { icon: 'fas fa-globe', color: '#6366f1' },
        'Mobile': { icon: 'fas fa-mobile-alt', color: '#8b5cf6' },
        'Worldwide': { icon: 'fas fa-globe-americas', color: '#10b981' }
    },

    // Configuration des types d'action avec icônes et couleurs
    actionConfig: {
        'followers': { icon: 'fas fa-users', color: '#8B5CF6', label: 'Followers' },
        'likes': { icon: 'fas fa-heart', color: '#EC4899', label: 'Likes' },
        'views': { icon: 'fas fa-eye', color: '#3B82F6', label: 'Views' },
        'subscribers': { icon: 'fas fa-user-plus', color: '#EF4444', label: 'Subscribers' },
        'comments': { icon: 'fas fa-comment', color: '#10B981', label: 'Comments' },
        'shares': { icon: 'fas fa-share-alt', color: '#F59E0B', label: 'Shares' }
    },

    // Fonction helper pour obtenir la config d'une plateforme
    getPlatformConfig(platformName) {
        return this.platformConfig[platformName] || {
            icon: 'fas fa-globe',
            color: '#6b7280'
        };
    },

    // Fonction helper pour obtenir la config d'une action
    getActionConfig(actionType) {
        return this.actionConfig[actionType] || null;
    },

    init() {
        console.log('🚀 ServicesManagerMultiline v2.1 initialized');
        this.attachEvents();
        // this.reorganizeMobileFilters(); // Désactivé - géré en CSS maintenant
        this.loadServices();
        this.setupInfiniteScroll();
        this.setupStickyDetection(); // Détecter sticky pour effet visuel
    },

    /**
     * Réorganise les filtres pour mobile: déplace Refill dans ligne 1 colonne 3
     * DÉSACTIVÉ - La disposition est maintenant gérée entièrement en CSS
     */
    reorganizeMobileFilters() {
        // Fonction désactivée - le layout mobile est géré en CSS dans filters-2lines.css
        return;

        /* Code original conservé pour référence
        if (window.innerWidth > 599) return; // Seulement sur mobile
        
        const actionsDropGroup = document.querySelector('.filter-actions-drop-group');
        const refillGroup = document.querySelector('.filters-row-secondary > .filter-group-multiline:nth-child(1)');
        
        if (!actionsDropGroup || !refillGroup) return;
        
        // Vérifier si déjà déplacé pour éviter doublons
        if (document.getElementById('refillFilterMobile')) {
            console.log('⚠️ Refill déjà déplacé, skip');
            return;
        }
        
        // Cloner le select refill
        const refillSelect = refillGroup.querySelector('.filter-select-multiline');
        if (!refillSelect) return;
        
        // Créer un nouveau subgroup pour Refill dans colonne 3
        const refillSubgroup = document.createElement('div');
        refillSubgroup.className = 'filter-subgroup-multiline';
        
        const refillClone = refillSelect.cloneNode(true);
        refillClone.id = 'refillFilterMobile'; // Nouvel ID pour éviter conflits
        
        refillSubgroup.appendChild(refillClone);
        actionsDropGroup.appendChild(refillSubgroup);
        
        // Attacher l'event au clone
        refillClone.addEventListener('change', (e) => {
            this.filters.refill = e.target.value;
            this.reloadWithFilters();
        });
        
        // Masquer l'original
        refillGroup.style.display = 'none';
        */

        console.log('✅ Refill déplacé dans ligne 1 colonne 3 (mobile)');
    },

    setupStickyDetection() {
        const filtersBar = document.querySelector('.services-filters-multiline');
        if (!filtersBar) return;

        // Méthode 1: Intersection Observer (moderne)
        const observer = new IntersectionObserver(
            ([entry]) => {
                // Sticky actif quand sort de la vue normale
                if (entry.boundingClientRect.y <= 0) {
                    filtersBar.classList.add('is-stuck');
                } else {
                    filtersBar.classList.remove('is-stuck');
                }
            },
            { threshold: [0, 1] }
        );

        observer.observe(filtersBar);

        // Méthode 2: Scroll fallback (compatibilité)
        let lastScrollY = window.scrollY;
        window.addEventListener('scroll', () => {
            const rect = filtersBar.getBoundingClientRect();
            // Si top = 0 ou 55px (mobile), sticky est actif
            if (rect.top <= 5) {
                filtersBar.classList.add('is-stuck');
            } else {
                filtersBar.classList.remove('is-stuck');
            }
            lastScrollY = window.scrollY;
        }, { passive: true });
    },

    attachEvents() {
        // Plateformes (incluant le label "All")
        document.querySelectorAll('.platform-btn-multiline').forEach(btn => {
            btn.addEventListener('click', () => {
                const platform = btn.dataset.platform || '';

                // Retirer active de tous les boutons plateformes (y compris le label)
                document.querySelectorAll('.platform-btn-multiline').forEach(b => b.classList.remove('active'));

                // Ajouter active au bouton cliqué
                btn.classList.add('active');

                this.filters.platform = platform;
                this.reloadWithFilters();
            });
        });

        // Tiers (incluant le label "All")
        document.querySelectorAll('.tier-btn-multiline').forEach(btn => {
            btn.addEventListener('click', () => {
                const tier = btn.dataset.tier || '';

                // Retirer active de tous les boutons tiers (y compris le label)
                document.querySelectorAll('.tier-btn-multiline').forEach(b => b.classList.remove('active'));

                // Ajouter active au bouton cliqué
                btn.classList.add('active');

                this.filters.tier = tier;
                this.reloadWithFilters();
            });
        });

        // Type d'action
        const actionTypeFilter = document.getElementById('actionTypeFilter');
        if (actionTypeFilter) {
            actionTypeFilter.addEventListener('change', (e) => {
                this.filters.actionType = e.target.value;
                // Ajouter classe active si une option est sélectionnée
                e.target.classList.toggle('active', e.target.value !== '');
                this.reloadWithFilters();
            });
        }

        // Drop Rate
        const dropRateFilter = document.getElementById('dropRateFilter');
        if (dropRateFilter) {
            dropRateFilter.addEventListener('change', (e) => {
                this.filters.dropRate = e.target.value;
                // Ajouter classe active si une option est sélectionnée
                e.target.classList.toggle('active', e.target.value !== '');
                this.reloadWithFilters();
            });
        }

        // Refill (en jours)
        const refillFilter = document.getElementById('refillFilter');
        if (refillFilter) {
            refillFilter.addEventListener('change', (e) => {
                this.filters.refill = e.target.value;
                // Ajouter classe active si une option est sélectionnée
                e.target.classList.toggle('active', e.target.value !== '');
                this.reloadWithFilters();
            });
        }

        // Prix Min
        const priceMin = document.getElementById('priceMin');
        if (priceMin) {
            priceMin.addEventListener('change', (e) => {
                const val = parseFloat(e.target.value);
                this.filters.priceMin = (val && val > 0) ? val : null;
                // Ajouter classe active si une valeur est entrée
                e.target.classList.toggle('active', val && val > 0);
                this.reloadWithFilters();
            });
        }

        // Prix Max
        const priceMax = document.getElementById('priceMax');
        if (priceMax) {
            priceMax.addEventListener('change', (e) => {
                const val = parseFloat(e.target.value);
                this.filters.priceMax = (val && val > 0) ? val : null;
                // Ajouter classe active si une valeur est entrée
                e.target.classList.toggle('active', val && val > 0);
                this.reloadWithFilters();
            });
        }

        // Tri
        const sortSelect = document.getElementById('sortSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', (e) => {
                this.filters.sort = e.target.value;
                this.reloadWithFilters();
            });
        }

        // Reset
        const resetBtn = document.getElementById('resetFiltersBtn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.resetFilters();
            });
        }
    },

    resetFilters() {
        // Reset des valeurs
        this.filters = {
            platform: '',
            tier: '',
            actionType: '',
            dropRate: '',
            refill: '',
            priceMin: null,
            priceMax: null,
            sort: 'price-asc'  // Reset vers prix croissant
        };

        // Reset UI
        // Plateformes: activer uniquement le label "All" (premier bouton = label)
        document.querySelectorAll('.platform-btn-multiline').forEach((btn, index) => {
            btn.classList.toggle('active', index === 0);
        });

        // Tiers: activer uniquement le label "All" (premier bouton = label)
        document.querySelectorAll('.tier-btn-multiline').forEach((btn, index) => {
            btn.classList.toggle('active', index === 0);
        });

        // Reset selects et retirer animation pulse
        const actionTypeFilter = document.getElementById('actionTypeFilter');
        const dropRateFilter = document.getElementById('dropRateFilter');
        const refillFilter = document.getElementById('refillFilter');
        const priceMin = document.getElementById('priceMin');
        const priceMax = document.getElementById('priceMax');
        const sortSelect = document.getElementById('sortSelect');

        if (actionTypeFilter) {
            actionTypeFilter.value = '';
            actionTypeFilter.classList.remove('active');
        }

        if (dropRateFilter) {
            dropRateFilter.value = '';
            dropRateFilter.classList.remove('active');
        }

        if (refillFilter) {
            refillFilter.value = '';
            refillFilter.classList.remove('active');
        }

        if (priceMin) {
            priceMin.value = '';
            priceMin.classList.remove('active');
        }

        if (priceMax) {
            priceMax.value = '';
            priceMax.classList.remove('active');
        }

        if (sortSelect) {
            sortSelect.value = 'price-asc';
        }

        this.reloadWithFilters();
    },

    reloadWithFilters() {
        // Sauvegarder la position de scroll actuelle
        const currentScrollY = window.scrollY || window.pageYOffset;

        // Annuler tout timeout en attente
        if (this.loadTimeout) {
            clearTimeout(this.loadTimeout);
        }

        // Annuler toute requête en cours
        if (this.abortController) {
            this.abortController.abort();
            console.log('🚫 Requête précédente annulée');
        }

        this.currentPage = 1;
        this.hasMore = true;
        this.totalDisplayed = 0;  // Réinitialiser le compteur affiché
        this.totalAvailable = 0;  // Réinitialiser le total disponible
        this.isLoading = false;   // Réinitialiser le flag de chargement

        const grid = document.getElementById('servicesGrid');
        const sentinel = document.getElementById('scrollSentinel');

        // Réafficher la sentinelle pour nouveau chargement
        if (sentinel) {
            sentinel.style.display = 'block';
        }

        if (grid) {
            // Effet fade-out
            grid.classList.add('fading-out');

            // Debounce: attendre 150ms avant de charger
            this.loadTimeout = setTimeout(() => {
                grid.innerHTML = '';
                this.loadServices().then(() => {
                    // Restaurer la position de scroll après chargement
                    window.scrollTo({
                        top: currentScrollY,
                        behavior: 'instant' // Pas d'animation
                    });
                });
            }, 150);
        }
    },

    async loadServices() {
        if (this.isLoading || !this.hasMore) {
            console.log('⏸️ loadServices bloqué:', { isLoading: this.isLoading, hasMore: this.hasMore });
            return;
        }

        console.log(`🔄 Chargement page ${this.currentPage}...`);

        this.isLoading = true;
        const loadingState = document.getElementById('loadingState');
        const grid = document.getElementById('servicesGrid');

        // Créer un nouveau AbortController pour cette requête
        this.abortController = new AbortController();
        const signal = this.abortController.signal;

        // Afficher skeletons pour page > 1 (infinite scroll)
        if (this.currentPage > 1) {
            this.showSkeletons(4); // Afficher 4 skeletons
            if (loadingState) {
                loadingState.classList.add('active');
            }
        }

        try {
            // Construction URL avec tous les filtres
            let url = `../api/services.php?page=${this.currentPage}&per_page=${this.itemsPerPage}`;

            if (this.filters.platform) url += `&platform=${encodeURIComponent(this.filters.platform)}`;
            if (this.filters.tier) url += `&tier=${encodeURIComponent(this.filters.tier)}`;
            if (this.filters.actionType) url += `&action_type=${encodeURIComponent(this.filters.actionType)}`;
            if (this.filters.dropRate) url += `&drop_rate=${encodeURIComponent(this.filters.dropRate)}`;
            if (this.filters.refill) url += `&refill_days=${encodeURIComponent(this.filters.refill)}`;
            if (this.filters.priceMin) url += `&price_min=${this.filters.priceMin}`;
            if (this.filters.priceMax) url += `&price_max=${this.filters.priceMax}`;
            if (this.filters.sort) url += `&sort=${this.filters.sort}`;

            console.log('📡 Fetching:', url);

            const response = await fetch(url, { signal });

            // Vérifier si la réponse est OK
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();

            console.log('🔍 Réponse API complète:', data);

            // Retirer les skeletons avant d'afficher les vrais résultats
            this.removeSkeletons();

            if (data.success) {
                // L'API retourne data.services et data.pagination
                const services = data.services || data.data || [];
                const pagination = data.pagination || {};
                const total = pagination.total || data.total || 0;

                // Mettre à jour le total disponible
                this.totalAvailable = total;

                // Si c'est la première page, réinitialiser le compteur
                if (this.currentPage === 1) {
                    this.totalDisplayed = 0;
                }

                // Ajouter le nombre de services chargés
                this.totalDisplayed += (services?.length || 0);

                // Calculer has_more si l'API ne le fournit pas
                const totalPages = pagination.total_pages || Math.ceil(total / this.itemsPerPage);
                this.hasMore = pagination.has_more !== undefined
                    ? pagination.has_more
                    : (this.currentPage < totalPages);

                console.log(`📊 Pagination détaillée:`, {
                    page: this.currentPage,
                    received: services?.length || 0,
                    displayed: this.totalDisplayed,
                    total: this.totalAvailable,
                    hasMore: this.hasMore,
                    totalPages: totalPages,
                    apiData: pagination
                });

                this.renderServices(services);

                // Mettre à jour le compteur avec le format: "affichés / total"
                this.updateResultsCount(this.totalDisplayed, this.totalAvailable);

                this.currentPage++;

                // Cacher la sentinelle si plus de résultats
                const sentinel = document.getElementById('scrollSentinel');
                if (sentinel && !this.hasMore) {
                    sentinel.style.display = 'none';
                    console.log('🏁 Fin des résultats - Sentinelle cachée');
                }

                console.log(`✅ Loaded ${services?.length || 0} services (displayed: ${this.totalDisplayed}/${this.totalAvailable}) - hasMore: ${this.hasMore}`);
            } else {
                console.error('❌ Erreur API:', data.message || data.error);
                this.showError(data.message || data.error || 'Erreur de chargement');
            }

        } catch (error) {
            // Si la requête a été annulée, ne pas afficher d'erreur
            if (error.name === 'AbortError') {
                console.log('🚫 Requête annulée');
                return; // Ne pas réinitialiser isLoading, la nouvelle requête le fera
            }

            console.error('❌ Erreur chargement services:', error);
            this.showError('Impossible de charger les services');
        } finally {
            // Ne réinitialiser que si la requête n'a pas été annulée
            if (!signal.aborted) {
                this.isLoading = false;
                if (loadingState) {
                    loadingState.classList.remove('active');
                }
            }
        }
    },

    renderServices(services) {
        const grid = document.getElementById('servicesGrid');
        if (!grid) {
            console.warn('⚠️ Grid non trouvée lors du rendu');
            return;
        }

        // Enlever fade-out
        grid.classList.remove('fading-out');

        const template = document.getElementById('serviceCardTemplate');
        if (!template) {
            console.error('❌ Template serviceCardTemplate not found');
            return;
        }

        // Vérifier si on doit afficher un message "aucun résultat"
        if (services.length === 0 && this.currentPage === 1) {
            grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6b7280;">Aucun service trouvé avec ces filtres.</div>';
            return;
        }

        // Fragment pour optimiser les performances
        const fragment = document.createDocumentFragment();

        services.forEach((service, index) => {
            const card = template.content.cloneNode(true);

            // ID du service (nouveau badge)
            const idBadge = card.querySelector('.service-id-badge');
            if (idBadge) {
                idBadge.textContent = `#${service.id}`;
                // Ajouter l'événement de copie au clic
                idBadge.style.cursor = 'pointer';
                idBadge.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.copyServiceId(service.id);
                });
            }

            // Récupérer la config de la plateforme avec icône et couleur
            const platformData = this.getPlatformConfig(service.platform);

            // Remplir les données de plateforme avec icône
            const platformIcon = card.querySelector('.platform-icon-mini');
            if (platformIcon) {
                platformIcon.className = `platform-icon-mini ${platformData.icon}`;
                platformIcon.style.color = platformData.color;
            }

            const platformName = card.querySelector('.platform-name');
            if (platformName) {
                platformName.textContent = service.platform || 'Platform';
                platformName.style.color = platformData.color;
            }

            // Afficher le badge d'action si un filtre action est sélectionné
            const actionBadge = card.querySelector('.service-action-badge');
            if (actionBadge && this.filters.actionType) {
                const actionData = this.getActionConfig(this.filters.actionType);
                if (actionData) {
                    actionBadge.innerHTML = `<i class="${actionData.icon}"></i> ${actionData.label}`;
                    actionBadge.style.display = 'inline-flex';
                    actionBadge.style.background = `linear-gradient(135deg, ${actionData.color}22, ${actionData.color}11)`;
                    actionBadge.style.color = actionData.color;
                    actionBadge.style.border = `1px solid ${actionData.color}33`;
                    actionBadge.style.padding = '3px 8px';
                    actionBadge.style.borderRadius = '6px';
                    actionBadge.style.fontSize = '9px';
                    actionBadge.style.fontWeight = '600';
                    actionBadge.style.alignItems = 'center';
                    actionBadge.style.gap = '4px';
                }
            }

            const tierBadge = card.querySelector('.service-tier-badge');
            if (tierBadge) {
                const tier = service.tier || 'standard';
                // Ajouter icône selon le tier
                const tierIcons = {
                    'budget': '<i class="fas fa-piggy-bank"></i>',
                    'standard': '<i class="fas fa-star"></i>',
                    'premium': '<i class="fas fa-gem"></i>',
                    'ultimate': '<i class="fas fa-crown"></i>'
                };
                const tierLabel = tier.charAt(0).toUpperCase() + tier.slice(1);
                tierBadge.innerHTML = `${tierIcons[tier.toLowerCase()] || ''} ${tierLabel}`;
                tierBadge.className = `service-tier-badge tier-${tier.toLowerCase()}`;
            }

            const title = card.querySelector('.service-card-title');
            if (title) {
                const serviceName = service.name || 'Service';
                title.textContent = serviceName;

                // Si le titre est long (>50 caractères), ajouter système d'expansion
                if (serviceName.length > 50) {
                    title.classList.add('title-truncated');
                    title.setAttribute('data-full-title', serviceName);
                    title.innerHTML = `
                        <span class="title-text">${serviceName}</span>
                        <button class="title-expand-btn" title="Voir le titre complet">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    `;
                }
            }

            const price = card.querySelector('.service-price');
            if (price) {
                const priceVal = parseFloat(service.sell_price || service.price || 0);
                const minQty = parseInt(service.min_quantity) || 1000;

                // Calculer le prix au minimum
                const priceAtMin = (priceVal * minQty) / 1000;

                // Fonction de formatage intelligent avec décimales adaptatives
                const formatPrice = (price) => {
                    if (price === 0) return '0.00';
                    if (price >= 1) return price.toFixed(2);    // $1.50
                    if (price >= 0.01) return price.toFixed(3); // $0.015
                    if (price >= 0.001) return price.toFixed(4);// $0.0015
                    if (price >= 0.0001) return price.toFixed(5); // $0.00015
                    return price.toFixed(6);                    // $0.000015
                };

                // Afficher les deux prix : prix/min ET prix/1K
                if (minQty === 1000) {
                    // Si min = 1000, afficher seulement le prix /1k
                    price.innerHTML = `$${formatPrice(priceVal)} / 1k`;
                } else {
                    // Afficher prix au min + prix par 1K
                    const minLabel = minQty >= 1000 ? (minQty / 1000).toFixed(0) + 'k' : minQty;
                    price.innerHTML = `
                        <div style="display: flex; flex-direction: column; align-items: flex-start; line-height: 1.3;">
                            <div style="font-size: 1em; font-weight: 700; color: #3b82f6;">
                                $${formatPrice(priceAtMin)} <span style="font-size: 0.85em; opacity: 0.8;">/ ${minLabel}</span>
                            </div>
                            <div style="font-size: 0.8em; font-weight: 500; color: #10b981; white-space: nowrap;">
                                ($${formatPrice(priceVal)} / 1K)
                            </div>
                        </div>
                    `;
                }
            }

            const orderBtn = card.querySelector('.service-order-btn');
            if (orderBtn) {
                orderBtn.href = `../orders/new.php?service=${service.id}`;
            }

            // Features: Caractéristiques en ligne (drop rate, refill, instant, min/max)
            const features = card.querySelector('.service-features');
            if (features) {
                features.innerHTML = '';

                // 1. Drop Rate avec icônes
                if (service.drop_rate) {
                    const dropLower = service.drop_rate.toLowerCase();
                    if (dropLower.includes('no drop') || dropLower === 'nodrop') {
                        features.innerHTML += '<span class="service-feature-item"><i class="fas fa-shield-alt"></i> No Drop</span>';
                    } else if (dropLower.includes('low drop') || dropLower === 'lowdrop') {
                        features.innerHTML += '<span class="service-feature-item"><i class="fas fa-exclamation-triangle"></i> Low Drop</span>';
                    } else if (dropLower.includes('high drop') || dropLower === 'highdrop' || dropLower.includes('full drop') || dropLower === 'fulldrop') {
                        features.innerHTML += '<span class="service-feature-item"><i class="fas fa-times-circle"></i> High Drop</span>';
                    } else {
                        features.innerHTML += `<span class="service-feature-item"><i class="fas fa-chart-line"></i> ${service.drop_rate}</span>`;
                    }
                }

                // 2. Refill avec icônes
                const refillDays = parseInt(service.refill_days);
                if (refillDays > 0 && refillDays <= 30) {
                    features.innerHTML += `<span class="service-feature-item"><i class="fas fa-sync-alt"></i> Refill ${refillDays}j</span>`;
                } else if (refillDays > 30 && refillDays < 365) {
                    features.innerHTML += `<span class="service-feature-item"><i class="fas fa-redo"></i> Refill ${refillDays}j</span>`;
                } else if (refillDays >= 365 || service.refill_days === 'lifetime' || service.refill_days === 'Lifetime') {
                    features.innerHTML += '<span class="service-feature-item"><i class="fas fa-infinity"></i> Lifetime</span>';
                }

                // 3. Vitesse (détection dans le nom)
                const nameLower = (service.name || '').toLowerCase();
                if (nameLower.includes('instant') || nameLower.includes('immediate')) {
                    features.innerHTML += '<span class="service-feature-item"><i class="fas fa-bolt"></i> Instant</span>';
                } else if (nameLower.includes('fast') || nameLower.includes('rapide') || nameLower.includes('quick')) {
                    features.innerHTML += '<span class="service-feature-item"><i class="fas fa-rocket"></i> Rapide</span>';
                } else if (nameLower.includes('slow') || nameLower.includes('lent') || nameLower.includes('progressive')) {
                    features.innerHTML += '<span class="service-feature-item"><i class="fas fa-hourglass-half"></i> Progressif</span>';
                }

                // 4. Quantité Min/Max
                const minQty = parseInt(service.min_quantity) || 0;
                const maxQty = parseInt(service.max_quantity) || 0;
                if (minQty > 0 && maxQty > 0) {
                    const minFormatted = minQty >= 1000 ? (minQty / 1000).toFixed(0) + 'K' : minQty;
                    const maxFormatted = maxQty >= 1000 ? (maxQty / 1000).toFixed(0) + 'K' : maxQty;
                    features.innerHTML += `<span class="service-feature-item"><i class="fas fa-boxes"></i> ${minFormatted} - ${maxFormatted}</span>`;
                }

                // ✅ 5. QUALITY Badge (NOUVEAU V2)
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

                // ✅ 6. LOCATION Badge (NOUVEAU V2)
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

                // ✅ 7. SPEED Enhanced (NOUVEAU V2)
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
                    } else if (speedLower.includes('up to')) {
                        icon = 'fas fa-tachometer-alt';
                        // Raccourcir "Up To 50K/Day" → "50K/Day"
                        label = label.replace(/up to /i, '');
                    }

                    features.innerHTML += `<span class="service-feature-item feature-speed"><i class="${icon}"></i> ${label}</span>`;
                }

                // ✅ 8. AVERAGE TIME (NOUVEAU V2)
                if (service.average_time) {
                    features.innerHTML += `<span class="service-feature-item feature-time"><i class="fas fa-clock"></i> ${service.average_time}</span>`;
                }

                // ✅ 9. DRIPFEED Badge (NOUVEAU V2)
                if (service.dripfeed === true || service.dripfeed === 1) {
                    features.innerHTML += '<span class="service-feature-item feature-dripfeed"><i class="fas fa-water"></i> Dripfeed</span>';
                }

                // ✅ 10. CANCEL Badge (NOUVEAU V2)
                if (service.cancel === true || service.cancel === 1) {
                    features.innerHTML += '<span class="service-feature-item feature-cancel"><i class="fas fa-times-circle"></i> Cancellable</span>';
                }

                // ✅ 11. REFILL TYPE Enhanced (NOUVEAU V2)
                if (service.refill_type && !features.innerHTML.includes('Lifetime')) {
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
                    if (label.length > 25) {
                        label = label.substring(0, 25) + '...';
                    }

                    features.innerHTML += `<span class="service-feature-item feature-refill"><i class="${icon}"></i> ${label}</span>`;
                }
            }

            // Animation staggered
            const cardElement = card.querySelector('.service-card-modern');
            if (cardElement) {
                cardElement.style.animationDelay = `${index * 0.05}s`;

                // Ajouter data-attributes pour le modal de commande
                cardElement.dataset.serviceId = service.id;
                cardElement.dataset.providerId = service.provider_id;
                cardElement.dataset.price = service.sell_price || service.price || 0;
                cardElement.dataset.minQuantity = service.min_quantity || 1000;
                cardElement.dataset.maxQuantity = service.max_quantity || 10000;
                cardElement.dataset.tier = service.tier || '';
                cardElement.dataset.quality = service.quality || '';
                cardElement.dataset.refillDays = service.refill_days || '0';
                cardElement.dataset.refillType = service.refill_type || 'No Refill';
                cardElement.dataset.dropRate = service.drop_rate || 'No Drop';
                cardElement.dataset.speed = service.speed || '';
                cardElement.dataset.averageTime = service.average_time || '';
                cardElement.dataset.dripfeed = service.dripfeed || '0';
                cardElement.dataset.cancel = service.cancel || '0';
                cardElement.dataset.description = service.description || '';
                cardElement.dataset.location = service.location || '';
            }

            fragment.appendChild(card);
        });

        // Ajouter tous les cards d'un coup (meilleure performance)
        grid.appendChild(fragment);

        // Dispatcher l'event pour que loadFavoritesState() puisse marquer les favoris
        window.dispatchEvent(new CustomEvent('servicesLoaded', {
            detail: { count: services.length, page: this.currentPage }
        }));

        // Attacher les event listeners pour l'expansion des titres
        this.attachTitleExpandListeners();
    },

    attachTitleExpandListeners() {
        const expandBtns = document.querySelectorAll('.title-expand-btn');
        expandBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const title = btn.closest('.service-card-title');
                if (title) {
                    title.classList.toggle('title-expanded');

                    // Rotation de l'icône
                    const svg = btn.querySelector('svg');
                    if (svg) {
                        svg.style.transform = title.classList.contains('title-expanded')
                            ? 'rotate(180deg)'
                            : 'rotate(0deg)';
                    }
                }
            });
        });
    },

    updateResultsCount(displayed, total) {
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            // Si tous les résultats sont affichés, montrer seulement le total
            if (displayed >= total || !this.hasMore) {
                resultsCount.textContent = new Intl.NumberFormat('fr-FR').format(total);
            } else {
                // Sinon montrer "affichés / total"
                resultsCount.textContent = `${new Intl.NumberFormat('fr-FR').format(displayed)} / ${new Intl.NumberFormat('fr-FR').format(total)}`;
            }
        }
    },

    showError(message) {
        const grid = document.getElementById('servicesGrid');
        if (grid && this.currentPage === 1) {
            grid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #ef4444;">
                    <strong>Erreur:</strong> ${message}
                </div>
            `;
        }
    },

    showSkeletons(count = 4) {
        const grid = document.getElementById('servicesGrid');
        if (!grid) return;

        // Créer des cartes skeleton
        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'service-card-modern skeleton-card';
            skeleton.setAttribute('data-skeleton', 'true');
            skeleton.innerHTML = `
                <div class="service-card-header skeleton-header">
                    <div class="skeleton-badge" style="width: 80px; height: 20px;"></div>
                    <div class="skeleton-badge" style="width: 60px; height: 20px;"></div>
                </div>
                <div class="skeleton-title" style="width: 100%; height: 24px; margin: 12px 0;"></div>
                <div class="skeleton-features" style="width: 80%; height: 16px; margin: 8px 0;"></div>
                <div class="skeleton-features" style="width: 60%; height: 16px; margin: 8px 0;"></div>
                <div class="service-card-footer skeleton-footer">
                    <div class="skeleton-price" style="width: 80px; height: 28px;"></div>
                    <div class="skeleton-button" style="width: 100px; height: 36px;"></div>
                </div>
            `;
            grid.appendChild(skeleton);
        }
    },

    removeSkeletons() {
        const skeletons = document.querySelectorAll('[data-skeleton="true"]');
        skeletons.forEach(skeleton => skeleton.remove());
    },

    setupInfiniteScroll() {
        const sentinel = document.getElementById('scrollSentinel');

        if (!sentinel) {
            console.error('❌ Sentinelle de scroll non trouvée');
            return;
        }

        // Afficher la sentinelle pour qu'elle soit observable
        sentinel.style.display = 'block';

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.isLoading && this.hasMore) {
                    console.log('🔄 Sentinelle visible - Chargement page', this.currentPage);
                    this.loadServices();
                } else if (entry.isIntersecting) {
                    console.log('⏸️ Sentinelle visible mais chargement bloqué:', {
                        isLoading: this.isLoading,
                        hasMore: this.hasMore
                    });
                }
            });
        }, {
            root: null, // viewport
            rootMargin: '200px', // Déclencher 200px avant d'atteindre la sentinelle
            threshold: 0.1
        });

        observer.observe(sentinel);
        console.log('👁️ IntersectionObserver attaché à la sentinelle');
    },

    showSkeletons(count = 4) {
        const skeletonContainer = document.getElementById('skeletonLoaders');
        const template = document.getElementById('skeletonCardTemplate');

        if (!skeletonContainer || !template) {
            console.warn('⚠️ Template skeleton non trouvé');
            return;
        }

        // Vider les anciens skeletons
        skeletonContainer.innerHTML = '';

        // Ajouter de nouveaux skeletons
        for (let i = 0; i < count; i++) {
            const skeleton = template.content.cloneNode(true);
            skeletonContainer.appendChild(skeleton);
        }

        console.log(`💀 ${count} skeletons affichés`);
    },

    removeSkeletons() {
        const skeletonContainer = document.getElementById('skeletonLoaders');
        if (skeletonContainer) {
            skeletonContainer.innerHTML = '';
            console.log('✨ Skeletons supprimés');
        }
    },

    /**
     * Copie l'ID du service dans le presse-papier et affiche un toast
     * @param {number|string} serviceId - L'ID du service à copier
     */
    copyServiceId(serviceId) {
        // Copier dans le presse-papier
        navigator.clipboard.writeText(serviceId.toString()).then(() => {
            // Créer le toast
            const toast = document.createElement('div');
            toast.className = 'copy-toast';
            toast.textContent = `ID Service : ${serviceId} copié dans le presse-papier`;

            // Ajouter au body
            document.body.appendChild(toast);

            // Supprimer après 3 secondes
            setTimeout(() => {
                toast.remove();
            }, 3000);

            console.log(`📋 ID ${serviceId} copié dans le presse-papier`);
        }).catch(err => {
            console.error('❌ Erreur lors de la copie :', err);
            // Fallback avec toast d'erreur
            const toast = document.createElement('div');
            toast.className = 'copy-toast';
            toast.style.background = 'linear-gradient(135deg, #EF4444, #DC2626)';
            toast.textContent = `Erreur lors de la copie de l'ID ${serviceId}`;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        });
    }
};

// Initialisation au chargement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        ServicesManagerMultiline.init();
    });
} else {
    ServicesManagerMultiline.init();
}