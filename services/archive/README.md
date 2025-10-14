# 📦 ARCHIVE - Services (Anciens Fichiers)

**Date d'archivage :** 14 Octobre 2025  
**Raison :** Réorganisation du dossier services avec renommage et commentaires

---

## 📋 FICHIERS ARCHIVÉS

### 🎨 **CSS - Anciennes Versions**

- `mobile-filters-fix.css` - Version 1.0 du responsive mobile
- `mobile-filters-fix-v2.css` - Version 2.0 (3 lignes de filtres)
- `filters-multiline.css` - Anciennes règles de filtres multi-lignes

**➡️ Remplacés par :**

- `css/filters.css` (fusion de filters-2lines.css)
- `css/mobile-filters.css` (mobile-filters-fix-v3.css renommé)

---

### ⚙️ **JavaScript - Anciennes Versions**

- `services-manager-multiline.js` - Version 2.0 non commentée
- `CARDS_ENHANCEMENT_V2.js` - Version 2.0 des métadonnées cards
- `order-modal.js` - Version d'origine

**➡️ Remplacés par :**

- `js/services-manager.js` (renommé + commenté v3.0)
- `js/cards-enhancement.js` (renommé + commenté v3.0)
- `js/order-modal.js` (commenté v3.0)

---

## 🔄 CHANGEMENTS PRINCIPAUX

### **Structure Réorganisée**

```
AVANT:
services/
├── index.php
├── filters-2lines.css
├── mobile-filters-fix-v3.css
├── order-modal.css
├── services-manager-multiline.js
├── CARDS_ENHANCEMENT_V2.js
├── order-modal.js
└── [autres versions obsolètes]

APRÈS:
services/
├── index.php (mis à jour avec nouveaux chemins)
├── css/
│   ├── filters.css
│   ├── mobile-filters.css
│   └── order-modal.css
├── js/
│   ├── services-manager.js (v3.0 commenté)
│   ├── cards-enhancement.js (v3.0 commenté)
│   └── order-modal.js (v3.0 commenté)
└── archive/ (ce dossier)
```

### **Améliorations Apportées**

1. ✅ **Headers PHP standards** avec documentation complète
2. ✅ **Commentaires JSDoc** pour toutes les fonctions JavaScript
3. ✅ **Structure claire** avec sous-dossiers css/ et js/
4. ✅ **Nomenclature cohérente** (services-manager au lieu de services-manager-multiline)
5. ✅ **Versioning clair** (v3.0 au lieu de V2, multiline, etc.)
6. ✅ **Documentation inline** expliquant chaque fonctionnalité

---

## 🚫 QUAND UTILISER CES FICHIERS ARCHIVÉS ?

**NE PAS UTILISER** sauf pour :

- Comparaison de code en cas de régression
- Récupération d'une fonctionnalité spécifique
- Référence historique

**⚠️ Les fichiers actifs sont dans `css/` et `js/`**

---

## 📚 DOCUMENTATION COMPLÈTE

Pour plus d'informations sur l'architecture actuelle :

- 📁 `DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\`
- 📁 `DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\installation\`
- 📁 `DOCS_DEV_TO_PROD\05_FIXES_PATCHES\`
- 📄 `DOCS_DEV_TO_PROD\COPILOT_GUIDE_PRATIQUE.md`

---

## 🔗 COMPATIBILITÉ

Les anciens liens dans `index.php` ont été mis à jour :

```php
// ANCIEN
<link rel="stylesheet" href="filters-2lines.css">
<link rel="stylesheet" href="mobile-filters-fix-v3.css">
<script src="services-manager-multiline.js"></script>

// NOUVEAU
<link rel="stylesheet" href="css/filters.css">
<link rel="stylesheet" href="css/mobile-filters.css">
<script src="js/services-manager.js"></script>
```

---

**🎯 RÈGLE D'OR :** Ces fichiers sont conservés pour historique uniquement. Utiliser les versions dans `css/` et `js/` pour toute modification future.
