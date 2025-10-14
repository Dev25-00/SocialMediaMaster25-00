/**
 * SMM Mastery - Gestionnaire Modal de Commande
 * Date: 13 Octobre 2025
 * Version: 1.0
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\
 */

class OrderModal {
    constructor() {
        this.modal = null;
        this.overlay = null;
        this.currentService = null;
        this.userBalance = 0;
        this.activeTab = 'new-order'; // Default tab
        this.shareListenersSetup = false;
        this.init();
    }

    init() {
        // Créer le modal au chargement
        this.createModal();

        // Écouter les clics sur les boutons "Buy"
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('service-order-btn') ||
                e.target.closest('.service-order-btn')) {
                e.preventDefault();
                const btn = e.target.classList.contains('service-order-btn') ?
                    e.target : e.target.closest('.service-order-btn');

                // Récupérer le service depuis la card (class: service-card-modern)
                const card = btn.closest('.service-card-modern');
                if (card) {
                    const serviceData = this.extractServiceData(card);
                    this.open(serviceData);
                } else {
                    console.error('❌ Service card not found');
                }
            }
        });

        // Vérifier si URL contient ?service=ID pour ouvrir auto
        this.checkURLForService();
    }

    checkURLForService() {
        const urlParams = new URLSearchParams(window.location.search);
        const serviceId = urlParams.get('service');

        if (serviceId) {
            console.log('🔗 Service ID detected in URL:', serviceId);

            // Attendre que les services soient chargés
            const checkInterval = setInterval(() => {
                const card = document.querySelector(`[data-service-id="${serviceId}"]`);
                if (card) {
                    clearInterval(checkInterval);
                    console.log('✅ Service found, opening modal...');
                    const serviceData = this.extractServiceData(card);
                    this.open(serviceData, true); // true = from URL
                } else {
                    console.log('⏳ Waiting for services to load...');
                }
            }, 500);

            // Timeout après 10 secondes
            setTimeout(() => {
                clearInterval(checkInterval);
                console.warn('⚠️ Service not found after timeout');
            }, 10000);
        }
    }

    createModal() {
        // Créer l'overlay et le modal
        const modalHTML = `
            <div class="order-modal-overlay" id="orderModalOverlay">
                <div class="order-modal">
                    <div style="display: flex; flex-direction: column; width: 100%; max-height: 90vh;">
                        <!-- Header avec Tabs -->
                        <div class="order-modal-header">
                            <div class="order-modal-title-tabs">
                                <h2>
                                    <i class="fas fa-shopping-cart"></i>
                                    Order Service
                                </h2>
                                <!-- Tabs Navigation -->
                                <div class="order-modal-tabs">
                                    <button class="order-tab-btn active" data-tab="new-order">
                                        <i class="fas fa-cart-plus"></i>
                                        New Order
                                    </button>
                                    <button class="order-tab-btn" data-tab="favorites">
                                        <i class="fas fa-star"></i>
                                        Favorites
                                    </button>
                                    <button class="order-tab-btn" data-tab="countries">
                                        <i class="fas fa-globe"></i>
                                        Countries
                                    </button>
                                    <button class="order-tab-btn" data-tab="auto-subscription" style="display: none;">
                                        <i class="fas fa-sync-alt"></i>
                                        Auto Sub
                                    </button>
                                </div>
                            </div>
                            <button class="order-modal-close" id="orderModalClose">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Tab Content Container -->
                        <div class="order-modal-body order-tab-content" data-active-tab="new-order">
                            
                            <!-- TAB 1: NEW ORDER (Contenu existant) -->
                            <div class="order-tab-panel active" data-tab-panel="new-order">
                                <!-- Colonne gauche: Formulaire -->
                                <div class="order-modal-form-section">
                                <!-- Alert messages -->
                                <div id="orderAlert"></div>

                                <!-- Service sélectionné -->
                                <div class="order-selected-service" id="orderSelectedService">
                                    <!-- Rempli dynamiquement -->
                                </div>

                                <!-- Formulaire -->
                                <form id="orderForm">
                                    <!-- Link -->
                                    <div class="order-form-group">
                                        <label>
                                            <i class="fas fa-link"></i>
                                            Link / URL
                                        </label>
                                        <input 
                                            type="url" 
                                            id="orderLink" 
                                            name="link" 
                                            placeholder="https://example.com/your-link"
                                            required
                                        >
                                    </div>

                                    <!-- Quantity -->
                                    <div class="order-form-group">
                                        <label>
                                            <i class="fas fa-hashtag"></i>
                                            Quantity
                                        </label>
                                        <div class="order-quantity-controls">
                                            <input 
                                                type="number" 
                                                id="orderQuantity" 
                                                name="quantity" 
                                                class="order-quantity-input"
                                                required
                                            >
                                        </div>
                                        <div class="order-quantity-minmax">
                                            <span>Min: <strong id="orderMinQty">0</strong></span>
                                            <span>Max: <strong id="orderMaxQty">0</strong></span>
                                        </div>
                                    </div>

                                    <!-- Drip-feed -->
                                    <div class="order-form-group">
                                        <div class="order-checkbox-group" id="orderDripfeedToggle">
                                            <input 
                                                type="checkbox" 
                                                id="orderDripfeed" 
                                                name="dripfeed"
                                            >
                                            <label for="orderDripfeed">
                                                <i class="fas fa-tint"></i>
                                                Enable Drip-feed (spread delivery over time)
                                            </label>
                                        </div>
                                        
                                        <!-- Drip-feed options (hidden by default) -->
                                        <div class="order-dripfeed-options" id="orderDripfeedOptions">
                                            <div class="order-form-group">
                                                <label>Runs (number of batches)</label>
                                                <input 
                                                    type="number" 
                                                    id="orderDripfeedRuns" 
                                                    name="dripfeed_runs"
                                                    min="2"
                                                    placeholder="e.g., 10"
                                                >
                                            </div>
                                            <div class="order-form-group">
                                                <label>Interval (minutes between batches)</label>
                                                <input 
                                                    type="number" 
                                                    id="orderDripfeedInterval" 
                                                    name="dripfeed_interval"
                                                    min="1"
                                                    placeholder="e.g., 60"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Charge -->
                                    <!-- Total Charge Display -->
                                    <div class="order-form-group">
                                        <div class="order-charge-display">
                                            <div>
                                                <div class="order-charge-label">
                                                    <i class="fas fa-dollar-sign"></i>
                                                    Total Charge
                                                </div>
                                                <div class="order-balance-info">
                                                    <div class="order-balance-current">
                                                        Current balance: <strong id="orderBalanceCurrent">$0.00</strong>
                                                    </div>
                                                    <div class="order-balance-remaining">
                                                        Balance after: <strong id="orderBalanceAfter">$0.00</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-charge-amount" id="orderChargeAmount">
                                                $0.00
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service Description Section (moved here from right column) -->
                                    <div class="order-service-description-section">
                                        <div class="order-description-title">
                                            <i class="fas fa-info-circle"></i>
                                            Description
                                        </div>
                                        <div id="orderServiceDetails" class="order-service-details-content">
                                            <!-- Rempli dynamiquement -->
                                        </div>
                                    </div>

                                </form>
                            </div>
                            </div>
                            <!-- FIN TAB 1: NEW ORDER -->
                            
                            <!-- TAB 2: FAVORITES -->
                            <div class="order-tab-panel" data-tab-panel="favorites">
                                <div class="favorites-container">
                                    <div class="favorites-header">
                                        <i class="fas fa-star"></i>
                                        <h3>My Favorite Services</h3>
                                        <p>Quick access to your frequently used services</p>
                                    </div>
                                    <div id="favoritesList" class="favorites-list">
                                        <!-- Rempli dynamiquement via loadFavorites() -->
                                        <div class="favorites-empty">
                                            <i class="fas fa-star-o"></i>
                                            <p>No favorites yet</p>
                                            <small>Click the ⭐ icon on any service card to add it to your favorites</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN TAB 2: FAVORITES -->
                            
                            <!-- TAB 3: COUNTRIES -->
                            <div class="order-tab-panel" data-tab-panel="countries">
                                <div class="countries-container">
                                    <div class="countries-header">
                                        <i class="fas fa-globe"></i>
                                        <h3>Order by Country</h3>
                                        <p>Target specific geographic locations</p>
                                    </div>
                                    
                                    <!-- Country Selector -->
                                    <div class="country-selector-group">
                                        <label>
                                            <i class="fas fa-flag"></i>
                                            Select Country
                                        </label>
                                        <select id="countrySelector" class="country-select">
                                            <option value="">-- Select a Country --</option>
                                            <!-- Rempli dynamiquement -->
                                        </select>
                                    </div>
                                    
                                    <!-- Services filtered by country -->
                                    <div id="countryServicesList" class="country-services-list">
                                        <div class="country-services-empty">
                                            <i class="fas fa-globe-americas"></i>
                                            <p>Select a country to view available services</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN TAB 3: COUNTRIES -->
                            
                            <!-- TAB 4: AUTO SUBSCRIPTION (Future) -->
                            <div class="order-tab-panel" data-tab-panel="auto-subscription" style="display: none;">
                                <div class="subscription-container">
                                    <div class="coming-soon">
                                        <i class="fas fa-sync-alt"></i>
                                        <h3>Auto Subscription</h3>
                                        <p>Coming Soon...</p>
                                        <small>Automatically order services for new posts</small>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN TAB 4: AUTO SUBSCRIPTION -->
                            
                        </div>
                        <!-- Fin Body -->

                        <!-- Footer Fixe -->
                        <div class="order-modal-footer">
                            <!-- Share Button Simple (Copy Link) -->
                            <button type="button" class="order-btn-share" id="orderBtnShare" title="Copy service link">
                                <i class="fas fa-copy"></i>
                                <span class="btn-text">Copy Link</span>
                            </button>
                            
                            <button type="button" class="order-btn-cancel" id="orderBtnCancel">
                                <i class="fas fa-times"></i>
                                <span class="btn-text">Cancel</span>
                            </button>
                            <button type="button" class="order-btn-submit" id="orderBtnSubmit">
                                <i class="fas fa-check"></i>
                                <span class="btn-text">Place Order</span>
                            </button>
                        </div>
                        <!-- Fin Footer -->
                        
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        this.modal = document.getElementById('orderModalOverlay');
        this.overlay = document.getElementById('orderModalOverlay');

        // Event listeners
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Tab switching
        document.querySelectorAll('.order-tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tabName = btn.dataset.tab;
                this.switchTab(tabName);
            });
        });

        // Close modal
        document.getElementById('orderModalClose').addEventListener('click', () => this.close());
        document.getElementById('orderBtnCancel').addEventListener('click', () => this.close());

        // Close on overlay click
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                this.close();
            }
        });

        // Drip-feed toggle
        document.getElementById('orderDripfeedToggle').addEventListener('click', () => {
            const checkbox = document.getElementById('orderDripfeed');
            checkbox.checked = !checkbox.checked;
            this.toggleDripfeed();
        });

        document.getElementById('orderDripfeed').addEventListener('change', () => {
            this.toggleDripfeed();
        });

        // Quantity change -> Update price
        document.getElementById('orderQuantity').addEventListener('input', (e) => {
            console.log('📊 Quantity changed:', e.target.value);
            this.updateCharge();
        });

        // Form submit
        document.getElementById('orderForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitOrder();
        });

        // Submit button (also handle click since it's outside form now)
        document.getElementById('orderBtnSubmit').addEventListener('click', (e) => {
            console.log('📤 Submit button clicked');
            e.preventDefault();
            this.submitOrder();
        });

        // Share button - Simple copy link
        const shareBtnElement = document.getElementById('orderBtnShare');
        if (shareBtnElement) {
            shareBtnElement.addEventListener('click', (e) => {
                e.stopPropagation();
                this.copyServiceLink();
            });
        }
    }

    extractServiceData(card) {
        // Extraire le platform depuis le badge
        const platformNameEl = card.querySelector('.platform-name');
        const platformName = platformNameEl ? platformNameEl.textContent.trim() : '';

        // Extraire le nom du service depuis le titre
        const titleEl = card.querySelector('.service-card-title');
        const serviceName = titleEl ? titleEl.textContent.trim() : '';

        return {
            id: card.dataset.serviceId,
            provider_id: card.dataset.providerId,
            platform: platformName,
            name: serviceName,
            description: card.dataset.description || '',
            location: card.dataset.location || '',
            price: parseFloat(card.dataset.price || 0),
            min_quantity: parseInt(card.dataset.minQuantity || 1000),
            max_quantity: parseInt(card.dataset.maxQuantity || 10000),
            tier: card.dataset.tier || '',
            quality: card.dataset.quality || '',
            refill_days: card.dataset.refillDays || '0',
            refill_type: card.dataset.refillType || 'No Refill',
            drop_rate: card.dataset.dropRate || 'No Drop',
            speed: card.dataset.speed || '',
            average_time: card.dataset.averageTime || '',
            dripfeed: card.dataset.dripfeed === '1' || card.dataset.dripfeed === 'true',
            cancel: card.dataset.cancel === '1' || card.dataset.cancel === 'true'
        };
    }

    open(serviceData, fromURL = false) {
        this.currentService = serviceData;

        // Remplir les données du service
        this.populateServiceInfo();

        // Réinitialiser le formulaire
        document.getElementById('orderForm').reset();
        document.getElementById('orderAlert').innerHTML = '';
        document.getElementById('orderQuantity').value = serviceData.min_quantity;
        document.getElementById('orderDripfeedOptions').classList.remove('active');

        // Charger les favorites automatiquement (pour que la liste soit à jour)
        this.loadFavorites();

        // Mettre à jour l'URL (sauf si déjà ouvert depuis URL)
        if (!fromURL) {
            const newURL = `${window.location.pathname}?service=${serviceData.id}`;
            window.history.pushState({ serviceId: serviceData.id }, '', newURL);
        }

        // Afficher le modal
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Focus sur le champ link
        setTimeout(() => {
            document.getElementById('orderLink').focus();
        }, 300);

        // Update charge
        this.updateCharge();
    }

    close() {
        this.modal.classList.remove('active');
        document.body.style.overflow = '';
        this.currentService = null;

        // Nettoyer l'URL (retirer ?service=ID)
        const cleanURL = window.location.pathname;
        window.history.pushState({}, '', cleanURL);
    }

    /**
     * Switch between tabs
     * @param {string} tabName - Name of tab to switch to
     */
    switchTab(tabName) {
        console.log('🔄 Switching to tab:', tabName);

        // Update active tab
        this.activeTab = tabName;

        // Update tab buttons
        document.querySelectorAll('.order-tab-btn').forEach(btn => {
            if (btn.dataset.tab === tabName) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update tab panels
        document.querySelectorAll('.order-tab-panel').forEach(panel => {
            if (panel.dataset.tabPanel === tabName) {
                panel.classList.add('active');
            } else {
                panel.classList.remove('active');
            }
        });

        // Update body data attribute
        const tabContent = document.querySelector('.order-tab-content');
        if (tabContent) {
            tabContent.dataset.activeTab = tabName;
        }

        // Load content based on tab
        switch (tabName) {
            case 'favorites':
                this.loadFavorites();
                break;
            case 'countries':
                this.loadCountries();
                break;
            case 'auto-subscription':
                // Future implementation
                break;
            case 'new-order':
            default:
                // New order is always loaded (static content)
                break;
        }
    }

    /**
     * Load user's favorite services
     */
    /**
     * Load user's favorite services
     */
    async loadFavorites() {
        console.log('⭐ Loading favorites...');
        const favoritesList = document.getElementById('favoritesList');

        // Show loading state
        favoritesList.innerHTML = `
            <div class="favorites-loading" style="text-align: center; padding: 40px; color: rgba(255,255,255,0.5);">
                <i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 12px;"></i>
                <p>Loading favorites...</p>
            </div>
        `;

        try {
            const response = await fetch('/smm/api/favorites/list.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to load favorites');
            }

            if (data.favorites && data.favorites.length > 0) {
                // Render favorites
                favoritesList.innerHTML = data.favorites.map(fav => this.renderFavoriteItem(fav)).join('');

                // Add click listeners
                favoritesList.querySelectorAll('.favorite-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        if (!e.target.closest('.favorite-item-remove')) {
                            const serviceData = JSON.parse(item.dataset.serviceData);
                            console.log('🎯 Favorite clicked:', serviceData);

                            // Set current service
                            this.currentService = serviceData;

                            // Switch to New Order tab
                            this.switchTab('new-order');

                            // Populate form with this service
                            setTimeout(() => {
                                this.populateServiceInfo();

                                // Reset quantity to min_quantity of new service
                                const quantityInput = document.getElementById('orderQuantity');
                                quantityInput.value = serviceData.min_quantity;
                                console.log(`📝 Quantity reset to min: ${serviceData.min_quantity}`);

                                // Recalculate price with new service + new quantity
                                this.updateCharge();
                                console.log('✅ Price recalculated for favorite service');
                            }, 100);
                        }
                    });
                });

                // Add remove listeners
                favoritesList.querySelectorAll('.favorite-item-remove').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        e.stopPropagation();
                        const item = btn.closest('.favorite-item');
                        const serviceId = item.dataset.serviceId;
                        await this.removeFavorite(serviceId, item);
                    });
                });

                console.log(`✅ Loaded ${data.favorites.length} favorites`);
            } else {
                // Empty state
                favoritesList.innerHTML = `
                    <div class="favorites-empty">
                        <i class="far fa-star"></i>
                        <p>No favorites yet</p>
                        <small>Click the ⭐ icon on any service card to add it to your favorites</small>
                    </div>
                `;
            }
        } catch (error) {
            console.error('❌ Failed to load favorites:', error);
            favoritesList.innerHTML = `
                <div class="favorites-empty">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load favorites</p>
                    <small>${error.message}</small>
                </div>
            `;
        }
    }

    /**
     * Render a single favorite item
     */
    renderFavoriteItem(favorite) {
        const service = favorite.service;
        const formatPrice = (price) => {
            if (price >= 1) return price.toFixed(2);
            if (price >= 0.01) return price.toFixed(4);
            return price.toFixed(8);
        };

        return `
            <div class="favorite-item" 
                 data-service-id="${service.id}"
                 data-service-data='${JSON.stringify(service)}'>
                <div class="favorite-item-header">
                    <span class="favorite-item-platform">${service.platform}</span>
                    <button class="favorite-item-remove" title="Remove from favorites">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="favorite-item-name">${service.name}</div>
                <div class="favorite-item-meta">
                    <span>Min: ${service.min_quantity}</span>
                    <span class="favorite-item-price">$${formatPrice(service.price)}/1K</span>
                </div>
            </div>
        `;
    }

    /**
     * Remove favorite
     */
    async removeFavorite(serviceId, itemElement) {
        try {
            itemElement.style.opacity = '0.5';
            itemElement.style.pointerEvents = 'none';

            const response = await fetch('/smm/api/favorites/remove.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ service_id: serviceId })
            });

            const data = await response.json();

            if (data.success) {
                // Animate out
                itemElement.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    itemElement.remove();

                    // Check if list is now empty
                    const favoritesList = document.getElementById('favoritesList');
                    if (favoritesList.children.length === 0) {
                        favoritesList.innerHTML = `
                            <div class="favorites-empty">
                                <i class="far fa-star"></i>
                                <p>No favorites left</p>
                                <small>Add more favorites from the services page</small>
                            </div>
                        `;
                    }
                }, 300);

                // Update favorite button on services page if visible
                const card = document.querySelector(`[data-service-id="${serviceId}"]`);
                if (card) {
                    const favBtn = card.querySelector('.service-favorite-btn');
                    if (favBtn) {
                        favBtn.dataset.favorite = 'false';
                        favBtn.classList.remove('active');
                        favBtn.querySelector('i').className = 'far fa-star';
                        favBtn.title = 'Add to favorites';
                    }
                }

                console.log('✅ Favorite removed');
            } else {
                throw new Error(data.message || 'Failed to remove favorite');
            }
        } catch (error) {
            console.error('❌ Remove favorite error:', error);
            alert('Failed to remove favorite. Please try again.');
            itemElement.style.opacity = '1';
            itemElement.style.pointerEvents = 'auto';
        }
    }

    /**
     * Load countries list
     */
    /**
     * Load countries tab with location selector
     * Fetches services when location is selected
     */
    async loadCountries() {
        console.log('🌍 Loading countries tab...');

        const countriesList = document.getElementById('countriesList');
        const countrySelector = document.getElementById('countrySelector');

        // Populate country selector if empty
        if (countrySelector && countrySelector.options.length === 1) {
            const locations = [
                'Global',
                'Worldwide',
                'United States',
                'USA',
                'Us',
                'Usa',
                'Canada',
                'United Kingdom',
                'UK',
                'Europe',
                'France',
                'Germany',
                'Spain',
                'Italy',
                'Netherlands',
                'Belgium',
                'Switzerland',
                'Austria',
                'Portugal',
                'Poland',
                'Greece',
                'Turkey',
                'Russia',
                'Ukraine',
                'Asia',
                'China',
                'Japan',
                'South Korea',
                'India',
                'Indonesia',
                'Thailand',
                'Vietnam',
                'Malaysia',
                'Singapore',
                'Philippines',
                'Pakistan',
                'Bangladesh',
                'Latin America',
                'Brazil',
                'Mexico',
                'Argentina',
                'Colombia',
                'Chile',
                'Peru',
                'Venezuela',
                'Australia',
                'New Zealand',
                'Nigeria',
                'South Africa',
                'Egypt',
                'Morocco',
                'Kenya',
                'Saudi Arabia',
                'United Arab Emirates',
                'UAE',
                'Israel',
                'Iran',
                'Iraq',
                'Lebanon',
                'Jordan',
                'Kuwait',
                'Qatar',
                'Oman',
                'Bahrain'
            ];

            locations.forEach(location => {
                const option = document.createElement('option');
                option.value = location;
                option.textContent = location;
                countrySelector.appendChild(option);
            });
        }

        // Show empty state initially
        countriesList.innerHTML = `
            <div class="countries-empty-state">
                <i class="fas fa-globe-americas"></i>
                <h3>Select a Location</h3>
                <p>Choose a country from the dropdown above to see available services</p>
            </div>
        `;

        // Add change event listener to country selector
        if (!countrySelector.dataset.listenerAdded) {
            countrySelector.addEventListener('change', async (e) => {
                const selectedLocation = e.target.value;
                if (selectedLocation) {
                    await this.loadServicesByLocation(selectedLocation);
                }
            });
            countrySelector.dataset.listenerAdded = 'true';
        }
    }

    /**
     * Load services filtered by location
     * @param {string} location - Selected location/country
     */
    async loadServicesByLocation(location) {
        console.log('🌍 Loading services for location:', location);

        const countriesList = document.getElementById('countriesList');

        // Show loading
        countriesList.innerHTML = `
            <div class="countries-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading services for ${location}...</p>
            </div>
        `;

        try {
            // Fetch services by location (using relative path)
            const apiUrl = `../api/services/by-location.php?location=${encodeURIComponent(location)}`;
            console.log('📡 API URL:', apiUrl);

            const response = await fetch(apiUrl);
            console.log('📡 Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Log raw response for debugging
            const responseText = await response.text();
            console.log('📄 Raw response (first 500 chars):', responseText.substring(0, 500));

            // Check if response is valid JSON
            if (!responseText.trim().startsWith('{')) {
                console.error('❌ Response is not JSON:', responseText);
                throw new Error('Invalid JSON response from server');
            }

            const data = JSON.parse(responseText);
            console.log('✅ JSON parsed:', data);

            if (!data.success) {
                throw new Error(data.message || 'Failed to load services');
            }

            console.log(`✅ Loaded ${data.total} services for ${location}`);

            if (data.services && data.services.length > 0) {
                // Render services
                countriesList.innerHTML = `
                    <div class="countries-results-header">
                        <h3>
                            <i class="fas fa-map-marker-alt"></i>
                            ${location}
                        </h3>
                        <span class="countries-results-count">${data.total} services found</span>
                    </div>
                    <div class="countries-services-grid">
                        ${data.services.map(service => this.renderCountryService(service)).join('')}
                    </div>
                `;

                // Add click listeners
                countriesList.querySelectorAll('.country-service-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const serviceData = JSON.parse(item.dataset.serviceData);
                        console.log('🌍 Country service clicked:', serviceData);

                        // Switch to New Order tab and populate with this service
                        this.currentService = serviceData;
                        this.switchTab('new-order');

                        setTimeout(() => {
                            this.populateServiceInfo();

                            // Reset quantity to min_quantity of new service
                            const quantityInput = document.getElementById('orderQuantity');
                            quantityInput.value = serviceData.min_quantity;
                            console.log(`📝 Quantity reset to min: ${serviceData.min_quantity}`);

                            // Recalculate price with new service + new quantity
                            this.updateCharge();
                            console.log('✅ Price recalculated for country service');
                        }, 100);
                    });
                });
            } else {
                // No services found
                countriesList.innerHTML = `
                    <div class="countries-empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <h3>No Services Found</h3>
                        <p>No services available for ${location}</p>
                        <button class="btn-secondary" onclick="document.getElementById('countrySelector').value = ''; orderModal.loadCountries();">
                            Try Another Location
                        </button>
                    </div>
                `;
            }

        } catch (error) {
            console.error('❌ Error loading services:', error);
            countriesList.innerHTML = `
                <div class="countries-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error Loading Services</h3>
                    <p>${error.message}</p>
                    <button class="btn-secondary" onclick="orderModal.loadServicesByLocation('${location}')">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                </div>
            `;
        }
    }

    /**
     * Render a service item for countries tab
     * @param {Object} service - Service data
     * @returns {string} HTML string
     */
    renderCountryService(service) {
        // Use sell_price from database (not 'price')
        const price = parseFloat(service.sell_price || service.price || 0).toFixed(4);
        const platform = service.platform || 'Unknown';
        const name = service.name || 'Unnamed Service';

        console.log('🎨 Rendering country service:', {
            id: service.id,
            name: service.name,
            sell_price: service.sell_price,
            price: price,
            min: service.min_quantity,
            max: service.max_quantity
        });

        // Prepare service data with correct 'price' field for modal
        const serviceDataForModal = {
            ...service,
            price: parseFloat(service.sell_price || service.price || 0)
        };

        return `
            <div class="country-service-item" data-service-data='${JSON.stringify(serviceDataForModal)}'>
                <div class="country-service-header">
                    <span class="country-service-platform">${platform}</span>
                    ${service.tier ? `<span class="country-service-tier tier-${service.tier}">${service.tier}</span>` : ''}
                </div>
                <div class="country-service-name">${name}</div>
                <div class="country-service-meta">
                    <span class="country-service-price">
                        <i class="fas fa-dollar-sign"></i>
                        $${price}/1K
                    </span>
                    <span class="country-service-min">
                        <i class="fas fa-layer-group"></i>
                        Min: ${service.min_quantity}
                    </span>
                </div>
            </div>
        `;
    }

    populateServiceInfo() {
        const service = this.currentService;

        // Service sélectionné (header)
        document.getElementById('orderSelectedService').innerHTML = `
            <div class="order-selected-service-header">
                <span class="order-selected-service-platform">${service.platform}</span>
                <span class="order-selected-service-id">#${service.id}</span>
            </div>
            <div class="order-selected-service-name">${service.name}</div>
            <div class="order-selected-service-meta">
                <span class="order-service-badge">
                    <i class="fas fa-dollar-sign"></i>
                    $${this.formatPrice(service.price)} / 1K
                </span>
                ${service.quality ? `<span class="order-service-badge">${this.getQualityIcon(service.quality)} ${service.quality}</span>` : ''}
                ${service.tier ? `<span class="order-service-badge">${this.getTierIcon(service.tier)} ${service.tier}</span>` : ''}
            </div>
        `;

        // Min/Max quantity
        document.getElementById('orderMinQty').textContent = this.formatNumber(service.min_quantity);
        document.getElementById('orderMaxQty').textContent = this.formatNumber(service.max_quantity);
        document.getElementById('orderQuantity').min = service.min_quantity;
        document.getElementById('orderQuantity').max = service.max_quantity;

        // Description panel (colonne droite)
        document.getElementById('orderServiceDetails').innerHTML = `
            ${service.description ? `
                <div class="order-description-item" style="background: rgba(102, 126, 234, 0.1); border-left: 3px solid #667eea;">
                    <div class="order-description-item-label">
                        <i class="fas fa-info-circle"></i>
                        Service Description
                    </div>
                    <div class="order-description-item-value" style="line-height: 1.5; white-space: pre-line;">
                        ${service.description}
                    </div>
                </div>
            ` : ''
            }
            
            ${service.location ? `
                <div class="order-description-item">
                    <div class="order-description-item-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Location
                    </div>
                    <div class="order-description-item-value">${service.location}</div>
                </div>
            ` : ''
            }
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-star"></i>
                    Quality
                </div>
                <div class="order-description-item-value">${service.quality || 'Standard'}</div>
            </div>
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-tachometer-alt"></i>
                    Speed
                </div>
                <div class="order-description-item-value">${service.speed || service.average_time || 'Standard'}</div>
            </div>
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-shield-alt"></i>
                    Refill
                </div>
                <div class="order-description-item-value">${service.refill_type}</div>
            </div>
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-arrow-down"></i>
                    Drop Rate
                </div>
                <div class="order-description-item-value">${service.drop_rate}</div>
            </div>
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-hashtag"></i>
                    Min / Max Quantity
                </div>
                <div class="order-description-item-value">
                    ${this.formatNumber(service.min_quantity)} - ${this.formatNumber(service.max_quantity)}
                </div>
            </div>
            
            <div class="order-description-item">
                <div class="order-description-item-label">
                    <i class="fas fa-dollar-sign"></i>
                    Price per 1K
                </div>
                <div class="order-description-item-value" style="color: #10b981; font-weight: 600;">
                    $${this.formatPrice(service.price)}
                </div>
            </div>
            
            <div class="order-description-notes">
                <div class="order-description-notes-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Important Notes
                </div>
                <ul class="order-description-notes-list">
                    <li>Check the link format carefully before placing the order</li>
                    <li>Make sure your account is public, not private</li>
                    ${service.dripfeed ? '<li>Drip-feed option is available for this service</li>' : ''}
                    ${service.cancel ? '<li>This order can be cancelled if needed</li>' : '<li>This order cannot be cancelled once placed</li>'}
                </ul>
            </div>
`;
    }

    toggleDripfeed() {
        const checkbox = document.getElementById('orderDripfeed');
        const options = document.getElementById('orderDripfeedOptions');

        if (checkbox.checked) {
            options.classList.add('active');
        } else {
            options.classList.remove('active');
        }
    }

    updateCharge() {
        // Vérification que currentService existe
        if (!this.currentService || !this.currentService.price) {
            console.warn('⚠️ updateCharge() called but no currentService or price available');
            return;
        }

        const quantity = parseInt(document.getElementById('orderQuantity').value) || 0;
        const price = this.currentService.price;

        console.log('💰 Calculating charge:', {
            quantity,
            price,
            serviceId: this.currentService.id,
            serviceName: this.currentService.name
        });

        // Calcul du prix total avec précision (éviter les erreurs d'arrondi JavaScript)
        const totalCharge = this.calculatePrecisePrice(quantity, price);

        // Affichage avec formatage adaptatif
        document.getElementById('orderChargeAmount').textContent = `$${this.formatPrice(totalCharge)}`;

        // Récupérer le vrai solde utilisateur depuis le top-bar
        const balanceElement = document.querySelector('.balance-amount');
        let currentBalance = 0;

        if (balanceElement) {
            // Le format est "$X.XX" - extraire le nombre
            const balanceText = balanceElement.textContent.trim();
            currentBalance = parseFloat(balanceText.replace(/[$,]/g, '')) || 0;

            console.log('💵 Balance info:', {
                element: balanceElement,
                text: balanceText,
                parsed: currentBalance,
                totalCharge: totalCharge
            });
        } else {
            console.warn('⚠️ Balance element not found (.balance-amount)');
        }

        // Afficher le solde actuel
        const balanceCurrentEl = document.getElementById('orderBalanceCurrent');
        if (balanceCurrentEl) {
            balanceCurrentEl.textContent = `$${this.formatPrice(currentBalance)}`;
            balanceCurrentEl.style.color = '#10b981'; // Toujours vert pour current
        }

        // Calculer le solde restant avec précision (arrondi à 8 décimales)
        const balanceAfter = this.roundPrecise(currentBalance - totalCharge, 8);

        const balanceAfterEl = document.getElementById('orderBalanceAfter');
        balanceAfterEl.textContent = `$${this.formatPrice(balanceAfter)}`;
        balanceAfterEl.style.color = balanceAfter < 0 ? '#ef4444' : '#10b981';

        console.log('💰 Balance calculation:', {
            current: currentBalance,
            charge: totalCharge,
            after: balanceAfter,
            difference: currentBalance - totalCharge,
            rounded: balanceAfter
        });

        // Désactiver le bouton si solde insuffisant
        const submitBtn = document.getElementById('orderBtnSubmit');
        if (balanceAfter < 0) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> Insufficient Balance';
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Place Order';
        }
    }

    async submitOrder() {
        const submitBtn = document.getElementById('orderBtnSubmit');
        const originalHTML = submitBtn.innerHTML;

        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Récupérer les données du formulaire
        const formData = {
            service_id: this.currentService.id,
            provider_id: this.currentService.provider_id,
            link: document.getElementById('orderLink').value,
            quantity: parseInt(document.getElementById('orderQuantity').value),
            dripfeed: document.getElementById('orderDripfeed').checked ? 1 : 0,
            dripfeed_runs: document.getElementById('orderDripfeedRuns').value || null,
            dripfeed_interval: document.getElementById('orderDripfeedInterval').value || null
        };

        try {
            const response = await fetch('../api/create-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert('success', result.message || 'Order placed successfully!');

                // Rediriger après 2 secondes
                setTimeout(() => {
                    window.location.href = `../orders/tracking.php?id=${result.order_id}`;
                }, 2000);
            } else {
                this.showAlert('error', result.message || 'Failed to place order');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }
        } catch (error) {
            console.error('Order submission error:', error);
            this.showAlert('error', 'Network error. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        }
    }

    showAlert(type, message) {
        const alertDiv = document.getElementById('orderAlert');
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        alertDiv.innerHTML = `
            <div class="order-alert order-alert-${type}">
                <i class="fas ${iconClass}"></i>
                <span>${message}</span>
            </div>
        `;

        // Auto-hide après 5 secondes
        setTimeout(() => {
            alertDiv.innerHTML = '';
        }, 5000);
    }

    formatPrice(price) {
        if (price === 0) return '0.00';
        if (price >= 1) return price.toFixed(2);        // $1.50
        if (price >= 0.01) return price.toFixed(3);     // $0.015
        if (price >= 0.001) return price.toFixed(4);    // $0.0015
        if (price >= 0.0001) return price.toFixed(5);   // $0.00015
        if (price >= 0.00001) return price.toFixed(6);  // $0.000015
        if (price >= 0.000001) return price.toFixed(7); // $0.0000015
        return price.toFixed(8);                        // $0.00000015 (max precision)
    }

    /**
     * Calcul précis du prix total (évite les erreurs d'arrondi JavaScript)
     * @param {number} quantity - Quantité commandée
     * @param {number} pricePerK - Prix par 1000
     * @return {number} Prix total arrondi à 8 décimales
     */
    calculatePrecisePrice(quantity, pricePerK) {
        // Formule: (quantity * price) / 1000
        // Pour éviter les erreurs de virgule flottante, on multiplie par 1e8 puis on divise

        const multiplier = 1e8; // 100 millions pour 8 décimales de précision

        // Convertir en entiers
        const quantityInt = Math.round(quantity * multiplier);
        const priceInt = Math.round(pricePerK * multiplier);

        // Calcul: (quantity * price) / 1000
        // En entiers: (quantityInt * priceInt) / (multiplier * multiplier) / 1000
        const result = (quantityInt * priceInt) / (multiplier * multiplier) / 1000;

        // Arrondir à 8 décimales pour nettoyer les imprécisions résiduelles
        return this.roundPrecise(result, 8);
    }

    /**
     * Arrondit un nombre à N décimales de manière précise
     * @param {number} num - Nombre à arrondir
     * @param {number} decimals - Nombre de décimales (défaut: 8)
     * @return {number} Nombre arrondi
     */
    roundPrecise(num, decimals = 8) {
        const multiplier = Math.pow(10, decimals);
        return Math.round(num * multiplier) / multiplier;
    }

    formatNumber(num) {
        if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
        if (num >= 1000) return (num / 1000).toFixed(0) + 'K';
        return num.toString();
    }

    getQualityIcon(quality) {
        const icons = {
            'High': '👑',
            'Premium': '💎',
            'Standard': '⭐',
            'Budget': '💼'
        };
        return icons[quality] || '⭐';
    }

    getTierIcon(tier) {
        const icons = {
            'Premium': '💎',
            'High': '👑',
            'Standard': '⭐',
            'Budget': '💼'
        };
        return icons[tier] || '⭐';
    }

    /**
     * Copy service link to clipboard with toast notification
     */
    copyServiceLink() {
        if (!this.currentService) {
            this.showToast('⚠️ No service selected', 'warning');
            return;
        }

        // Generate service link
        const baseUrl = window.location.origin + window.location.pathname;
        const serviceUrl = `${baseUrl}?service=${this.currentService.id}`;

        // Copy to clipboard
        navigator.clipboard.writeText(serviceUrl)
            .then(() => {
                this.showToast('✅ Link copied to clipboard!', 'success');
                console.log('📋 Service link copied:', serviceUrl);
            })
            .catch(err => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = serviceUrl;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();

                try {
                    document.execCommand('copy');
                    this.showToast('✅ Link copied to clipboard!', 'success');
                    console.log('📋 Service link copied (fallback):', serviceUrl);
                } catch (err) {
                    this.showToast('❌ Failed to copy link', 'error');
                    console.error('Copy failed:', err);
                }

                document.body.removeChild(textArea);
            });
    }

    /**
     * Show toast notification
     * @param {string} message - Toast message
     * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
     */
    showToast(message, type = 'info') {
        // Remove existing toast if any
        const existingToast = document.getElementById('orderToast');
        if (existingToast) {
            existingToast.remove();
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.id = 'orderToast';
        toast.className = `order-toast order-toast-${type}`;
        toast.textContent = message;

        // Add to body
        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// Initialiser le modal au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    window.orderModal = new OrderModal();
});
