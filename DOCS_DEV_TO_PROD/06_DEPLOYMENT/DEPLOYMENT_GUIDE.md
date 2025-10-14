# 🚀 GUIDE DÉPLOIEMENT - MINI-SERVICES.TECH

**Date :** 11 Octobre 2025  
**Objectif :** Déployer SMM Mastery sur mini-services.tech pour tests réels

---

## ✅ CORRECTIONS CSS APPLIQUÉES

### **Problèmes identifiés et corrigés :**

1. ❌ **Scroll horizontal sur desktop** → ✅ **CORRIGÉ**
2. ❌ **Responsive mobile pas optimal** → ✅ **CORRIGÉ**
3. ❌ **Espacements débordants** → ✅ **CORRIGÉ**

### **Fichiers créés :**

- ✅ `assets/css/fixes.css` - Corrections complètes
- ✅ `assets/js/mobile-menu.js` - Menu mobile fonctionnel

---

## 📋 CHECKLIST PRÉ-DÉPLOIEMENT

### **1. Vérifications locales (5 min)**

- [ ] Tester toutes les pages en desktop (1920px, 1366px, 1024px)
- [ ] Tester toutes les pages en mobile (768px, 480px, 375px)
- [ ] Vérifier qu'il n'y a plus de scroll horizontal
- [ ] Vérifier que le menu mobile fonctionne
- [ ] Tester un paiement complet (simulate-ipn)
- [ ] Tester une commande complète

### **2. Fichiers à inclure dans TOUTES les pages**

Ajouter dans le `<head>` de **chaque page** :

```html
<!-- CSS Principal -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css" />

<!-- CSS Dashboard (pour pages dashboard/admin) -->
<link
  rel="stylesheet"
  href="<?php echo SITE_URL; ?>/assets/css/dashboard.css"
/>

<!-- CSS FIXES (IMPORTANT !) -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/fixes.css" />
```

Ajouter avant le `</body>` de **chaque page avec sidebar** :

```html
<!-- JS Mobile Menu -->
<script src="<?php echo SITE_URL; ?>/assets/js/mobile-menu.js"></script>
```

### **3. Fichiers à supprimer avant production** ⚠️

```
payment/test-payment.php          ❌ SUPPRIMER
payment/simulate-ipn.php          ❌ SUPPRIMER
admin/verify-config.php           ❌ SUPPRIMER (optionnel)
admin/fix-admin-role.php          ❌ SUPPRIMER (optionnel)
install.php                       ❌ SUPPRIMER (après installation)
```

---

## 🌐 DÉPLOIEMENT SUR MINI-SERVICES.TECH

### **ÉTAPE 1 : Créer le sous-domaine (5 min)**

1. **Se connecter à cPanel de mini-services.tech**

2. **Créer un sous-domaine :**

   ```
   Nom du sous-domaine : smm (ou smmmaster, ou panel, etc.)
   Domaine complet : smm.mini-services.tech
   ```

3. **Dossier racine :**
   ```
   public_html/smm
   ou
   subdomains/smm
   ```

---

### **ÉTAPE 2 : Préparer la base de données (3 min)**

1. **Aller dans cPanel > MySQL Databases**

2. **Créer une nouvelle base de données :**

   ```
   Nom : minitech_smm
   ```

3. **Créer un utilisateur MySQL :**

   ```
   Utilisateur : minitech_smm_user
   Mot de passe : [Générer un mot de passe fort]
   ```

4. **Associer l'utilisateur à la BDD :**

   - Cocher "ALL PRIVILEGES"
   - Sauvegarder

5. **Noter les informations :**
   ```
   DB_HOST: localhost
   DB_NAME: minitech_smm
   DB_USER: minitech_smm_user
   DB_PASS: [votre mot de passe]
   ```

---

### **ÉTAPE 3 : Upload des fichiers (10 min)**

#### **Option A : Via FileZilla (FTP)**

1. **Connexion FTP :**

   ```
   Hôte : ftp.mini-services.tech (ou IP du serveur)
   Utilisateur : [votre user cPanel]
   Mot de passe : [votre password cPanel]
   Port : 21
   ```

2. **Naviguer vers le dossier :**

   ```
   /public_html/smm/
   ```

3. **Uploader TOUS les fichiers du projet**
   - Sélectionner tout dans `D:\wamp64\www\smm\`
   - Uploader (peut prendre 5-10 minutes)

#### **Option B : Via File Manager cPanel**

1. **cPanel > File Manager**

2. **Naviguer vers `/public_html/smm/`**

3. **Upload**
   - Créer un ZIP de votre projet local
   - Uploader le ZIP
   - Extraire le ZIP

---

### **ÉTAPE 4 : Configuration (5 min)**

1. **Modifier `config.php` :**

```php
<?php
// ========== DATABASE ==========
define('DB_HOST', 'localhost');
define('DB_NAME', 'minitech_smm');           // ← BDD créée
define('DB_USER', 'minitech_smm_user');      // ← User créé
define('DB_PASS', 'VOTRE_MOT_DE_PASSE');     // ← Password

// ========== SITE CONFIG ==========
define('SITE_NAME', 'SMM Mastery');
define('SITE_URL', 'https://smm.mini-services.tech');  // ← Votre sous-domaine
define('SITE_EMAIL', 'contact@mini-services.tech');

// ========== SECURITY ==========
session_start();
ini_set('display_errors', 0);  // ← IMPORTANT : 0 en production !
error_reporting(0);            // ← IMPORTANT : 0 en production !

// ========== TIMEZONE ==========
date_default_timezone_set('Africa/Casablanca');

// ========== DATABASE CONNECTION ==========
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données');
}
?>
```

2. **Permissions des dossiers :**

Via FTP ou File Manager :

```
chmod 755 : tous les dossiers
chmod 644 : tous les fichiers PHP
chmod 777 : dossier /logs/ (si existant)
chmod 777 : dossier /uploads/ (si existant)
```

---

### **ÉTAPE 5 : Installation de la BDD (2 min)**

1. **Aller sur :**

   ```
   https://smm.mini-services.tech/install.php
   ```

2. **Suivre les 4 étapes**

   - Validation
   - Création BDD
   - Configuration
   - Compte admin

3. **SUPPRIMER install.php après installation :**
   ```
   rm /public_html/smm/install.php
   ```

---

### **ÉTAPE 6 : Configuration SSL (Auto ou Manuel)**

#### **Option A : SSL Auto (Let's Encrypt via cPanel)**

1. **cPanel > SSL/TLS Status**
2. **Trouver** `smm.mini-services.tech`
3. **Run AutoSSL** ou **Install SSL**
4. ✅ SSL activé automatiquement

#### **Option B : Forcer HTTPS via .htaccess**

Créer/modifier `.htaccess` dans `/public_html/smm/` :

```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Protection
Options -Indexes
```

---

### **ÉTAPE 7 : Configuration PayPal LIVE (5 min)**

1. **Se connecter au site :**

   ```
   https://smm.mini-services.tech/admin/settings.php
   ```

2. **Section PayPal :**

   ```
   Email PayPal : votre@email-paypal-business.com
   Mode PayPal : Live (Production)  ← IMPORTANT !
   ```

3. **Sauvegarder**

---

### **ÉTAPE 8 : Configuration SMMFollows (2 min)**

1. **Admin > Paramètres**

2. **API SMMFollows :**

   ```
   Clé API : ee754a1d73166198cc2b1a9ff0d8502f
   ```

3. **Sauvegarder**

4. **Synchroniser les services :**
   ```
   https://smm.mini-services.tech/admin/sync-services.php
   ```

---

### **ÉTAPE 9 : Tests en conditions réelles (10 min)**

#### **Test 1 : Navigation générale**

- [ ] Page d'accueil accessible
- [ ] Responsive OK (mobile/tablet/desktop)
- [ ] Pas de scroll horizontal
- [ ] Menu mobile fonctionne

#### **Test 2 : Authentification**

- [ ] Inscription fonctionne
- [ ] Connexion fonctionne
- [ ] Déconnexion fonctionne

#### **Test 3 : Dashboard**

- [ ] Stats s'affichent
- [ ] Navigation OK
- [ ] Responsive OK

#### **Test 4 : Paiement PayPal (VRAI TEST !)**

```
1. Dashboard > Mon Solde
2. Entrer un petit montant (1-3$)
3. Payer avec carte @shopping BP Maroc
4. Vérifier le crédit automatique (IPN)
5. ✅ Solde crédité ? → SUCCESS !
```

#### **Test 5 : Commande service**

- [ ] Parcourir les services
- [ ] Passer une commande
- [ ] Vérifier la transmission à SMMFollows
- [ ] Vérifier le statut

---

## 🎯 CONFIGURATION OPTIMALE

### **php.ini** (si accessible via cPanel)

```ini
upload_max_filesize = 32M
post_max_size = 32M
max_execution_time = 300
memory_limit = 256M
display_errors = Off
log_errors = On
error_log = /home/minitech/error_log
```

### **Sécurité**

1. **Fichier .htaccess racine :**

```apache
# Protection files
<Files .htaccess>
deny from all
</Files>

<Files config.php>
deny from all
</Files>

<Files database.sql>
deny from all
</Files>

# Désactiver directory listing
Options -Indexes

# Protection XSS
<IfModule mod_headers.c>
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
</IfModule>
```

---

## 🔧 DÉPANNAGE

### **Erreur : Base de données inaccessible**

→ Vérifier config.php (DB_HOST, DB_NAME, DB_USER, DB_PASS)  
→ Vérifier que l'utilisateur est associé à la BDD

### **Erreur 500**

→ Vérifier les permissions (755 pour dossiers, 644 pour fichiers)  
→ Vérifier les logs d'erreur : cPanel > Errors

### **CSS ne charge pas**

→ Vérifier que fixes.css est bien uploadé  
→ Vérifier les chemins dans les includes  
→ Vider le cache du navigateur (Ctrl+F5)

### **PayPal ne fonctionne pas**

→ Vérifier Mode = Live (pas Sandbox)  
→ Vérifier l'email PayPal Business  
→ Vérifier l'URL IPN : `https://smm.mini-services.tech/payment/paypal-ipn.php`

### **IPN ne crédite pas**

→ Vérifier les logs : `logs/paypal_ipn.log`  
→ Vérifier que le fichier paypal-ipn.php existe  
→ Tester manuellement : simulate-ipn.php (puis supprimer !)

---

## 📊 MONITORING POST-DÉPLOIEMENT

### **À vérifier quotidiennement (1ère semaine)**

- [ ] Logs d'erreur cPanel
- [ ] Logs PayPal IPN (`logs/paypal_ipn.log`)
- [ ] Transactions en attente
- [ ] Commandes en cours
- [ ] Solde SMMFollows suffisant

### **Backups**

Configurer backups automatiques :

1. **cPanel > Backup**
2. **Activer backup quotidien**
3. **Conserver 7 jours minimum**

---

## 🎉 CHECKLIST FINALE

- [ ] Sous-domaine créé et accessible
- [ ] SSL activé (HTTPS)
- [ ] Base de données créée et configurée
- [ ] Fichiers uploadés
- [ ] config.php modifié
- [ ] install.php exécuté puis supprimé
- [ ] test-payment.php supprimé
- [ ] simulate-ipn.php supprimé
- [ ] PayPal configuré en mode Live
- [ ] API SMMFollows configurée
- [ ] Services synchronisés
- [ ] Test paiement réel effectué ✅
- [ ] Test commande effectué ✅
- [ ] Responsive vérifié ✅
- [ ] Pas de scroll horizontal ✅

---

## 💳 TEST AVEC CARTE @SHOPPING BP MAROC

### **Procédure recommandée :**

1. **Créer un nouveau compte sur votre site**

   ```
   Email : un email de test
   Username : test_user_bp
   ```

2. **Ajouter 2-3 USD depuis ce compte**

   - Utiliser la carte @shopping BP Maroc
   - PayPal convertira MAD → USD automatiquement

3. **Vérifier le crédit automatique (IPN)**

   - Le solde doit être crédité en 10-30 secondes
   - Vérifier l'historique des transactions
   - Vérifier les logs IPN

4. **Passer une commande de test**
   - Choisir un service peu cher (< 1$)
   - Compléter la commande
   - Vérifier sur SMMFollows que la commande est passée

---

## 🚀 PROCHAINES ÉTAPES APRÈS DÉPLOIEMENT

1. **Marketing :** Créer page d'accueil attractive
2. **SEO :** Optimiser pour les moteurs de recherche
3. **Support :** Préparer FAQ et documentation
4. **Monitoring :** Mettre en place Google Analytics
5. \*\*Améliora

tions :\*\* Écouter les retours utilisateurs

---

## 📞 SUPPORT

**Besoin d'aide ?**

- Documentation : Consulter les fichiers .md du projet
- Logs : Vérifier `/logs/` et cPanel errors
- Tests : Utiliser les outils de développement du navigateur (F12)

---

**Bon déploiement ! 🎊**

---

**Fichier créé automatiquement - SMM Mastery v1.0**
