# 📦 SERVICES - Module de Gestion des Services SMM

**Version :** 3.0 - Réorganisé et commenté  
**Date :** 14 Octobre 2025  
**Documentation :** `DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\`

---

## 📁 STRUCTURE DU DOSSIER

```
services/
├── index.php                    # Page principale des services
├── css/                         # Styles CSS organisés
│   ├── filters.css             # Styles des filtres (2 lignes, sticky)
│   ├── mobile-filters.css      # Responsive mobile ultra-compact
│   └── order-modal.css         # Styles du modal de commande
├── js/                          # Scripts JavaScript commentés
│   ├── services-manager.js     # Gestionnaire principal (filtres, grid, scroll)
│   ├── cards-enhancement.js    # Métadonnées enrichies des cards
│   └── order-modal.js          # Modal de commande interactif
└── archive/                     # Anciennes versions (référence uniquement)
    ├── README.md               # Documentation des fichiers archivés
    └── [fichiers obsolètes]
```

---

## 🎯 FONCTIONNALITÉS PRINCIPALES

### **1️⃣ Affichage Grid Responsive**

- Grid dynamique : 1 colonne (mobile) → 5 colonnes (ultra-large desktop)
- Cards modernes avec badges et métadonnées
- Skeleton loading pendant chargement
- Infinite scroll (20 services par page)

### **2️⃣ Filtres Multi-Critères**

- **Plateforme** : Instagram, YouTube, TikTok, Facebook, etc. (30+ plateformes)
- **Tier** : Basic, Standard, Premium, VIP
- **Action Type** : Followers, Likes, Views, Subscribers, Comments, Shares
- **Drop Rate** : No Drop, Low Drop, High Drop
- **Refill** : 0 jours, 30j, 90j, 365j, Lifetime
- **Prix** : Fourchette min/max en USD
- **Tri** : Prix (↑↓), Nom (A-Z), Popularité

### **3️⃣ Modal de Commande**

- 3 onglets : New Order, Favorites, Countries
- Calcul automatique du prix en temps réel
- Validation des champs (quantity, link)
- Vérification du solde utilisateur
- Support dripfeed, comments, et autres paramètres

### **4️⃣ Fonctionnalités Avancées**

- Deep linking : `?service=ID` pour lien direct
- Bouton partage avec copie URL
- Système de favoris (add/remove)
- Liste des pays disponibles par service
- Sticky filters avec détection d'état

---

## 🚀 UTILISATION

### **Chargement de la Page**

```php
require_once '../config.php';
require_once '../functions.php';
require_once '../includes/icons-config.php';

// Vérification authentification
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Inclusion de index.php
// Les services sont chargés progressivement via JavaScript
```

### **Initialisation JavaScript**

```javascript
// Automatique au chargement du DOM
document.addEventListener("DOMContentLoaded", () => {
  ServicesManagerMultiline.init(); // Démarre le gestionnaire
});
```

---

## 🔧 MODIFICATION & DÉVELOPPEMENT

### **Ajouter un Nouveau Filtre**

**Fichier :** `js/services-manager.js`

```javascript
// 1. Ajouter la propriété dans filters
filters: {
    // ... autres filtres
    newFilter: '',  // Nouveau filtre
},

// 2. Ajouter l'event listener dans attachEvents()
document.querySelector('#newFilterSelect').addEventListener('change', (e) => {
    this.filters.newFilter = e.target.value;
    this.reloadWithFilters();
});

// 3. Utiliser dans loadServices() pour l'API call
const params = new URLSearchParams({
    // ... autres params
    newFilter: this.filters.newFilter
});
```

### **Ajouter une Métadonnée de Card**

**Fichier :** `js/cards-enhancement.js`

```javascript
// Ajouter après les autres badges
if (service.newMetadata) {
  features.innerHTML += `
        <span class="service-feature-item feature-new">
            <i class="fas fa-icon"></i> ${service.newMetadata}
        </span>
    `;
}
```

### **Modifier le Style des Filtres**

**Fichiers :**

- `css/filters.css` - Desktop
- `css/mobile-filters.css` - Mobile (max-width: 599px)

---

## 🎨 CLASSES CSS PRINCIPALES

### **Grid**

```css
.services-grid-modern          /* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
/* Container grid principal */
.service-card-modern           /* Card individuelle */
.service-card-header           /* En-tête avec icône plateforme */
.service-card-body             /* Corps avec titre et description */
.service-card-footer; /* Footer avec prix et bouton */
```

### **Filtres**

```css
.services-filters-multiline    /* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
/* Container filtres sticky */
.filters-row-primary           /* Ligne 1 : Plateformes, Tier, Action */
.filters-row-secondary         /* Ligne 2 : Drop, Refill, Prix, Tri */
.filter-group-multiline        /* Groupe de filtres */
.filter-select-multiline       /* Select de filtre */
.platform-btn-multiline; /* Bouton plateforme */
```

### **Modal**

```css
.order-modal-overlay           /* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
/* Overlay fond sombre */
.order-modal                   /* Container modal */
.order-modal-header            /* Header avec tabs */
.order-modal-body              /* Corps avec formulaire */
.order-modal-footer; /* Footer avec actions */
```

---

## 🐛 DÉBOGAGE

### **Console Logs Utiles**

```javascript
// Services Manager
console.log("🚀 ServicesManager v3.0 initialized");
console.log("📊 Services loaded:", services.length);
console.log("🔍 Current filters:", this.filters);

// Order Modal
console.log("🔗 Service ID detected in URL:", serviceId);
console.log("✅ Service found, opening modal...");
```

### **Problèmes Courants**

| Problème                    | Solution                                             |
| --------------------------- | ---------------------------------------------------- |
| Services ne chargent pas    | Vérifier `../api/services.php` et logs console       |
| Filtres ne fonctionnent pas | Vérifier event listeners dans `attachEvents()`       |
| Modal ne s'ouvre pas        | Vérifier class `.service-order-btn` sur boutons      |
| Responsive cassé            | Vérifier media queries dans `css/mobile-filters.css` |

---

## 📊 PERFORMANCES

### **Optimisations Implémentées**

- ✅ Chargement progressif (20 services/page)
- ✅ Debounce sur filtres rapides (300ms)
- ✅ AbortController pour annuler requêtes en cours
- ✅ Skeleton loading pour feedback utilisateur
- ✅ Lazy loading des images (si implémenté)
- ✅ Will-change sur filtres sticky

### **Métriques Cibles**

- First Contentful Paint (FCP) : < 1.5s
- Largest Contentful Paint (LCP) : < 2.5s
- Cumulative Layout Shift (CLS) : < 0.1
- First Input Delay (FID) : < 100ms

---

## 🔐 SÉCURITÉ

### **Patterns Appliqués**

```php
// Sanitisation des inputs
$input = filter_var($_POST['input'], FILTER_SANITIZE_STRING);
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

// Requêtes préparées
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
```

### **Protection CSRF**

- Token CSRF sur formulaire de commande
- Validation côté serveur dans `create-order.php`

---

## 📚 DOCUMENTATION COMPLÈTE

### **Guides de Référence**

- 📁 `DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\installation\`
- 📁 `DOCS_DEV_TO_PROD\PHASE11_AMELIORATIONS_FILTRES_V2.9.md`
- 📁 `DOCS_DEV_TO_PROD\PHASE12_UX_IMPROVEMENTS_RAPPORT.md`

### **Instructions GitHub Copilot**

- 📄 `.github\instructions\instructions.instructions.md`
- 📄 `DOCS_DEV_TO_PROD\COPILOT_GUIDE_PRATIQUE.md`

---

## 🔄 HISTORIQUE DES VERSIONS

### **v3.0 - 14/10/2025** _(Actuel)_

- ✅ Réorganisation complète du dossier
- ✅ Commentaires détaillés sur toutes les fonctions
- ✅ Séparation css/ et js/
- ✅ Archivage des anciennes versions
- ✅ Documentation complète

### **v2.9 - 13/10/2025**

- Amélioration des filtres multi-lignes
- Ajout métadonnées enrichies (quality, location, speed)
- Optimisation responsive mobile

### **v2.5 - 12/10/2025**

- Refonte UI/UX des services
- Cards modernes avec badges
- Sticky filters

---

## 🤝 CONTRIBUTION

Pour modifier ce module :

1. Consulter cette documentation
2. Respecter les conventions de code (voir `.github/instructions/`)
3. Commenter toutes les nouvelles fonctions
4. Tester en mobile ET desktop
5. Mettre à jour la documentation

---

**🔑 RÈGLE D'OR :** Toujours maintenir la cohérence avec l'existant et documenter tous les changements !

---

**Dernière mise à jour :** 14 Octobre 2025  
**Mainteneur :** Équipe SMM Mastery  
**Support :** Consulter `DOCS_DEV_TO_PROD\`
