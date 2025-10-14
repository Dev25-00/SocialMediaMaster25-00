# 📐 CONVENTIONS DE CODE - Module Services

Guide des conventions et standards à respecter pour le module Services.

---

## 📝 NOMENCLATURE

### **Fichiers**

```
✅ CORRECT                          ❌ INCORRECT
services-manager.js                 ServicesManager.js
cards-enhancement.js                CARDS_ENHANCEMENT_V2.js
mobile-filters.css                  mobile-filters-fix-v3.css
order-modal.js                      orderModal.js

RÈGLES:
- Minuscules uniquement
- Séparateur : tiret (-)
- Pas de versions dans le nom (v1, v2, V3)
- Pas de suffixes redondants (-multiline, -fix)
```

### **Classes CSS**

```css
✅ CORRECT
.services-filters-multiline
.filter-group-multiline
.service-card-modern
.order-modal-overlay

❌ INCORRECT
.ServicesFilters
.filter_group
.serviceCard
.OrderModal

RÈGLES:
- Kebab-case (tirets)
- Préfixes descriptifs
- Suffixes pour variantes (-modern, -multiline)
```

### **Variables JavaScript**

```javascript
✅ CORRECT
let currentPage = 1;
const platformConfig = {};
this.userBalance = 0;
const orderTotal = calculateTotal();

❌ INCORRECT
let CurrentPage = 1;
const platform_config = {};
this.UserBalance = 0;
const order_total = calculateTotal();

RÈGLES:
- camelCase
- Noms descriptifs
- Pas de snake_case
- Constantes en UPPER_CASE si vraiment constantes
```

### **Fonctions JavaScript**

```javascript
✅ CORRECT
getPlatformConfig(platformName) { }
loadServices() { }
attachEvents() { }
reloadWithFilters() { }

❌ INCORRECT
get_platform_config(platform_name) { }
LoadServices() { }
attachevents() { }
reload_with_filters() { }

RÈGLES:
- camelCase
- Verbe + Nom (getPlatform, loadServices)
- Noms explicites
```

---

## 📚 COMMENTAIRES & DOCUMENTATION

### **Headers de Fichiers**

#### **JavaScript**

```javascript
/**
 * SMM Mastery - [Nom du Module]
 * Date: DD Mois YYYY
 * Version: X.Y.Z
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\
 *
 * FONCTIONNALITÉS PRINCIPALES:
 * - Feature 1 détaillée
 * - Feature 2 détaillée
 * - Feature 3 détaillée
 *
 * DÉPENDANCES:
 * - fichier1.css (description)
 * - fichier2.js (description)
 * - ../api/endpoint.php (description)
 *
 * UTILISATION:
 * const instance = new ClassName();
 * instance.method();
 */
```

#### **PHP**

```php
/**
 * SMM Mastery - [Nom de la Page]
 * Date: DD Mois YYYY
 * Version: X.Y.Z
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\
 *
 * FONCTIONNALITÉS:
 * - Feature 1
 * - Feature 2
 * - Feature 3
 *
 * DÉPENDANCES:
 * - ../config.php (configuration)
 * - ../functions.php (fonctions globales)
 * - includes/header.php (en-tête)
 *
 * STRUCTURE:
 * - Section 1
 * - Section 2
 * - Section 3
 */
```

#### **CSS**

```css
/**
 * SMM Mastery - [Nom du Composant]
 * Date: DD Mois YYYY
 * Version: X.Y.Z
 * 
 * DESCRIPTION:
 * Description du rôle de ce fichier CSS
 * 
 * COMPOSANTS:
 * - Composant 1
 * - Composant 2
 * - Composant 3
 * 
 * BREAKPOINTS:
 * - Mobile: max-width 599px
 * - Tablet: 600px - 1023px
 * - Desktop: 1024px+
 */
```

### **Commentaires de Fonctions (JSDoc)**

```javascript
/**
 * DESCRIPTION DE LA FONCTION EN MAJUSCULES
 *
 * Description détaillée de ce que fait la fonction
 * et de son contexte d'utilisation
 *
 * @param {type} paramName - Description du paramètre
 * @return {type} Description de ce qui est retourné
 *
 * @example
 * const result = myFunction('example');
 * // Retourne: 'formatted_example'
 *
 * @throws {Error} Description des erreurs possibles
 * @see RelatedFunction - Pour plus d'infos
 * @version X.Y.Z
 * @date YYYY-MM-DD
 */
function myFunction(paramName) {
  // Implementation
}
```

### **Commentaires Inline**

```javascript
// ===== SECTION PRINCIPALE =====
// Description de cette section

// Action spécifique importante
const result = doSomething();

// Commentaire multi-lignes pour logique complexe
// Ligne 2 du commentaire
// Ligne 3 du commentaire
if (complexCondition) {
  // Explication de ce bloc
}
```

### **Commentaires CSS**

```css
/* ========================================
   SECTION PRINCIPALE
   ======================================== */

/* Composant spécifique */
.my-component {
  /* Propriété importante avec explication */
  property: value;
}

/* Mobile Responsive */
@media (max-width: 599px) {
  /* Ajustements mobile */
  .my-component {
    property: mobile-value;
  }
}
```

---

## 🎨 STYLE & FORMATAGE

### **Indentation**

```javascript
// 4 espaces (pas de tabs)
if (condition) {
  doSomething();
  if (nested) {
    doNestedThing();
  }
}

// Objets et tableaux
const config = {
  property1: "value1",
  property2: "value2",
  nested: {
    prop: "value",
  },
};
```

### **Espacement**

```javascript
// Opérateurs
const result = a + b;          // ✅
const result=a+b;              // ❌

// Fonctions
function myFunc(param1, param2) {  // ✅
function myFunc(param1,param2){    // ❌

// Conditions
if (condition) {               // ✅
if(condition){                 // ❌

// Boucles
for (let i = 0; i < 10; i++) { // ✅
for(let i=0;i<10;i++){         // ❌
```

### **Accolades**

```javascript
// ✅ CORRECT (Egyptian brackets)
if (condition) {
  doSomething();
} else {
  doOtherThing();
}

// ❌ INCORRECT
if (condition) {
  doSomething();
} else {
  doOtherThing();
}
```

### **Guillemets**

```javascript
// JavaScript : Guillemets simples préférés
const str = "Hello World"; // ✅
const str = "Hello World"; // ⚠️ OK mais pas préféré

// Interpolation : Backticks
const msg = `Hello ${name}`; // ✅
const msg = "Hello " + name; // ⚠️ OK mais moins moderne
```

```php
// PHP : Guillemets simples pour strings simples
$str = 'Hello World';          // ✅
$str = "Hello World";          // ⚠️ Plus lent

// Variables : Guillemets doubles
$msg = "Hello $name";          // ✅
$msg = 'Hello ' . $name;       // ⚠️ OK mais moins lisible
```

### **Lignes Vides**

```javascript
// Une ligne vide entre sections logiques
const setup = initSetup();

const data = loadData();
const processed = processData(data);

return processed;

// Pas de lignes vides multiples
const a = 1;

const b = 2; // ❌ Trop d'espace
```

---

## 🔒 SÉCURITÉ

### **Sanitisation PHP**

```php
// ✅ CORRECT
$input = filter_var($_POST['input'], FILTER_SANITIZE_STRING);
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

// Requêtes préparées
$stmt = $pdo->prepare("SELECT * FROM table WHERE id = ?");
$stmt->execute([$id]);

// ❌ INCORRECT
$input = $_POST['input'];  // Pas de sanitisation
$sql = "SELECT * FROM table WHERE id = $id";  // Injection SQL
```

### **Validation JavaScript**

```javascript
// ✅ CORRECT
const quantity = parseInt(inputValue, 10);
if (isNaN(quantity) || quantity < 1) {
  alert("Invalid quantity");
  return;
}

// ❌ INCORRECT
const quantity = inputValue; // Pas de validation
processOrder(quantity);
```

### **XSS Protection**

```javascript
// ✅ CORRECT
element.textContent = userInput; // Safe
element.innerHTML = DOMPurify.sanitize(userInput); // Safe avec lib

// ❌ INCORRECT
element.innerHTML = userInput; // XSS vulnérable
```

---

## ⚡ PERFORMANCES

### **Requêtes API**

```javascript
// ✅ CORRECT - Debounce
let timeout;
function search(query) {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    fetchResults(query);
  }, 300);
}

// ✅ CORRECT - AbortController
let abortController;
function loadData() {
  if (abortController) {
    abortController.abort();
  }
  abortController = new AbortController();

  fetch(url, { signal: abortController.signal })
    .then((response) => response.json())
    .then((data) => processData(data));
}
```

### **DOM Manipulation**

```javascript
// ✅ CORRECT - Fragment pour insertions multiples
const fragment = document.createDocumentFragment();
items.forEach((item) => {
  const el = createCard(item);
  fragment.appendChild(el);
});
container.appendChild(fragment);

// ❌ INCORRECT - Insertions multiples directes
items.forEach((item) => {
  const el = createCard(item);
  container.appendChild(el); // Reflow à chaque fois
});
```

### **CSS Sélecteurs**

```css
/* ✅ CORRECT - Spécificité optimale */
.service-card {
}
.service-card-header {
}
.service-card-title {
}

/* ❌ INCORRECT - Trop spécifique */
div.services-grid div.service-card div.card-header h3.title {
}

/* ❌ INCORRECT - Sélecteur universel */
* {
  margin: 0;
} /* Lent sur gros DOM */
```

---

## 🧪 TESTS & VALIDATION

### **Console Logs**

```javascript
// ✅ DÉVELOPPEMENT
console.log("🚀 Initialized");
console.log("📊 Data:", data);
console.error("❌ Error:", error);
console.warn("⚠️ Warning:", warning);

// ❌ PRODUCTION
// Supprimer tous les console.log() avant commit production
```

### **Validation Avant Commit**

```bash
# Checklist
✅ Code commenté (JSDoc/PHPDoc)
✅ Testé desktop (Chrome, Firefox)
✅ Testé mobile (responsive)
✅ Aucune erreur console
✅ CHANGELOG.md mis à jour
✅ Pas de console.log() en production
✅ Code formaté et indenté
✅ Noms de variables descriptifs
```

---

## 📁 ORGANISATION FICHIERS

### **Structure Standard**

```
module/
├── index.php              # Point d'entrée
├── README.md              # Documentation principale
├── CHANGELOG.md           # Historique versions
├── QUICK_START.md         # Guide démarrage rapide
├── css/                   # Styles organisés
│   ├── main.css          # Styles principaux
│   ├── mobile.css        # Responsive mobile
│   └── components.css    # Composants
├── js/                    # Scripts organisés
│   ├── manager.js        # Gestionnaire principal
│   ├── components.js     # Composants
│   └── utils.js          # Utilitaires
└── archive/               # Anciennes versions
    └── README.md         # Documentation archives
```

---

## 🎯 RÈGLES D'OR

### **✅ À FAIRE**

1. **Commenter** toutes les fonctions avec JSDoc/PHPDoc
2. **Nommer** de façon descriptive (pas de `temp`, `data`, `x`)
3. **Tester** en mobile ET desktop
4. **Documenter** dans README.md et CHANGELOG.md
5. **Séparer** logique, présentation et données
6. **Valider** les entrées utilisateur
7. **Gérer** les erreurs avec try/catch
8. **Optimiser** les performances (debounce, fragment, etc.)

### **❌ À ÉVITER**

1. **NE PAS** utiliser var (utiliser let/const)
2. **NE PAS** mélanger PHP et HTML sans structure
3. **NE PAS** oublier les points-virgules en JavaScript
4. **NE PAS** utiliser innerHTML avec données utilisateur
5. **NE PAS** faire des requêtes SQL directes (utiliser PDO)
6. **NE PAS** commit avec console.log() en production
7. **NE PAS** dupliquer le code (DRY principle)
8. **NE PAS** utiliser !important sauf absolument nécessaire

---

## 📚 RESSOURCES

### **Guides de Style Officiels**

- [Airbnb JavaScript Style Guide](https://github.com/airbnb/javascript)
- [Google JavaScript Style Guide](https://google.github.io/styleguide/jsguide.html)
- [PSR-12 PHP Standard](https://www.php-fig.org/psr/psr-12/)

### **Documentation Projet**

- `README.md` - Vue d'ensemble du module
- `CHANGELOG.md` - Historique des versions
- `QUICK_START.md` - Guide démarrage rapide
- `DOCS_DEV_TO_PROD/` - Documentation complète

---

**🔑 RÈGLE D'OR :** La cohérence est plus importante que la perfection. Suivez les conventions existantes !

---

**Dernière mise à jour :** 14 Octobre 2025  
**Version :** 3.0  
**Mainteneur :** Équipe SMM Mastery
