# 🛠️ INSTRUCTIONS COPILOT - SYSTÈME MULTI-LANGUE SMM Mastery

**Version :** 2.0 - Enrichie Multi-langue  
**Date :** 14 Octobre 2025  
**Pour :** GitHub Copilot

---

## 📋 CONTEXTE PROJET

**SMM Mastery** = Plateforme PHP de services médias sociaux avec système multi-langue complet.

### **Fichier Widget Principal**

```
includes/google-translate-widget.php
```

---

## 🎯 SNIPPETS ESSENTIELS

### **Snippet 1 : Inclure Widget dans Header**

**Trigger :** `smm-multilang`

```php
<!-- Widget Multi-langue -->
<?php include __DIR__ . '/../includes/google-translate-widget.php'; ?>
```

**Usage :** Dans navigation header, avant boutons CTA

---

### **Snippet 2 : Nouvelle Page avec Multi-langue**

**Trigger :** `smm-page-public`

```php
<?php
require_once 'config.php';
require_once 'functions.php';

$page_title = "Titre Page";
$page_description = "Description";
?>
<?php include 'includes/public-header.php'; ?>
<!-- Widget déjà inclus dans header -->

<main class="page-content">
    <h1>Titre</h1>
    <p>Contenu traduis

ible automatiquement</p>
</main>

<?php include 'includes/public-footer.php'; ?>
```

---

### **Snippet 3 : Nouvelle Page Dashboard**

**Trigger :** `smm-page-dashboard`

```php
<?php
require_once '../config.php';
require_once '../functions.php';
checkAuth(); // Vérifier authentification

$page_title_bar = "Titre Page";
?>
<?php include '../includes/dashboard-header-simple.php'; ?>
<!-- Widget déjà inclus dans dashboard-top-bar.php -->

<div class="dashboard-content">
    <h2>Titre Dashboard</h2>
    <!-- Contenu -->
</div>

<?php include '../includes/dashboard-footer-simple.php'; ?>
```

---

### **Snippet 4 : Styles Personnalisés Widget**

**Trigger :** `smm-translate-css`

```css
/* Personnalisation widget multi-langue */
.smm-translate-wrapper {
  margin-left: auto; /* Aligner droite */
}

/* Adapter couleurs */
.smm-translate-btn {
  background: linear-gradient(
    135deg,
    #custom-color 0%,
    #custom-color2 100%
  ) !important;
}

/* Responsive custom */
@media (max-width: 768px) {
  .smm-translate-btn {
    padding: 6px 12px !important;
  }
}
```

---

### **Snippet 5 : Debug Console Traduction**

**Trigger :** `smm-translate-debug`

```javascript
// Debug système traduction
console.log("=== DEBUG TRADUCTION ===");
console.log("Widget existe:", typeof window.smmToggleDropdown === "function");
console.log("Google Translate:", typeof google !== "undefined");

const select = document.querySelector(".goog-te-combo");
console.log("Langues disponibles:", select ? select.options.length : 0);
console.log("Langue actuelle:", localStorage.getItem("smm_preferred_language"));
```

---

### **Snippet 6 : Forcer Langue Programmatically**

**Trigger :** `smm-force-lang`

```javascript
// Forcer changement langue
function forceLanguage(langCode) {
  if (typeof window.smmChangeLanguage === "function") {
    window.smmChangeLanguage(langCode, langCode.toUpperCase());
  } else {
    console.error("Widget multi-langue non chargé");
  }
}

// Usage
forceLanguage("en"); // Anglais
forceLanguage("es"); // Espagnol
```

---

### **Snippet 7 : Ajouter Nouvelle Langue**

**Trigger :** `smm-add-lang`

```javascript
// Dans google-translate-widget.php, array languages:
{ code: 'xx', name: 'Nom Langue', flag: '🏳️', popular: false },

// Exemples:
{ code: 'nl', name: 'Nederlands', flag: '🇳🇱', popular: false },
{ code: 'sv', name: 'Svenska', flag: '🇸🇪', popular: false },
{ code: 'no', name: 'Norsk', flag: '🇳🇴', popular: false },
```

---

## 🔧 CONVENTIONS COPILOT

### **Nommage Fichiers**

```
✅ CORRECT:
- my-new-page.php
- custom-translate-styles.css
- multilang-helper.js

❌ INCORRECT:
- MyNewPage.php (camelCase)
- custom_styles.css (mixte)
```

### **Commentaires Code**

```php
<?php
/**
 * Description fichier
 *
 * @package SMM_Mastery
 * @version 1.0
 * @multilang Compatible - Widget inclus dans header
 */
```

### **Structure Fonction**

```php
/**
 * Nom fonction
 *
 * @param type $param Description
 * @return type Description
 * @multilang-aware Si fonction impacte multi-langue
 */
function myFunction($param) {
    // Code
}
```

---

## 🚫 ANTI-PATTERNS À ÉVITER

### **1. Dupliquer Widget**

```php
<!-- ❌ MAUVAIS -->
<?php include 'includes/public-header.php'; ?>
<?php include 'includes/google-translate-widget.php'; ?> <!-- Déjà dans header -->

<!-- ✅ BON -->
<?php include 'includes/public-header.php'; ?>
<!-- Widget déjà inclus -->
```

### **2. Écraser Styles Critiques**

```css
/* ❌ MAUVAIS - Casse position dropdown */
.smm-translate-dropdown {
  position: relative !important;
}

/* ✅ BON - Ajoute sans casser */
.smm-translate-wrapper {
  margin-left: 20px;
}
```

### **3. Version Debug en Production**

```php
<!-- ❌ JAMAIS -->
<?php include 'includes/google-translate-widget-debug.php'; ?>

<!-- ✅ PRODUCTION -->
<?php include 'includes/google-translate-widget.php'; ?>
```

---

## 📚 RÉFÉRENCES RAPIDES

### **Fichiers Clés**

```
includes/google-translate-widget.php          ← Widget principal
includes/public-header.php                    ← Header public
includes/dashboard-top-bar.php                ← Header dashboard
```

### **Documentation**

```
DOCS_DEV_TO_PROD/
├── PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md  ← Doc technique
├── PHASE14_VALIDATION_TRADUCTION.md          ← Tests
├── CLAUDE_INSTRUCTIONS_MULTILANG.md          ← Instructions Claude
└── COPILOT_INSTRUCTIONS_MULTILANG.md         ← Ce fichier
```

### **Tests Console**

```javascript
// Vérifier widget
typeof window.smmToggleDropdown; // "function"

// Lister langues
document.querySelector(".goog-te-combo").options.length; // 38+

// Langue actuelle
localStorage.getItem("smm_preferred_language"); // 'en', 'fr', etc.
```

---

## 🎯 CHECKLIST AVANT COMMIT

```
CODE
[ ] Widget inclus si nouveau header
[ ] Pas de duplication widget
[ ] Styles ne cassent pas widget
[ ] Version debug non utilisée
[ ] Code commenté si complexe

TESTS
[ ] Dropdown s'ouvre
[ ] Traduction fonctionne (min 2 langues)
[ ] Responsive OK (mobile + desktop)
[ ] Aucune erreur console

DOCUMENTATION
[ ] Changements documentés si widget modifié
[ ] Version incrémentée si modification widget
```

---

## 💡 SUGGESTIONS INTELLIGENTES

### **Auto-Complétion Recommandée**

Quand Copilot détecte :

```php
<?php include 'includes/public-header.php'; ?>
```

**Suggérer commentaire :**

```php
<?php include 'includes/public-header.php'; ?>
<!-- Widget multi-langue déjà inclus dans header -->
```

### **Quand Voir ".smm-translate-"**

**Suggérer :**

```css
/* ⚠️ Styles widget multi-langue - Éviter !important sauf nécessaire */
```

---

## 🔄 WORKFLOW TYPIQUE

```
1. Créer nouvelle page
2. Inclure header (widget auto-inclus)
3. Ajouter contenu
4. Tester traduction fonctionne
5. Commit avec message clair
```

**Exemple commit :**

```
feat: Ajout page pricing avec support multi-langue

- Nouvelle page pages/pricing.php
- Widget multi-langue inclus via header
- Testé traduction EN/ES/AR
- Responsive validé
```

---

## 🎓 FORMATION COPILOT

### **Patterns À Reconnaître**

**Pattern 1 : Nouveau Fichier Page**

```php
<?php
require_once
```

→ Suggérer template complet avec header

**Pattern 2 : Modification Widget**

```php
includes/google-translate-widget.php
```

→ Avertir : Fichier sensible, backup recommandé

**Pattern 3 : Styles `.smm-translate-`**

```css
.smm-translate-
```

→ Suggérer précautions, éviter !important

---

## ✅ VALIDATION COPILOT

Copilot doit suggérer correctement pour :

```
[ ] Inclusion widget dans nouveau header
[ ] Template page publique complet
[ ] Template page dashboard complet
[ ] Styles personnalisés sans casser widget
[ ] Debug console traduction
[ ] Ajout nouvelle langue
[ ] Commentaires appropriés
[ ] Message commit clair
```

---

## 📖 LIENS UTILES

**Documentation Projet :**

- `README.md` - Vue d'ensemble
- `SESSION_INDEX.md` - Point d'entrée docs
- `PROGRESS_UPDATED.md` - État projet

**Multi-langue :**

- `PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md` - Détails complets
- `PHASE14_VALIDATION_TRADUCTION.md` - Tests

**Google Translate :**

- Codes langues : https://cloud.google.com/translate/docs/languages
- API Docs : https://cloud.google.com/translate/docs

---

**🛠️ Version :** 2.0 - Instructions Enrichies Multi-langue  
**📅 Date :** 14 Octobre 2025  
**✅ Statut :** Instructions Copilot Production Ready

**🚀 Copilot est maintenant formé au système multi-langue SMM Mastery !**
