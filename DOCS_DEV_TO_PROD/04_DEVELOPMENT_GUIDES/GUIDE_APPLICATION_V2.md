# 🚀 GUIDE RAPIDE D'APPLICATION - SMM Mastery V2

**Pour passer de V1 à V2 en 30 minutes**

---

## ⚡ ÉTAPE 1: BACKUP (5 min)

```bash
# 1. Backup base de données
# Via phpMyAdmin: Export → SQL → Télécharger

# 2. Backup fichiers
# Copier tout le dossier smm vers smm_backup
cp -r D:\wamp64\www\smm D:\wamp64\www\smm_backup
```

---

## ⚡ ÉTAPE 2: MISE À JOUR BASE DE DONNÉES (5 min)

### Via phpMyAdmin:

1. Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
2. Sélectionner base `smm_master`
3. Onglet "SQL"
4. Copier/coller le contenu de `database-updates-v2.sql`
5. Cliquer "Exécuter"
6. Vérifier le message de succès

### Vérification:

```sql
-- Vérifier tables créées
SHOW TABLES LIKE '%auto_credit%';
SHOW TABLES LIKE 'email_%';

-- Vérifier settings
SELECT * FROM settings WHERE key_name LIKE 'auto_credit%';
SELECT * FROM email_templates;
```

**✅ Vous devriez voir:**

- auto_credit_queue
- provider_credit_log
- email_templates (avec 5 templates)
- email_logs

---

## ⚡ ÉTAPE 3: CONFIGURATION (5 min)

### Modifier `config.php`:

```php
// 1. Vérifier SITE_URL
define('SITE_URL', 'http://localhost/smm'); // En dev

// 2. Configurer email
define('SITE_EMAIL', 'smm@mini-services.tech');

// 3. Activer auto-crédit
define('AUTO_CREDIT_ENABLED', true);

// 4. Emails automatiques
define('SEND_ORDER_CONFIRMATION', true);
define('SEND_ORDER_PROCESSING', true);
define('SEND_ORDER_COMPLETED', true);
```

### Dans la base de données (settings):

```sql
-- Email fournisseur PayPal (IMPORTANT!)
UPDATE settings SET key_value = 'provider@email.com'
WHERE key_name = 'provider_paypal_email';

-- Activer auto-crédit
UPDATE settings SET key_value = '1'
WHERE key_name = 'auto_credit_enabled';

-- Méthode paiement par défaut
UPDATE settings SET key_value = 'paypal'
WHERE key_name = 'auto_credit_payment_method';
```

---

## ⚡ ÉTAPE 4: APPLIQUER NOUVEAUX HEADERS/FOOTERS (10 min)

### Template pour pages DASHBOARD (avec session):

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

// Vérifier session
if (!isLoggedIn()) {
    redirect(SITE_URL . '/auth/login.php');
}

$user = getCurrentUser($pdo);
$page_title = "Titre de la page"; // PERSONNALISER

// Inclure header
require_once __DIR__ . '/../includes/dashboard-header.php';
?>

<!-- VOTRE CONTENU ICI -->
<div class="dashboard-content">
    <div class="container">
        <h1>Contenu...</h1>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/dashboard-footer.php'; ?>
```

### Fichiers à mettre à jour:

- [ ] dashboard/index.php
- [ ] dashboard/profile.php
- [ ] dashboard/balance.php
- [ ] orders/history.php
- [ ] orders/tracking.php
- [ ] services/index.php
- [ ] support/tickets.php
- [ ] support/new-ticket.php
- [ ] support/view-ticket.php
- [ ] admin/\*.php (adapter selon admin)

### Template pour pages PUBLIQUES:

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$page_title = "Titre de la page";
$page_description = "Description...";

require_once __DIR__ . '/../includes/public-header.php';
?>

<!-- VOTRE CONTENU ICI -->

<?php require_once __DIR__ . '/../includes/public-footer.php'; ?>
```

### Fichiers à mettre à jour:

- [ ] pages/about.php
- [ ] pages/contact.php
- [ ] pages/faq.php
- [ ] pages/pricing.php
- [ ] pages/terms.php
- [ ] pages/privacy.php
- [ ] pages/refund.php
- [ ] pages/disclaimer.php

---

## ⚡ ÉTAPE 5: TESTER LE SYSTÈME (5 min)

### Test 1: Icônes et Interface

1. Ouvrir http://localhost/smm/
2. Vérifier les icônes s'affichent (🚀 → icône fusée Font Awesome)
3. Vérifier animations brillance
4. Tester responsive (F12 → mode mobile)

### Test 2: Session et Navigation

1. Se connecter
2. Vérifier header dashboard avec solde
3. Tester menu utilisateur (dropdown)
4. Tester menu mobile

### Test 3: Nouvelle Commande

1. Aller sur Services
2. Choisir un service
3. Aller sur "Nouvelle Commande"
4. Vérifier calculateur prix
5. **NE PAS SOUMETTRE ENCORE** (attendre config PayPal)

### Test 4: Emails (si SMTP configuré)

```php
// Créer test-email.php à la racine
<?php
require_once 'config.php';
require_once 'includes/EmailManager.php';

$emailManager = new EmailManager($pdo);

// Tester email de bienvenue
$test_user = [
    'id' => 1,
    'username' => 'Test',
    'email' => 'votre-email@test.com'
];

$result = $emailManager->sendWelcomeEmail($test_user);

echo $result ? "✅ Email envoyé!" : "❌ Échec envoi";
?>
```

---

## ⚡ ÉTAPE 6: CONFIGURATION CRON (Optionnel - Production)

### Sur serveur Linux:

```bash
# Éditer crontab
crontab -e

# Ajouter ces lignes
# Traiter queue auto-crédit toutes les 10 min
*/10 * * * * php /path/to/smm/cron/auto-credit-queue.php

# Sync services toutes les 6 heures
0 */6 * * * php /path/to/smm/cron/sync-services.php

# Vérifier commandes toutes les 10 min
*/10 * * * * php /path/to/smm/cron/check-orders.php
```

### Sur Windows (WAMP - dev):

1. Ouvrir "Planificateur de tâches"
2. Créer tâche basique
3. Déclencher: Toutes les 10 minutes
4. Action: `php.exe C:\wamp64\www\smm\cron\auto-credit-queue.php`

---

## 🧪 TESTS COMPLETS

### ✅ Checklist de tests:

#### Interface:

- [ ] Toutes les icônes s'affichent correctement
- [ ] Animations fonctionnent (hover, shine)
- [ ] Responsive sur mobile/tablet/desktop
- [ ] Aucun emoji visible (tous remplacés)
- [ ] Headers/footers cohérents sur toutes pages

#### Fonctionnalités:

- [ ] Inscription fonctionne
- [ ] Connexion fonctionne
- [ ] Dashboard affiche solde correct
- [ ] Services listés avec icônes
- [ ] Nouvelle commande: calculateur fonctionne
- [ ] Liens navigation fonctionnent

#### Base de données:

- [ ] Tables auto_credit créées
- [ ] Templates emails insérés
- [ ] Settings configurés
- [ ] Aucune erreur SQL

#### Système auto-crédit:

- [ ] Configuration dans settings OK
- [ ] Fichier AutoCreditSystem.php chargé sans erreur
- [ ] CRON queue exécutable
- [ ] Logs créés dans /logs/

---

## 🚨 DÉPANNAGE RAPIDE

### Erreur: "Class AutoCreditSystem not found"

```php
// Vérifier que le fichier existe
file_exists(__DIR__ . '/api/AutoCreditSystem.php'); // doit retourner true

// Vérifier require_once dans orders/new.php
require_once __DIR__ . '/../api/AutoCreditSystem.php';
```

### Erreur: "Table auto_credit_queue doesn't exist"

```sql
-- Réexécuter le SQL
source /path/to/database-updates-v2.sql;

-- Ou copier/coller dans phpMyAdmin
```

### Icônes ne s'affichent pas

```html
<!-- Vérifier que Font Awesome est chargé dans <head> -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
/>
```

### Emails ne partent pas

```php
// Dans config.php, vérifier:
define('SMTP_HOST', 'mail.mini-services.tech');
define('SMTP_USERNAME', 'smm@mini-services.tech');
define('SMTP_PASSWORD', 'VOTRE_MOT_DE_PASSE');

// Tester avec mail() simple
mail('test@test.com', 'Test', 'Test message');
```

---

## 📊 VALIDATION FINALE

### Tout est OK si:

✅ Aucune erreur PHP affichée
✅ Toutes les icônes s'affichent
✅ Navigation fluide
✅ Calculateur prix fonctionne
✅ Tables BDD créées
✅ Settings configurés

### Vous êtes prêt pour:

🚀 Tester auto-crédit en mode sandbox
🚀 Configurer PayPal/Stripe test
🚀 Passer commandes test
🚀 Déployer sur smm.mini-services.tech

---

## 📞 BESOIN D'AIDE?

**Email:** smm@mini-services.tech

**Logs à vérifier:**

- `logs/errors.log`
- `logs/auto-credit.log`
- `logs/api-calls.log`

**Fichiers importants:**

- `config.php` - Configuration générale
- `api/AutoCreditSystem.php` - Système auto-crédit
- `includes/EmailManager.php` - Emails
- `database-updates-v2.sql` - SQL updates

---

## 🎯 PROCHAINES ÉTAPES

Une fois V2 testée en local:

1. **Configurer production:**

   - Modifier ENVIRONMENT à 'production' dans config.php
   - Configurer vraies clés API PayPal/Stripe
   - Configurer SMTP emails
   - Tester sur smm.mini-services.tech

2. **Sécurité:**

   - Changer SECRET_KEY dans config
   - Activer HTTPS
   - Configurer firewall
   - Limiter accès admin

3. **Monitoring:**
   - Vérifier logs quotidiennement
   - Surveiller queue auto-crédit
   - Analyser emails envoyés
   - Suivre commandes

---

**✨ Bon développement !**
