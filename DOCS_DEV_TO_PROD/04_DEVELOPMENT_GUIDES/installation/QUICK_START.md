# 🚀 Guide de Démarrage Rapide - SMM Mastery

Installation et mise en ligne en **moins de 10 minutes** !

---

## 📋 Checklist Pre-Installation

Avant de commencer, assurez-vous d'avoir :

- [ ] Un serveur web (Apache/Nginx)
- [ ] PHP 8.0+ installé
- [ ] MySQL 8.0+ installé
- [ ] Un nom de domaine
- [ ] Un compte SMMFollows avec API Key
- [ ] Un compte PayPal Business (optionnel)

---

## ⚡ Installation en 5 Étapes

### Étape 1 : Upload des fichiers (2 min)

**Via FTP :**

```
1. Connectez-vous via FileZilla/FTP
2. Uploadez tous les fichiers dans public_html/ ou www/
3. Attendez la fin du transfert
```

**Via SSH (plus rapide) :**

```bash
cd /var/www/html
git clone https://github.com/votre-repo/smm-Mastery.git
cd smm-Mastery
```

### Étape 2 : Permissions (1 min)

```bash
chmod 755 -R .
chmod 777 logs uploads
```

### Étape 3 : Installation automatique (3 min)

1. Ouvrez votre navigateur
2. Allez sur : `https://votredomaine.com/install.php`
3. Suivez les 4 étapes :

   - ✅ Vérification (automatique)
   - ✅ Base de données (entrez vos infos MySQL)
   - ✅ Tables (création automatique)
   - ✅ Admin (créez votre compte)

4. **IMPORTANT** : Supprimez `install.php` !

```bash
rm install.php
```

### Étape 4 : Configuration SMMFollows (2 min)

1. Connectez-vous avec votre compte admin
2. Allez dans **Admin > Paramètres**
3. Section **SMMFollows API**
4. Entrez votre API Key
5. Cliquez sur **Sauvegarder**

### Étape 5 : Première synchronisation (2 min)

**Via navigateur (une seule fois) :**

```
https://votredomaine.com/cron/sync-services.php?cron_key=test
```

**Ou via SSH :**

```bash
php cron/sync-services.php
```

Attendez que la synchronisation se termine (1-2 minutes).

---

## 🎉 C'est Terminé !

Votre plateforme SMM est maintenant **opérationnelle** !

### Testez maintenant :

1. **Page d'accueil** : https://votredomaine.com
2. **Dashboard Admin** : https://votredomaine.com/admin/dashboard.php
3. **Services** : https://votredomaine.com/services/index.php

---

## ⚙️ Configuration CRON (Important !)

Pour que le système fonctionne automatiquement, ajoutez ces CRON jobs :

```bash
crontab -e
```

Ajoutez ces lignes :

```bash
# Sync services toutes les 6h
0 */6 * * * php /var/www/html/smm-Mastery/cron/sync-services.php

# Check orders toutes les 10 min
*/10 * * * * php /var/www/html/smm-Mastery/cron/check-orders.php
```

Sauvegardez avec `Ctrl+X` puis `Y` puis `Enter`.

---

## 💳 Configuration Paiements (Optionnel)

### PayPal (Recommandé)

1. Admin > Paramètres > PayPal
2. Email PayPal Business : `votre@email.com`
3. Mode : `Live`
4. Sauvegarder

### Stripe

1. Admin > Paramètres > Stripe
2. Public Key : `pk_live_xxx`
3. Secret Key : `sk_live_xxx`
4. Mode : `Live`
5. Sauvegarder

---

## 🔒 Sécurité (À faire maintenant !)

### 1. Changez le mot de passe admin

```
1. Dashboard > Profil
2. Changez "Admin@123"
3. Utilisez un mot de passe fort
```

### 2. Activez HTTPS

**Avec Let's Encrypt (gratuit) :**

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d votredomaine.com
```

### 3. Désactivez le mode Debug

Éditez `config.php` :

```php
define('DEBUG_MODE', false); // Mettre false en production
```

---

## 📊 Premiers Pas

### Créer un utilisateur test

1. Déconnectez-vous du compte admin
2. Allez sur : https://votredomaine.com/auth/register.php
3. Créez un compte
4. Recevez 1$ de bonus
5. Testez une commande !

### Ajuster les prix (optionnel)

1. Admin > Services
2. Filtrez par plateforme
3. Modifiez les prix de vente manuellement
4. Ou laissez les marges automatiques

### Personnaliser le site

1. Modifiez `index.php` (page d'accueil)
2. Changez les couleurs dans `assets/css/main.css`
3. Ajoutez votre logo dans `assets/images/`

---

## ❓ Problèmes Courants

### Erreur : "API Key non configurée"

➡️ Allez dans Admin > Paramètres et entrez votre API Key SMMFollows

### Erreur : "Cannot connect to database"

➡️ Vérifiez les identifiants dans `config.php`

### Services vides

➡️ Lancez manuellement : `php cron/sync-services.php`

### Erreur 500

➡️ Activez `DEBUG_MODE` dans `config.php` pour voir l'erreur

### CRON ne fonctionne pas

➡️ Vérifiez les chemins absolus dans crontab
➡️ Testez manuellement : `php cron/sync-services.php`

---

## 📞 Besoin d'Aide ?

- 📖 **Documentation complète** : Voir README.md
- 💬 **Support** : support@smmmaster.com
- 🐛 **Bug Report** : GitHub Issues

---

## ✅ Checklist Post-Installation

Avant de lancer officiellement :

- [ ] ✅ Installation terminée
- [ ] ✅ SMMFollows API configurée
- [ ] ✅ Services synchronisés
- [ ] ✅ CRON jobs configurés
- [ ] ✅ HTTPS activé
- [ ] ✅ Mot de passe admin changé
- [ ] ✅ Debug mode désactivé
- [ ] ✅ Paiements configurés
- [ ] ✅ Test de commande effectué
- [ ] ✅ Backup configuré
- [ ] ✅ Logo/design personnalisé

---

## 🚀 Prêt à Lancer !

Votre plateforme SMM est maintenant prête à générer des revenus !

**Prochaines étapes :**

1. Créez vos comptes sociaux (Instagram, Twitter, etc.)
2. Faites votre première campagne marketing
3. Partagez votre lien d'affiliation
4. Commencez à gagner ! 💰

---

**Bon succès avec SMM Mastery ! 🎉**

_Version 1.0 - 11 Octobre 2025_
