# 🔧 CORRECTIONS SPÉCIFIQUES WIDGET TRADUCTION

**Date :** 14 Octobre 2025  
**Fichier :** `includes/widgets/google-translate-widget-v3-final.php`  
**Problèmes identifiés et solutions**

## 🚨 PROBLÈMES DÉTECTÉS

### 1. 🌐 Dropdown State Non Synchronisé

**Ligne ~540 :** La fonction `updateBadge()` met seulement à jour le badge mais pas la sélection visuelle du dropdown

### 2. 🔄 Animation Globe Bizarre

**Ligne ~71 :** Animation `rotateY` au lieu de `rotate` normal pour effet planète

### 3. 🐛 État Dropdown Après Traduction

**Problème :** Pas de mise à jour visuelle de l'élément sélectionné dans la liste

## ✅ CORRECTIONS À APPLIQUER

### 1. 📝 Corriger Animation Globe

```css
/* AVANT - Animation bizarre */
.smm-translate-icon {
  animation: rotateGlobe 10s linear infinite;
}
@keyframes rotateGlobe {
  0% {
    transform: rotateY(0deg);
  }
  100% {
    transform: rotateY(360deg);
  }
}

/* APRÈS - Animation naturelle planète */
.smm-translate-icon {
  animation: rotateGlobe 8s linear infinite;
  transform-style: preserve-3d;
}
@keyframes rotateGlobe {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Animation hover plus douce */
.smm-translate-btn:hover .smm-translate-icon {
  animation-duration: 4s;
}
```

### 2. 🔄 Améliorer Fonction updateBadge

```javascript
// AVANT - Mise à jour partielle
function updateBadge(langCode) {
  const badge = document.getElementById("smmCurrentLang");
  if (badge) {
    badge.textContent = langCode.toUpperCase().substring(0, 3);
  }
}

// APRÈS - Mise à jour complète
function updateBadge(langCode) {
  // Mettre à jour le badge
  const badge = document.getElementById("smmCurrentLang");
  if (badge) {
    badge.textContent = langCode.toUpperCase().substring(0, 3);
  }

  // Mettre à jour l'état visuel du dropdown
  updateDropdownSelection(langCode);

  // Mettre à jour le libellé du bouton principal
  updateButtonLabel(langCode);
}

function updateDropdownSelection(langCode) {
  // Retirer la classe 'selected' de tous les éléments
  const allItems = document.querySelectorAll(".smm-translate-option");
  allItems.forEach((item) => {
    item.classList.remove("selected");
  });

  // Ajouter la classe 'selected' à l'élément actuel
  const currentItem = document.querySelector(`[data-lang="${langCode}"]`);
  if (currentItem) {
    currentItem.classList.add("selected");
  }
}

function updateButtonLabel(langCode) {
  const languages = {
    fr: "🇫🇷 Français",
    en: "🇺🇸 English",
    es: "🇪🇸 Español",
    de: "🇩🇪 Deutsch",
    it: "🇮🇹 Italiano",
    ar: "🇸🇦 العربية",
    // Ajouter d'autres langues selon besoins
  };

  const buttonText = document.querySelector(".smm-translate-current");
  if (buttonText && languages[langCode]) {
    buttonText.textContent = languages[langCode];
  }
}
```

### 3. 🎨 Ajouter Styles Selection Dropdown

```css
/* États visuels pour dropdown */
.smm-translate-option {
  padding: 12px 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  border-left: 3px solid transparent;
}

.smm-translate-option:hover {
  background: rgba(37, 99, 235, 0.1);
  border-left-color: #2563eb;
}

.smm-translate-option.selected {
  background: rgba(37, 99, 235, 0.15);
  border-left-color: #2563eb;
  font-weight: 600;
  color: #2563eb;
}

.smm-translate-option.selected::after {
  content: "✓";
  float: right;
  color: #2563eb;
  font-weight: bold;
}
```

### 4. 💾 Persistance État Selection

```javascript
// Fonction d'initialisation améliorée
function initializeLanguageWidget() {
  // Détecter langue sauvegardée
  const savedLang =
    localStorage.getItem("smm_preferred_language") ||
    getCookie("smm_language") ||
    "fr";

  // Initialiser l'affichage
  currentLang = savedLang;
  updateBadge(savedLang);

  console.log(`[SMM Translate] 🌐 Langue initialisée: ${savedLang}`);
}

// Utilitaire lecture cookie
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
  return null;
}
```

## 🎯 SKELETON SYSTEM - STRUCTURE PROPOSÉE

### 📁 Nouveau Dossier Structure

```
includes/
├── skeletons/
│   ├── skeleton-detector.php     # Détection type page
│   ├── skeleton-admin.php        # Template admin
│   ├── skeleton-dashboard.php    # Template client
│   ├── skeleton-landing.php      # Template public
│   └── skeleton-manager.php      # Gestionnaire skeletons
├── layout/
│   ├── dashboard-header-simple.php (existant)
│   └── dashboard-footer-simple.php (existant)
└── widgets/
    └── google-translate-widget-v3-final.php (à corriger)
```

### 🔧 Skeleton Detector

```php
<?php
// includes/skeletons/skeleton-detector.php
function detectPageType() {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);

    // Admin detection
    if (strpos($path, '/admin/') !== false) {
        return 'admin';
    }

    // Dashboard detection
    if (strpos($path, '/dashboard/') !== false ||
        strpos($path, '/orders/') !== false ||
        strpos($path, '/support/') !== false ||
        strpos($path, '/services/') !== false) {
        return 'dashboard';
    }

    // Landing pages
    return 'landing';
}

function shouldShowSkeleton() {
    // Afficher skeleton si traduction demandée
    return isset($_GET['lang']) ||
           isset($_GET['translate']) ||
           (isset($_COOKIE['smm_language']) && $_COOKIE['smm_language'] !== 'fr');
}
?>
```

### ⚡ Smart Loading Integration

```javascript
// Smart skeleton loader
class SkeletonLoader {
  constructor() {
    this.pageType = this.detectPageType();
    this.isTranslating = false;
  }

  detectPageType() {
    const path = window.location.pathname;
    if (path.includes("/admin/")) return "admin";
    if (
      path.includes("/dashboard/") ||
      path.includes("/orders/") ||
      path.includes("/support/") ||
      path.includes("/services/")
    )
      return "dashboard";
    return "landing";
  }

  showSkeleton() {
    if (this.isTranslating) return;

    const skeleton = this.createSkeleton(this.pageType);
    document.body.innerHTML = skeleton;
    this.isTranslating = true;

    console.log(`[Skeleton] 💀 Affichage skeleton: ${this.pageType}`);
  }

  createSkeleton(type) {
    const skeletons = {
      admin: this.getAdminSkeleton(),
      dashboard: this.getDashboardSkeleton(),
      landing: this.getLandingSkeleton(),
    };

    return skeletons[type] || skeletons.landing;
  }

  getDashboardSkeleton() {
    return `
            <div class="skeleton-container">
                <div class="skeleton-topbar">
                    <div class="skeleton-logo"></div>
                    <div class="skeleton-balance"></div>
                    <div class="skeleton-user"></div>
                </div>
                <div class="skeleton-sidebar">
                    <div class="skeleton-nav-item"></div>
                    <div class="skeleton-nav-item"></div>
                    <div class="skeleton-nav-item"></div>
                    <div class="skeleton-nav-item"></div>
                </div>
                <div class="skeleton-content">
                    <div class="skeleton-header"></div>
                    <div class="skeleton-stats">
                        <div class="skeleton-stat"></div>
                        <div class="skeleton-stat"></div>
                        <div class="skeleton-stat"></div>
                    </div>
                    <div class="skeleton-main-content"></div>
                </div>
            </div>
        `;
  }

  // ... autres méthodes skeleton
}

// Integration avec widget traduction
window.skeletonLoader = new SkeletonLoader();

// Hook dans la fonction de changement de langue
function smmChangeLanguage(langCode, langName) {
  // Afficher skeleton immédiatement
  window.skeletonLoader.showSkeleton();

  // Puis continuer traduction normale
  // ... reste du code existant
}
```

## 📊 CSS SKELETON STYLES

```css
/* Skeleton Loading Styles */
.skeleton-container {
  width: 100%;
  height: 100vh;
  background: #f8fafc;
  overflow: hidden;
}

.skeleton-topbar {
  height: 60px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-bottom: 1px solid #e5e7eb;
}

.skeleton-logo,
.skeleton-balance,
.skeleton-user {
  height: 32px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 4px;
}

.skeleton-logo {
  width: 120px;
}
.skeleton-balance {
  width: 80px;
}
.skeleton-user {
  width: 40px;
  border-radius: 50%;
}

.skeleton-sidebar {
  position: fixed;
  left: 0;
  top: 60px;
  width: 250px;
  height: calc(100vh - 60px);
  background: #1f2937;
  padding: 20px;
}

.skeleton-nav-item {
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  margin-bottom: 10px;
  border-radius: 6px;
  animation: skeleton-loading 1.5s infinite;
}

.skeleton-content {
  margin-left: 250px;
  padding: 20px;
}

.skeleton-header {
  height: 60px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 8px;
  margin-bottom: 20px;
}

.skeleton-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.skeleton-stat {
  height: 100px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 8px;
}

@keyframes skeleton-loading {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Responsive */
@media (max-width: 768px) {
  .skeleton-sidebar {
    transform: translateX(-100%);
  }
  .skeleton-content {
    margin-left: 0;
  }
  .skeleton-stats {
    grid-template-columns: 1fr;
  }
}
```

---

## 🚀 PRIORITÉS IMPLEMENTATION

### ⚡ IMMÉDIAT (1-2h)

1. Corriger animation globe (rotateY → rotate)
2. Corriger fonction updateBadge avec état dropdown
3. Ajouter styles .selected pour dropdown

### 🔥 URGENT (4-6h)

4. Créer système skeleton de base
5. Intégrer skeleton avec traduction
6. Tests sur 3 types de pages

### 💡 OPTIMISATION (1-2 jours)

7. Animations smooth skeleton → contenu
8. Performance optimizations
9. Tests cross-browser

**Voulez-vous commencer par les corrections immédiates du widget ?** 🚀
