# 📁 INCLUDES - STRUCTURE ORGANISÉE

**Date mise à jour :** 14 Octobre 2025  
**Version :** 2.0 - Architecture Professionnelle  
**Projet :** SMM Mastery

---

## 📊 VUE D'ENSEMBLE

Ce dossier contient **tous les composants réutilisables** du projet SMM Mastery, organisés par **catégorie fonctionnelle** pour une maintenance optimale.

### 🎯 **Principes d'organisation**

1. **Séparation des responsabilités** - Chaque sous-dossier a un rôle spécifique
2. **Chemins explicites** - Noms de dossiers clairs et auto-documentés
3. **Archives isolées** - Les anciens fichiers sont préservés mais séparés
4. **Documentation intégrée** - README dans chaque dossier critique

---

## 📂 STRUCTURE COMPLÈTE

```
includes/
│
├── 🎨 widgets/                 # Widgets interactifs (traduction, etc.)
│   ├── google-translate-widget-v3-final.php    [MASTER VERSION]
│   └── google-translate-widget.php              [Production copy]
│
├── 📧 email/                   # Gestion des emails
│   └── EmailManager.php
│
├── 🖼️ layout/                  # En-têtes, pieds de page, barres latérales
│   ├── dashboard-header-simple.php
│   ├── dashboard-footer-simple.php
│   ├── dashboard-sidebar.php
│   ├── dashboard-top-bar.php
│   ├── page-header.php
│   ├── public-header.php
│   └── public-footer.php
│
├── ⚙️ config/                  # Fichiers de configuration
│   ├── icons-config.php
│   └── php-translation-system.php
│
├── 📚 docs/                    # Documentation des includes
│   └── README_WIDGET_TRADUCTION.md
│
├── 🗄️ _archives/               # Archives (anciens fichiers)
│   └── translate_widgets/      # Anciennes versions widgets traduction
│       ├── google-translate-widget-original.php
│       ├── google-translate-widget-old.php
│       ├── google-translate-widget-backup.php
│       ├── google-translate-widget-backup-20251014.php
│       ├── google-translate-widget-backup-20251014-122601.php
│       ├── google-translate-widget-debug.php
│       ├── google-translate-widget-fixed.php
│       └── google-translate-widget-v3.php
│
└── README.md                   # Ce fichier
```

---

## 🎨 WIDGETS (`widgets/`)

### **Objectif**

Composants JavaScript/PHP interactifs réutilisables

### **Fichiers actifs**

| Fichier                                | Rôle                                        | Status        |
| -------------------------------------- | ------------------------------------------- | ------------- |
| `google-translate-widget-v3-final.php` | **Version MASTER** - Widget traduction v3.0 | ✅ Production |
| `google-translate-widget.php`          | Copie production (backward compatibility)   | ✅ Production |

### **Utilisation**

```php
// Dans n'importe quel fichier du projet
<?php include __DIR__ . '/../includes/widgets/google-translate-widget-v3-final.php'; ?>

// Depuis dashboard/
<?php include __DIR__ . '/../includes/widgets/google-translate-widget-v3-final.php'; ?>

// Depuis racine (index.php)
<?php include __DIR__ . '/includes/widgets/google-translate-widget-v3-final.php'; ?>
```

### **Documentation**

- 📖 Guide complet : `DOCS_DEV_TO_PROD/SOLUTION_FINALE_TRADUCTION_V3.0.md`
- 📖 README technique : `includes/docs/README_WIDGET_TRADUCTION.md`

---

## 📧 EMAIL (`email/`)

### **Objectif**

Gestion centralisée de l'envoi d'emails (commandes, tickets, notifications)

### **Fichiers actifs**

| Fichier            | Rôle                           | Dépendances            |
| ------------------ | ------------------------------ | ---------------------- |
| `EmailManager.php` | Classe complète gestion emails | PHPMailer, config SMTP |

### **Utilisation**

```php
// Inclusion
require_once __DIR__ . '/../includes/email/EmailManager.php';

// Instanciation
$emailManager = new EmailManager($pdo);

// Envoi email commande
$emailManager->sendOrderConfirmation($orderId, $userEmail);

// Envoi email ticket
$emailManager->sendTicketNotification($ticketId, $supportEmail);
```

### **Méthodes disponibles**

- `sendOrderConfirmation($orderId, $userEmail)`
- `sendTicketNotification($ticketId, $email)`
- `sendWelcomeEmail($userId, $userEmail)`
- `sendPasswordReset($email, $token)`

---

## 🖼️ LAYOUT (`layout/`)

### **Objectif**

Composants de mise en page (headers, footers, sidebars) pour pages publiques et dashboard

### **Fichiers actifs**

#### **Dashboard (Zone connectée)**

| Fichier                       | Rôle                                                         | Utilisé par                                                             |
| ----------------------------- | ------------------------------------------------------------ | ----------------------------------------------------------------------- |
| `dashboard-header-simple.php` | Header minimal dashboard                                     | `dashboard/index.php`, `dashboard/profile.php`, `dashboard/balance.php` |
| `dashboard-footer-simple.php` | Footer minimal dashboard                                     | Mêmes pages                                                             |
| `dashboard-top-bar.php`       | Barre supérieure (balance, notifs, widget langue, menu user) | Toutes pages dashboard                                                  |
| `dashboard-sidebar.php`       | Menu latéral dashboard                                       | Pages avec sidebar complète                                             |
| `page-header.php`             | Header pages internes                                        | `services/`, `orders/`, `support/`                                      |

#### **Public (Zone non connectée)**

| Fichier             | Rôle                                    | Utilisé par                 |
| ------------------- | --------------------------------------- | --------------------------- |
| `public-header.php` | Header site public (nav, widget langue) | Toutes pages `/pages/*.php` |
| `public-footer.php` | Footer site public (liens, mentions)    | Toutes pages `/pages/*.php` |

### **Utilisation**

```php
// DASHBOARD
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';
// [Contenu de la page]
<?php require_once __DIR__ . '/../includes/layout/dashboard-footer-simple.php'; ?>

// PUBLIC
<?php include '../includes/layout/public-header.php'; ?>
// [Contenu de la page]
<?php include '../includes/layout/public-footer.php'; ?>

// PAGES INTERNES (services, orders, support)
require_once __DIR__ . '/../includes/layout/page-header.php';
```

### **Architecture HTML**

```html
<!-- DASHBOARD -->
<!DOCTYPE html>
<html>
  <head>
    <!-- CSS, meta -->
  </head>
  <body>
    <?php include 'includes/layout/dashboard-top-bar.php'; ?>

    <div class="dashboard-container">
      <?php include 'includes/layout/dashboard-sidebar.php'; ?>

      <main class="dashboard-main">
        <!-- Contenu spécifique page -->
      </main>
    </div>

    <?php include 'includes/layout/dashboard-footer-simple.php'; ?>
  </body>
</html>

<!-- PUBLIC -->
<!DOCTYPE html>
<html>
  <head>
    <!-- CSS, meta -->
  </head>
  <body>
    <?php include 'includes/layout/public-header.php'; ?>

    <main>
      <!-- Contenu spécifique page -->
    </main>

    <?php include 'includes/layout/public-footer.php'; ?>
  </body>
</html>
```

---

## ⚙️ CONFIG (`config/`)

### **Objectif**

Fichiers de configuration PHP (icônes, traductions, helpers)

### **Fichiers actifs**

| Fichier                      | Rôle                                                 | Chargé par                                |
| ---------------------------- | ---------------------------------------------------- | ----------------------------------------- |
| `icons-config.php`           | Mapping icônes Font Awesome 6 (fonction `getIcon()`) | `functions.php` (auto-chargé globalement) |
| `php-translation-system.php` | Système traduction PHP côté serveur                  | Pages nécessitant traduction statique     |

### **Utilisation**

#### **Icônes (`icons-config.php`)**

```php
// Auto-chargé dans functions.php - Utilisable partout
<?php echo getIcon('user', true, 'lg'); ?>
// Résultat: <i class="fa-solid fa-user fa-lg"></i>

// Paramètres:
// 1. string $icon - Nom simplifié ('user', 'dashboard', 'wallet', etc.)
// 2. bool $withWrapper - true = avec <i>, false = classe seulement
// 3. string $size - 'sm', 'lg', 'xl', '2x', '3x'
```

**Icônes disponibles :**

- `user`, `users`, `dashboard`, `wallet`, `credit-card`
- `services`, `orders`, `settings`, `support`, `ticket`
- `mail`, `bell`, `star`, `check`, `times`, `plus`, `minus`
- `edit`, `trash`, `search`, `filter`, `sort`
- Voir fichier complet pour liste exhaustive

#### **Traductions PHP (`php-translation-system.php`)**

```php
// Inclusion (si besoin traductions côté serveur)
require_once __DIR__ . '/../includes/config/php-translation-system.php';

// Utilisation
echo translate('welcome_message', $_SESSION['lang'] ?? 'fr');
// Note: Widget JavaScript (v3.0) gère la majorité des traductions
```

---

## 📚 DOCS (`docs/`)

### **Objectif**

Documentation technique spécifique aux composants includes/

### **Fichiers**

| Fichier                       | Description                                    |
| ----------------------------- | ---------------------------------------------- |
| `README_WIDGET_TRADUCTION.md` | Guide technique complet widget traduction v3.0 |

**Documentation principale projet :** `DOCS_DEV_TO_PROD/`

---

## 🗄️ ARCHIVES (`_archives/`)

### **Objectif**

Préserver les anciennes versions pour rollback ou référence historique

### **Contenu**

#### **`translate_widgets/` (8 fichiers)**

- `google-translate-widget-original.php` - Version v1.0 initiale
- `google-translate-widget-old.php` - Version v2.0
- `google-translate-widget-fixed.php` - Version v2.1 (70% succès)
- `google-translate-widget-v3.php` - v3.0 incomplète
- `google-translate-widget-backup*.php` - Divers backups datés
- `google-translate-widget-debug.php` - Version debug temporaire

### **⚠️ IMPORTANT**

- ❌ **NE PAS UTILISER** ces fichiers en production
- ✅ **CONSERVER** pour référence/rollback si nécessaire
- 🗑️ **Supprimer après 30 jours** de stabilité v3.0

### **Rollback d'urgence**

```bash
# Si widget v3.0 pose problème (très improbable)
Copy-Item "includes\_archives\translate_widgets\google-translate-widget-fixed.php" `
         "includes\widgets\google-translate-widget.php" -Force

# Note: v2.1-fixed = 70% succès, fallback non-fonctionnel
# Utiliser UNIQUEMENT en dernier recours
```

---

## 🚀 GUIDE D'UTILISATION RAPIDE

### **Pour les développeurs**

#### **1. Ajouter un nouveau composant**

```bash
# Identifier catégorie
# Widget interactif → widgets/
# Email → email/
# Layout → layout/
# Config → config/

# Créer fichier dans bon dossier
New-Item "includes/widgets/mon-nouveau-widget.php"

# Documenter dans README.md (ce fichier)
```

#### **2. Inclure un composant existant**

```php
// Toujours utiliser chemins relatifs depuis __DIR__

// Depuis racine projet (index.php)
include __DIR__ . '/includes/widgets/google-translate-widget-v3-final.php';

// Depuis sous-dossier (dashboard/index.php)
require_once __DIR__ . '/../includes/layout/dashboard-header-simple.php';

// Depuis admin/ ou pages/
include '../includes/layout/public-header.php';
```

#### **3. Modifier un composant**

```bash
# 1. Faire backup si modification majeure
Copy-Item "includes/widgets/mon-widget.php" `
          "includes/_archives/mon-widget-backup-$(Get-Date -Format 'yyyyMMdd').php"

# 2. Modifier le fichier
# [Modifications...]

# 3. Tester sur toutes les pages utilisant le composant
# 4. Documenter changements dans CHANGELOG ou README
```

---

## 📋 CHECKLIST MAINTENANCE

### **Mensuel**

- [ ] Vérifier aucun fichier obsolète à la racine `includes/`
- [ ] Valider tous les chemins d'inclusion fonctionnent
- [ ] Nettoyer archives > 30 jours si stable

### **Avant chaque release**

- [ ] Documenter nouveaux composants dans README
- [ ] Vérifier compatibilité cross-browser pour widgets JS
- [ ] Valider responsive mobile pour layout
- [ ] Tester rollback archives fonctionnels

### **Après incident**

- [ ] Analyser quel composant a causé problème
- [ ] Archiver version problématique avec suffixe `-broken-YYYYMMDD`
- [ ] Documenter incident dans `DOCS_DEV_TO_PROD/CHANGELOG_*.md`

---

## 🔧 DÉPANNAGE

### **Erreur : "Failed to include [fichier]"**

```php
// Vérifier chemin relatif correct
// AVANT (ancien)
include 'includes/google-translate-widget.php'; // ❌

// APRÈS (nouveau)
include __DIR__ . '/includes/widgets/google-translate-widget-v3-final.php'; // ✅
```

### **Widget traduction ne fonctionne pas**

1. Console F12 → Chercher logs `[SMM Translate v3.0]`
2. Vérifier inclusion : `includes/widgets/google-translate-widget-v3-final.php`
3. Consulter : `DOCS_DEV_TO_PROD/SOLUTION_FINALE_TRADUCTION_V3.0.md`

### **Icônes n'apparaissent pas**

```php
// Vérifier icons-config.php chargé
// Doit être dans functions.php ligne ~13:
require_once __DIR__ . '/includes/config/icons-config.php';

// Utiliser fonction globale
<?php echo getIcon('nom-icone', true); ?>
```

### **Layout cassé après déplacement fichier**

```bash
# Chercher toutes les inclusions du fichier
grep -r "includes/mon-fichier.php" d:\wamp64\www\smm\

# Mettre à jour tous les chemins trouvés
# Ancien: includes/mon-fichier.php
# Nouveau: includes/[categorie]/mon-fichier.php
```

---

## 📊 STATISTIQUES

### **Fichiers par catégorie**

| Catégorie | Fichiers actifs | Fichiers archivés | Total  |
| --------- | --------------- | ----------------- | ------ |
| Widgets   | 2               | 8                 | 10     |
| Email     | 1               | 0                 | 1      |
| Layout    | 7               | 0                 | 7      |
| Config    | 2               | 0                 | 2      |
| Docs      | 1               | 0                 | 1      |
| **TOTAL** | **13**          | **8**             | **21** |

### **Inclusions par type**

| Type fichier            | Pages utilisant                                         |
| ----------------------- | ------------------------------------------------------- |
| Widget traduction       | 3 (index.php, dashboard-top-bar.php, public-header.php) |
| Public header/footer    | 7 pages `/pages/*.php`                                  |
| Dashboard header/footer | 3 pages `/dashboard/*.php`                              |
| Page header             | 5+ pages internes                                       |
| Icons config            | Global (chargé par functions.php)                       |

---

## 🔗 LIENS UTILES

### **Documentation projet**

- 📂 Documentation complète : `/DOCS_DEV_TO_PROD/`
- 📖 Guide développeur : `/DOCS_DEV_TO_PROD/04_DEVELOPMENT_GUIDES/`
- 🔧 Correctifs : `/DOCS_DEV_TO_PROD/05_FIXES_PATCHES/`

### **Documentation composants**

- 🌐 Widget traduction : `/DOCS_DEV_TO_PROD/SOLUTION_FINALE_TRADUCTION_V3.0.md`
- 🎨 Icônes : `/DOCS_DEV_TO_PROD/ICONS_CONFIG_CENTRALISE_V1.0.md`
- 📧 Emails : Code source `includes/email/EmailManager.php` (commenté)

### **Instructions GitHub Copilot**

- 📜 Instructions générales : `/.github/instructions/instructions.instructions.md`

---

## 📞 SUPPORT

**En cas de problème avec la structure includes/ :**

1. **Consulter ce README** (vous y êtes !)
2. **Vérifier documentation projet** : `DOCS_DEV_TO_PROD/`
3. **Analyser logs** : Console navigateur (F12) + logs PHP
4. **Rollback si nécessaire** : Utiliser fichiers `_archives/`

---

_Structure mise à jour le 14 Octobre 2025 - SMM Mastery Team_  
**Version README :** 2.0  
**Dernière révision :** Réorganisation complète architecture includes/
