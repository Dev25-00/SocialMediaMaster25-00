# 🔧 SCRIPT DE MISE À JOUR AUTOMATIQUE DES HEADERS/FOOTERS

## Fichiers déjà mis à jour ✅

- [x] dashboard/index.php
- [x] dashboard/profile.php
- [x] orders/new.php

## Fichiers à mettre à jour 📝

### Dashboard Files
- [ ] dashboard/balance.php

### Orders Files
- [ ] orders/history.php
- [ ] orders/tracking.php

### Services Files
- [ ] services/index.php

### Support Files
- [ ] support/tickets.php
- [ ] support/new-ticket.php
- [ ] support/view-ticket.php

### Admin Files
- [ ] admin/dashboard.php
- [ ] admin/users.php
- [ ] admin/orders.php
- [ ] admin/services.php
- [ ] admin/tickets.php
- [ ] admin/settings.php

### Public Pages
- [ ] pages/about.php
- [ ] pages/contact.php
- [ ] pages/faq.php
- [ ] pages/pricing.php
- [ ] pages/terms.php
- [ ] pages/privacy.php
- [ ] pages/refund.php
- [ ] pages/disclaimer.php

---

## Template pour pages DASHBOARD

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);

// Variables pour le header
$page_title = "TITRE DE LA PAGE";
$include_charts = false; // true si besoin de Chart.js

require_once __DIR__ . '/../includes/dashboard-header.php';
?>

<div class="dashboard-content">
    <div class="container">
        
        <!-- En-tête de page -->
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <?php echo getIcon('ICONE_KEY', true, 'xl'); ?>
                    TITRE
                </h1>
                <p class="page-subtitle">Description</p>
            </div>
        </div>

        <!-- VOTRE CONTENU ICI -->
        
    </div>
</div>

<?php require_once __DIR__ . '/../includes/dashboard-footer.php'; ?>
```

---

## Template pour pages PUBLIQUES

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$page_title = "TITRE";
$page_description = "DESCRIPTION";

require_once __DIR__ . '/../includes/public-header.php';
?>

<!-- Page Hero -->
<div class="page-hero">
    <h1>
        <?php echo getIcon('ICONE_KEY', true, 'xl'); ?>
        TITRE
    </h1>
    <p>Description</p>
</div>

<!-- Page Content -->
<div class="page-content">
    <div class="content-section">
        <h2>
            <?php echo getIcon('ICONE_KEY'); ?>
            Section
        </h2>
        <p>Contenu...</p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/public-footer.php'; ?>
```

---

## Icônes disponibles (icons-config.php)

### Tiers
- `budget`, `standard`, `premium`, `ultimate`

### Actions
- `add`, `edit`, `delete`, `view`, `download`, `upload`

### Navigation
- `home`, `dashboard`, `services`, `orders`, `balance`, `support`, `settings`, `logout`

### Statuts
- `success`, `error`, `warning`, `info`, `pending`

### Réseaux sociaux
- `instagram`, `youtube`, `tiktok`, `facebook`, `twitter`, `linkedin`

### Métriques
- `followers`, `likes`, `views`, `comments`, `shares`, `subscribers`

### Finances
- `money`, `card`, `wallet`, `paypal`, `stripe`, `bitcoin`

### Autres
- `chart`, `stats`, `rocket`, `bell`, `mail`, `phone`, `user`, `shield`, `lock`, `search`, `filter`, `calendar`

---

## Fonctions helper importantes

### Icônes
```php
// Icône simple
<?php echo getIcon('rocket'); ?>

// Icône animée
<?php echo getIcon('rocket', true); ?>

// Icône avec taille
<?php echo getIcon('rocket', true, 'xl'); ?> // sm, md, lg, xl

// Icône avec texte
<?php echo iconText('rocket', 'Texte'); ?>

// Badge tier
<?php echo tierBadge('premium'); ?>

// Badge statut
<?php echo statusBadge('completed'); ?>

// Icône plateforme
<?php echo platformIcon('instagram', true); ?>
```

### Styles CSS utiles
```css
/* Container */
.dashboard-content { padding: 30px; }
.container { max-width: 1200px; margin: 0 auto; }

/* En-tête de page */
.page-header { margin-bottom: 30px; }
.page-title { font-size: 32px; font-weight: 700; }
.page-subtitle { color: #6b7280; }

/* Cards */
.card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
.card-body { padding: 30px; }
.card-footer { padding: 20px; border-top: 1px solid #e5e7eb; }

/* Formulaires */
.form-group { margin-bottom: 20px; }
.form-control { width: 100%; padding: 12px; border-radius: 8px; }
.form-hint { font-size: 13px; color: #6b7280; }

/* Boutons */
.btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; }
.btn-primary { background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; }
.btn-secondary { background: #f3f4f6; color: #374151; }
.btn-sm { padding: 6px 14px; font-size: 14px; }
.btn-lg { padding: 14px 28px; font-size: 16px; }

/* Alertes */
.alert { padding: 16px; border-radius: 8px; }
.alert-success { background: #d1fae5; color: #065f46; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert-warning { background: #fef3c7; color: #92400e; }
.alert-info { background: #dbeafe; color: #1e40af; }

/* Tables */
.table-responsive { overflow-x: auto; }
.table { width: 100%; }
.table th { padding: 12px; text-align: left; font-weight: 600; }
.table td { padding: 12px; border-top: 1px solid #e5e7eb; }

/* Empty state */
.empty-state { text-align: center; padding: 60px 30px; }
```

---

## Checklist finale avant commit

- [ ] Tous les emojis remplacés par icônes
- [ ] Header/footer dashboard sur pages connectées
- [ ] Header/footer public sur pages publiques
- [ ] Icônes avec getIcon()
- [ ] Badges avec tierBadge() / statusBadge()
- [ ] CSS cohérent
- [ ] Responsive testé
- [ ] Aucune erreur PHP
- [ ] Navigation fonctionne
- [ ] Liens corrects (SITE_URL)

---

## Commandes utiles

### Rechercher tous les emojis dans les fichiers
```bash
# Linux/Mac
grep -r "[\x{1F300}-\x{1F9FF}]" . --include="*.php"

# Windows (PowerShell)
Get-ChildItem -Recurse -Include *.php | Select-String -Pattern "[\u{1F300}-\u{1F9FF}]"
```

### Lister fichiers à mettre à jour
```bash
# Fichiers dashboard sans dashboard-header
grep -L "dashboard-header.php" dashboard/*.php orders/*.php support/*.php

# Fichiers pages sans public-header
grep -L "public-header.php" pages/*.php
```

---

## Priorités

### CRITIQUE (Aujourd'hui)
1. ✅ dashboard/index.php
2. ✅ dashboard/profile.php  
3. ✅ orders/new.php
4. dashboard/balance.php
5. orders/history.php
6. services/index.php

### IMPORTANT (Demain)
7. support/tickets.php
8. admin/dashboard.php
9. pages principales (about, contact, faq)

### PEUT ATTENDRE
10. Autres pages admin
11. Pages légales

---

## Notes

- Toujours tester après chaque modification
- Garder une copie backup
- Vérifier les chemins relatifs
- Tester responsive mobile
- Vérifier console JavaScript (F12)

---

**Dernière mise à jour:** 12 Octobre 2025
