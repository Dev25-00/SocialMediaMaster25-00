---
applyTo: "**"
---

# 🤖 INSTRUCTIONS GITHUB COPILOT - PROJET SMM Mastery

## 📋 CONTEXTE PROJET

**Projet :** SMM Mastery - Plateforme de services de médias sociaux  
**Stack :** PHP 8+, MySQL, JavaScript, CSS, HTML  
**Intégrations :** PayPal, Stripe, API SMMFollows  
**Environnement :** WAMP64 (Windows)

## 📁 STRUCTURE DOCUMENTATION OBLIGATOIRE

**Racine documentation :** `D:\wamp64\www\smm\DOCS_DEV_TO_PROD\`

### 🎯 NAVIGATION RAPIDE COPILOT

**AVANT DÉVELOPPEMENT :**

```
// Consulter état actuel
// Fichier: 01_PROJECT_MANAGEMENT\progress\PROGRESS_UPDATED.md
// Plan: 01_PROJECT_MANAGEMENT\planning\ACTION_PLAN.md
```

**GUIDES PAR DOMAINE :**

```
// Installation: 04_DEVELOPMENT_GUIDES\installation\
// Paiements: 04_DEVELOPMENT_GUIDES\payment\
// Tests: 04_DEVELOPMENT_GUIDES\testing\
// Module Services: 04_DEVELOPMENT_GUIDES\services_module\
// CSS: 05_FIXES_PATCHES\css\
// Liens: 05_FIXES_PATCHES\links\
// BDD: 02_DATABASE\
```

> 🔗 Les évolutions du module services (grille, filtres, modal, validations) sont centralisées dans `services_module`.

## 💻 RÈGLES DE DÉVELOPPEMENT COPILOT

### 🔤 CONVENTIONS DE CODE

#### **PHP (Fichiers principaux)**

```php
<?php
/**
 * SMM Mastery - [Description du fichier]
 * Date: 12 Octobre 2025
 * Version: 1.0
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\
 */

// Structure documentée dans: [chemin vers guide approprié]
```

#### **CSS (Corrections responsive)**

```css
/* SMM Mastery - [Composant/Page]
 * Correction: [Type de correction]
 * Référence: 05_FIXES_PATCHES\css\
 * Responsive: Mobile-first
 */
```

#### **JavaScript (Fonctionnalités)**

```javascript
/**
 * SMM Mastery - [Fonctionnalité]
 * Guide: 04_DEVELOPMENT_GUIDES\[domaine]\
 * Compatibilité: ES6+, Mobile
 */
```

### 📝 DOCUMENTATION AUTOMATIQUE

#### **Pour chaque nouvelle fonction PHP :**

```php
/**
 * [Description de la fonction]
 *
 * @param type $param Description
 * @return type Description
 * @throws Exception Si erreur
 *
 * @documentation 04_DEVELOPMENT_GUIDES\[domaine]\
 * @version 1.0
 * @date 2025-10-12
 */
```

#### **Pour chaque nouvelle route/page :**

```php
/**
 * PAGE: [Nom de la page]
 * URL: [URL d'accès]
 *
 * FONCTIONNALITÉS:
 * - [Liste des fonctionnalités]
 *
 * DÉPENDANCES:
 * - [Fichiers requis]
 *
 * DOCUMENTATION: [Chemin vers guide]
 */
```

## 🛠️ TEMPLATES DE CODE COPILOT

### 🔐 **AUTHENTIFICATION**

```php
// Template auth basé sur: 04_DEVELOPMENT_GUIDES\
// Pattern utilisé dans le projet SMM Mastery
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
```

### 💾 **CONNEXION BASE DE DONNÉES**

```php
// Connexion standard SMM Mastery
// Config: config.php (racine projet)
require_once __DIR__ . '/../config.php';
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log("Erreur BDD: " . $e->getMessage());
    die("Erreur de connexion");
}
```

### 🎨 **STRUCTURE HTML/CSS RESPONSIVE**

```html
<!-- SMM Mastery - Template page responsive -->
<!-- CSS: assets/css/main.css + assets/css/fixes.css -->
<!-- JS: assets/js/mobile-menu.js (obligatoire) -->
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CSS fixes obligatoires -->
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/fixes.css" />
  </head>
</html>
```

### 💳 **INTÉGRATION PAIEMENT**

```php
// Template PayPal/Stripe SMM Mastery
// Guide: 04_DEVELOPMENT_GUIDES\payment\
// Environnement: Sandbox pour dev, Live pour prod
```

## 🚨 RÈGLES STRICTES COPILOT

### ✅ **OBLIGATOIRE**

1. **Respecter l'architecture existante** du projet SMM Mastery
2. **Utiliser les classes CSS existantes** (main.css, fixes.css)
3. **Inclure mobile-menu.js** sur toutes les pages
4. **Suivre les patterns de sécurité** établis
5. **Documenter chaque nouvelle fonctionnalité**

### ❌ **INTERDIT**

1. **Créer de nouveaux frameworks** ou structures
2. **Ignorer le responsive** mobile-first
3. **Oublier la sécurité** (sanitisation, CSRF, etc.)
4. **Casser la structure** de navigation existante
5. **Modifier sans documenter** les changements

## 🔄 WORKFLOW COPILOT

### **POUR NOUVELLE FONCTIONNALITÉ :**

```
1. Analyser guides existants → 04_DEVELOPMENT_GUIDES\
2. Suivre patterns établis → Examiner code similaire
3. Implémenter avec documentation → Headers + commentaires
4. Tester responsive → Mobile + Desktop
5. Documenter changements → Mettre à jour progression
```

### **POUR CORRECTION BUG :**

```
1. Consulter corrections similaires → 05_FIXES_PATCHES\
2. Identifier cause racine → Debug + logs
3. Appliquer correction propre → Sans casser existant
4. Tester sur plusieurs environnements → WAMP + mobile
5. Documenter correction → Guide spécifique
```

## 📱 RESPONSIVE MOBILE-FIRST

### **CSS Pattern obligatoire :**

```css
/* Mobile par défaut */
.element {
  /* Styles mobile */
}

/* Tablet et plus */
@media (min-width: 768px) {
  .element {
    /* Styles tablet */
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .element {
    /* Styles desktop */
  }
}
```

## 🔐 SÉCURITÉ PATTERNS

### **Sanitisation input :**

```php
// Pattern SMM Mastery pour tous les inputs
$input = filter_var($_POST['input'], FILTER_SANITIZE_STRING);
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
```

### **Requêtes préparées :**

```php
// Pattern standard SMM Mastery
$stmt = $pdo->prepare("SELECT * FROM table WHERE id = ?");
$stmt->execute([$id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
```

## 📊 MISE À JOUR PROGRESSION

### **Format commentaire pour changements :**

```php
/**
 * CHANGEMENT: [Date] - [Description]
 * FICHIERS MODIFIÉS: [Liste]
 * TESTS: [Tests effectués]
 * DOCUMENTATION: [Chemin mis à jour]
 */
```

---

## 🎯 RÉSUMÉ POUR COPILOT

**Projet :** SMM Mastery (PHP/MySQL + PayPal/Stripe)  
**Docs :** `DOCS_DEV_TO_PROD\` - Toujours consulter avant code  
**CSS :** Mobile-first + fixes.css obligatoire  
**JS :** mobile-menu.js sur toutes les pages  
**Sécurité :** Sanitisation + requêtes préparées toujours  
**Documentation :** Header + commentaires + progression mise à jour

**🔑 RÈGLE D'OR :** Maintenir la cohérence avec l'existant et documenter tous les changements !
