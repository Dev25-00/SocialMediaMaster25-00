# 🚀 QUICK START - Module Services

Guide de démarrage rapide pour développer sur le module Services.

---

## 📦 STRUCTURE EN 30 SECONDES

```
services/
├── index.php              # Page principale
├── css/                   # Tous les styles
│   ├── filters.css       # Barre de filtres
│   ├── mobile-filters.css # Responsive mobile
│   └── order-modal.css   # Modal de commande
├── js/                    # Tous les scripts
│   ├── services-manager.js     # Gestionnaire principal ⭐
│   ├── cards-enhancement.js    # Métadonnées cards
│   └── order-modal.js          # Modal commande
└── archive/               # Anciennes versions (ne pas utiliser)
```

---

## ⚡ DÉMARRAGE RAPIDE

### **1. Modifier les Filtres**

📂 Fichier : `js/services-manager.js`

```javascript
// Ajouter un nouveau filtre
filters: {
    platform: '',
    newFilter: '',  // ← Ajouter ici
},

// Ajouter l'event listener
attachEvents() {
    document.querySelector('#myNewFilter').addEventListener('change', (e) => {
        this.filters.newFilter = e.target.value;
        this.reloadWithFilters();
    });
}
```

### **2. Modifier les Styles des Filtres**

📂 Fichier : `css/filters.css` (desktop) ou `css/mobile-filters.css` (mobile)

```css
/* Desktop */
.services-filters-multiline {
  /* Vos modifications ici */
}

/* Mobile (@media max-width: 599px) */
@media (max-width: 599px) {
  .services-filters-multiline {
    /* Vos modifications responsive ici */
  }
}
```

### **3. Ajouter un Badge sur les Cards**

📂 Fichier : `js/cards-enhancement.js`

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

### **4. Modifier le Modal de Commande**

📂 Fichier : `js/order-modal.js`

```javascript
// Chercher la méthode concernée
createModal() {
    // Modifier le HTML du modal ici
}

open(serviceData) {
    // Modifier le comportement d'ouverture ici
}
```

---

## 🐛 DÉBOGAGE RAPIDE

### **Services ne chargent pas**

```javascript
// Ouvrir la console (F12) et chercher :
console.log("📊 Services loaded:", services.length);
console.log("🔍 Current filters:", this.filters);

// Vérifier l'API :
// ../api/services.php?platform=Instagram&page=1&limit=20
```

### **Filtres ne fonctionnent pas**

```javascript
// Vérifier que l'event listener est attaché :
console.log('Event attached to:', document.querySelector('#filterSelect'));

// Vérifier que reloadWithFilters() est appelé :
reloadWithFilters() {
    console.log('🔄 Reloading with filters:', this.filters);
    // ...
}
```

### **Modal ne s'ouvre pas**

```javascript
// Vérifier le bouton :
<button class="service-order-btn" data-service-id="123">
  Buy
</button>;

// Vérifier dans la console :
console.log("🔗 Service ID detected:", serviceId);
console.log("✅ Service found, opening modal...");
```

### **Responsive cassé**

```css
/* Vérifier les media queries dans mobile-filters.css */
@media (max-width: 599px) {
  /* Vos règles mobile ici */
}

/* Forcer avec !important si nécessaire (temporaire) */
.element {
  property: value !important;
}
```

---

## 📚 DOCUMENTATION COMPLÈTE

- 📄 **README.md** - Architecture et fonctionnalités détaillées
- 📄 **CHANGELOG.md** - Historique des versions
- 📁 **DOCS_DEV_TO_PROD/** - Documentation projet complète
- 📁 **archive/README.md** - Infos sur fichiers archivés

---

## 🔗 LIENS UTILES

### **Fichiers Principaux**

| Fichier                   | Rôle                 | Quand Modifier                         |
| ------------------------- | -------------------- | -------------------------------------- |
| `index.php`               | Page HTML/PHP        | Ajouter sections, modifier structure   |
| `js/services-manager.js`  | Logique filtres/grid | Ajouter filtres, modifier comportement |
| `js/cards-enhancement.js` | Badges des cards     | Ajouter métadonnées visuelles          |
| `js/order-modal.js`       | Modal commande       | Modifier formulaire commande           |
| `css/filters.css`         | Styles filtres       | Changer couleurs, layout desktop       |
| `css/mobile-filters.css`  | Styles mobile        | Ajuster responsive mobile              |
| `css/order-modal.css`     | Styles modal         | Modifier apparence modal               |

### **API Endpoints**

- `../api/services.php` - Récupération services avec filtres
- `../api/create-order.php` - Création nouvelle commande
- `../api/favorites/` - Gestion favoris
- `../api/services/` - Services spécifiques

---

## 💡 TIPS & TRICKS

### **Forcer Rechargement CSS/JS**

```php
<!-- Utiliser cache-busting avec timestamp -->
<link rel="stylesheet" href="css/filters.css?v=<?php echo time(); ?>">
<script src="js/services-manager.js?v=<?php echo time(); ?>"></script>
```

### **Tester Responsive**

```javascript
// Simuler mobile en console
if (window.innerWidth <= 599) {
  console.log("📱 Mode mobile actif");
}

// Ou utiliser Chrome DevTools (F12) → Toggle Device Toolbar (Ctrl+Shift+M)
```

### **Console Logs Utiles**

```javascript
// Services Manager
console.log("🚀 ServicesManager v3.0 initialized");
console.log("📊 Services:", services);
console.log("🔍 Filters:", this.filters);
console.log(
  "📄 Page:",
  this.currentPage,
  "/",
  Math.ceil(this.totalAvailable / this.itemsPerPage)
);

// Order Modal
console.log("🛒 Opening modal for service:", serviceData);
console.log("💰 User balance:", this.userBalance);
console.log("💵 Order total:", total);
```

---

## ⚠️ À ÉVITER

❌ **NE PAS** modifier les fichiers dans `archive/` (anciennes versions)  
❌ **NE PAS** utiliser les anciens noms (`filters-2lines.css`, `services-manager-multiline.js`)  
❌ **NE PAS** oublier de commenter les nouvelles fonctions  
❌ **NE PAS** oublier de tester en mobile ET desktop  
❌ **NE PAS** commit sans mettre à jour CHANGELOG.md

✅ **TOUJOURS** utiliser les fichiers dans `css/` et `js/`  
✅ **TOUJOURS** commenter avec JSDoc  
✅ **TOUJOURS** tester responsive  
✅ **TOUJOURS** documenter les changements

---

## 🎯 CHECKLIST AVANT COMMIT

- [ ] Code commenté (JSDoc/PHPDoc)
- [ ] Testé en desktop (Chrome, Firefox)
- [ ] Testé en mobile (responsive)
- [ ] Aucune erreur console
- [ ] CHANGELOG.md mis à jour
- [ ] README.md mis à jour (si nécessaire)
- [ ] Code formaté et indenté
- [ ] Pas de console.log() en production

---

## 📞 BESOIN D'AIDE ?

1. 📖 Lire **README.md** (documentation complète)
2. 🔍 Chercher dans **CHANGELOG.md** (historique)
3. 📁 Consulter **DOCS_DEV_TO_PROD/**
4. 💬 Demander à l'équipe

---

**🚀 Prêt à développer ! Bon code !**

---

**Dernière mise à jour :** 14 Octobre 2025  
**Version :** 3.0  
**Format :** Quick Start Guide
