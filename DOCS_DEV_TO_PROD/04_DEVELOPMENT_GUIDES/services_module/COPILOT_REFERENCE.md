# 🤖 SERVICES MODULE - GITHUB COPILOT REFERENCE

Guide de référence rapide pour GitHub Copilot lors de l'édition du module Services.

---

## 📁 STRUCTURE ACTUELLE (v3.0)

```
services/
├── index.php                    # Page principale PHP
├── css/
│   ├── filters.css             # Styles filtres desktop
│   ├── mobile-filters.css      # Styles mobile (<599px)
│   └── order-modal.css         # Styles modal commande
├── js/
│   ├── services-manager.js     # Gestionnaire principal ⭐
│   ├── cards-enhancement.js    # Métadonnées enrichies
│   └── order-modal.js          # Modal de commande
├── archive/                     # ❌ NE PAS MODIFIER
└── [4 fichiers .md]            # Documentation
```

---

## ⚡ ACTIONS RAPIDES

### **Ajouter un Filtre**

📂 `js/services-manager.js` → `filters` object → `attachEvents()` method

### **Modifier Styles Filtres**

📂 Desktop: `css/filters.css`  
📂 Mobile: `css/mobile-filters.css` (media query @max-width: 599px)

### **Ajouter Badge sur Card**

📂 `js/cards-enhancement.js` → Ajouter après les autres `if (service.xxx)`

### **Modifier Modal**

📂 `js/order-modal.js` → Méthodes: `createModal()`, `open()`, `submit()`

---

## 🔍 POINTS D'ATTENTION

### **Chemins Importants**

```php
// Dans index.php - Toujours utiliser ces chemins:
<link rel="stylesheet" href="css/filters.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="css/mobile-filters.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="css/order-modal.css?v=<?php echo time(); ?>">
<script src="js/services-manager.js?v=<?php echo time(); ?>"></script>
<script src="js/order-modal.js?v=<?php echo time(); ?>"></script>
```

### **Objet Principal**

```javascript
// js/services-manager.js
const ServicesManagerMultiline = {
    filters: { ... },           // Filtres actifs
    currentPage: 1,             // Page courante
    itemsPerPage: 20,           // Services par page
    isLoading: false,           // État chargement

    init() { ... },             // Initialisation
    loadServices() { ... },     // Chargement API
    renderServices() { ... },   // Affichage grid
    attachEvents() { ... },     // Event listeners
    reloadWithFilters() { ... } // Recharger avec filtres
};
```

### **Classes CSS Clés**

```css
/* Filtres */
.services-filters-multiline     /* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
/* Container sticky */
.filter-group-multiline         /* Groupe de filtres */
.platform-btn-multiline         /* Bouton plateforme */
.filter-select-multiline        /* Select de filtre */

/* Grid */
.services-grid-modern           /* Container grid */
.service-card-modern            /* Card service */
.service-order-btn              /* Bouton Buy */

/* Modal */
.order-modal-overlay            /* Overlay fond */
.order-modal; /* Container modal */
```

---

## 🚨 RÈGLES STRICTES

### **❌ NE JAMAIS**

- Modifier fichiers dans `archive/`
- Utiliser anciens noms (`filters-2lines.css`, `services-manager-multiline.js`)
- Oublier `?v=<?php echo time(); ?>` sur CSS/JS
- Commit avec `console.log()` en production
- Utiliser `var` (toujours `let`/`const`)

### **✅ TOUJOURS**

- Commenter nouvelles fonctions (JSDoc)
- Tester mobile ET desktop
- Mettre à jour `CHANGELOG.md`
- Utiliser fichiers dans `css/` et `js/`
- Respecter conventions de nommage

---

## 🎯 PATTERNS COURANTS

### **Ajouter Event Listener**

```javascript
// Dans attachEvents()
document.querySelector("#mySelect").addEventListener("change", (e) => {
  this.filters.newFilter = e.target.value;
  this.reloadWithFilters();
});
```

### **Ajouter Badge Card**

```javascript
// Dans cards-enhancement.js
if (service.newProp) {
  features.innerHTML += `
        <span class="service-feature-item feature-new">
            <i class="fas fa-icon"></i> ${service.newProp}
        </span>
    `;
}
```

### **Modifier Responsive**

```css
/* Dans mobile-filters.css */
@media (max-width: 599px) {
  .my-element {
    /* Styles mobile */
  }
}
```

### **Console Log Debug**

```javascript
console.log("🚀 Initialized");
console.log("📊 Data:", data);
console.error("❌ Error:", error);
console.warn("⚠️ Warning:", warning);
```

---

## 📚 DOCUMENTATION DISPONIBLE

| Fichier             | Usage                                          |
| ------------------- | ---------------------------------------------- |
| `README.md`         | Architecture complète, fonctionnalités, guides |
| `QUICK_START.md`    | Démarrage rapide, exemples concrets            |
| `CHANGELOG.md`      | Historique des versions                        |
| `CONVENTIONS.md`    | Standards de code, nomenclature                |
| `archive/README.md` | Infos fichiers archivés                        |

---

## 🔗 API ENDPOINTS

```javascript
// Récupération services
fetch('../api/services.php?' + new URLSearchParams({
    platform: this.filters.platform,
    tier: this.filters.tier,
    page: this.currentPage,
    limit: this.itemsPerPage
}))

// Création commande
fetch('../api/create-order.php', {
    method: 'POST',
    body: JSON.stringify(orderData)
})

// Favoris
fetch('../api/favorites/add.php', { ... })
fetch('../api/favorites/remove.php', { ... })
```

---

## 💡 CONSEILS COPILOT

### **Lors de Modifications**

1. Lire d'abord `README.md` et `QUICK_START.md`
2. Vérifier `CONVENTIONS.md` pour standards
3. Consulter code existant pour patterns
4. Commenter toutes nouvelles fonctions
5. Tester en mobile et desktop
6. Mettre à jour `CHANGELOG.md`

### **Lors de Debug**

1. Vérifier console navigateur (F12)
2. Chercher logs avec emojis (🚀, 📊, ❌, ⚠️)
3. Vérifier que les chemins css/ et js/ sont corrects
4. Valider que les event listeners sont attachés
5. Tester avec différentes tailles d'écran

### **Lors d'Ajouts**

1. Respecter nomenclature existante
2. Utiliser mêmes patterns que code actuel
3. Documenter avec JSDoc/PHPDoc
4. Ajouter commentaires inline si logique complexe
5. Tester toutes les fonctionnalités impactées

---

## 🎨 STYLE GUIDE EXPRESS

```javascript
// ✅ Nommage
const myVariable = "value"; // camelCase
const MyClass = class {}; // PascalCase
const MY_CONSTANT = "value"; // UPPER_SNAKE_CASE

// ✅ Fonctions
function doSomething(param1, param2) {
  // 4 espaces indentation
  if (condition) {
    return result;
  }
}

// ✅ Objets
const config = {
  prop1: "value1",
  prop2: "value2",
};

// ✅ Promesses
fetch(url)
  .then((response) => response.json())
  .then((data) => processData(data))
  .catch((error) => console.error(error));
```

```css
/* ✅ Classes */
.kebab-case-naming {
}
.component-name {
}
.component-name-modifier {
}

/* ✅ Media queries */
@media (max-width: 599px) {
  .component {
  }
}
```

---

## 🔥 HOTFIXES COURANTS

### **Services ne chargent pas**

→ Vérifier `../api/services.php` et console

### **Filtres ne fonctionnent pas**

→ Vérifier `attachEvents()` et `reloadWithFilters()`

### **Modal ne s'ouvre pas**

→ Vérifier class `.service-order-btn` et `data-service-id`

### **Responsive cassé**

→ Vérifier `css/mobile-filters.css` media queries

### **Styles ne s'appliquent pas**

→ Vérifier cache avec `?v=<?php echo time(); ?>`

---

## 📊 MÉTRIQUES

- **Fichiers actifs:** 7 (1 PHP + 3 CSS + 3 JS)
- **Fichiers archivés:** 6
- **Documentation:** 5 fichiers .md
- **Lignes de doc:** ~1000 lignes
- **Version:** 3.0.0
- **Date:** 14 Octobre 2025

---

## 🎯 CHECKLIST AVANT COMMIT

```
[ ] Code commenté (JSDoc/PHPDoc)
[ ] Testé desktop (Chrome, Firefox)
[ ] Testé mobile (responsive)
[ ] Aucune erreur console
[ ] CHANGELOG.md mis à jour
[ ] README.md mis à jour (si nécessaire)
[ ] Pas de console.log() production
[ ] Nomenclature respectée
[ ] Chemins css/ et js/ corrects
[ ] Cache-busting avec ?v=time()
```

---

**🤖 Ce fichier est conçu pour GitHub Copilot. Toujours le consulter en premier !**

---

**Version:** 3.0  
**Dernière mise à jour:** 14 Octobre 2025  
**Mainteneur:** Équipe SMM Mastery
