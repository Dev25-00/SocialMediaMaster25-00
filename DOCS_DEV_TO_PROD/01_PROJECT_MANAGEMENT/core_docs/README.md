# 🚀 SMM Mastery V2.0

**Plateforme SMM Professionnelle avec Système Auto-Crédit Intelligent**

[![Version](https://img.shields.io/badge/version-2.0-blue.svg)](https://github.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-Proprietary-red.svg)](LICENSE)

---

## 📋 Table des Matières

- [À Propos](#-à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Nouveautés V2](#-nouveautés-v2)
- [Installation](#-installation)
- [Configuration](#️-configuration)
- [Utilisation](#-utilisation)
- [Architecture](#-architecture)
- [Documentation](#-documentation)
- [Support](#-support)

---

## 🎯 À Propos

**SMM Mastery** est une plateforme complète de gestion et revente de services SMM (Social Media Marketing). Elle permet d'acheter et revendre des services de croissance sur les réseaux sociaux (followers, likes, views, etc.) avec un système de marges optimisées.

### Caractéristiques Principales

- ✅ **Interface moderne** avec icônes professionnelles Font Awesome
- ✅ **Système auto-crédit intelligent** (unique sur le marché!)
- ✅ **Multi-tiers** (Budget, Standard, Premium, Ultimate)
- ✅ **Emails automatiques** avec templates HTML
- ✅ **API revendeurs** complète
- ✅ **Dashboard analytics** avec graphiques
- ✅ **Responsive** mobile, tablet, desktop
- ✅ **Multi-environnement** (dev, staging, prod)

---

## ✨ Fonctionnalités

### Pour les Clients

- 🛍️ **Catalogue de services** : 100+ services SMM
- 💳 **Paiements sécurisés** : PayPal, Stripe, Crypto
- 📊 **Dashboard personnel** : Statistiques et graphiques
- 🔍 **Suivi commandes** : Tracking en temps réel
- 💬 **Support tickets** : Système de tickets intégré
- 🎁 **Programme fidélité** : Bonus et récompenses

### Pour les Revendeurs

- 🔌 **API complète** : Documentation et exemples
- 📈 **Marges personnalisées** : 50% à 600%
- 🔐 **Clé API sécurisée** : Rate limiting
- 📊 **Statistiques détaillées** : Analytics avancées
- 💰 **Paiements automatiques** : Système transparent

### Pour les Admins

- 👥 **Gestion utilisateurs** : CRUD complet
- 📦 **Gestion services** : Sync auto SMMFollows
- 📋 **Gestion commandes** : Monitoring temps réel
- 🎫 **Gestion support** : Interface tickets
- ⚙️ **Configuration** : Settings centralisés
- 📊 **Analytics** : Rapports détaillés

---

## 🆕 Nouveautés V2

### 🎨 Interface Modernisée

- **Icônes professionnelles** : Remplacement complet des emojis par Font Awesome 6
- **Animations fluides** : Hover, shine, pulse, glow effects
- **Headers/Footers unifiés** : Interface cohérente sur tout le site
- **Badges animés** : Tiers et statuts avec animations

### ⭐ Système Auto-Crédit Intelligent

**LA FONCTIONNALITÉ RÉVOLUTIONNAIRE DE V2**

```
Principe:
Client paie → SMM Mastery crédité
Client commande → Auto-crédit fournisseur UNIQUEMENT au besoin
Échec? → Queue + Retry automatique + Alerte admin
```

**Avantages:**

- ✅ Fonds jamais bloqués chez le fournisseur
- ✅ Crédit automatique via PayPal/Stripe
- ✅ Système de retry intelligent (3 tentatives)
- ✅ Alertes email si échec
- ✅ Logs détaillés de toutes opérations

### 📧 Emails Automatiques

- Welcome email à l'inscription
- Confirmation de commande
- Notification traitement
- Notification terminée
- Confirmation de dépôt

### 🔧 Configuration Avancée

- Multi-environnement (dev/staging/prod)
- Configuration emails centralisée
- Support sous-domaine
- SMTP configurable
- Debug mode contextuel

---

## 🚀 Installation

### Prérequis

- PHP 8.2 ou supérieur
- MySQL 8.0 ou supérieur
- Serveur web (Apache/Nginx)
- Composer (optionnel)
- Extension PHP : PDO, cURL, mbstring, JSON

### Étape 1: Télécharger

```bash
git clone https://github.com/votre-repo/smm-Mastery.git
cd smm-Mastery
```

### Étape 2: Base de données

```sql
-- Créer la base de données
CREATE DATABASE smm_master CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Importer le schéma
mysql -u root -p smm_master < database.sql

-- Importer les mises à jour V2
mysql -u root -p smm_master < database-updates-v2.sql
```

### Étape 3: Configuration

```bash
# Copier et modifier config.php
cp config.sample.php config.php
nano config.php

# Configurer:
# - DB_HOST, DB_NAME, DB_USER, DB_PASS
# - SITE_URL
# - SITE_EMAIL
# - API Keys (PayPal, Stripe, SMMFollows)
```

### Étape 4: Permissions

```bash
chmod 755 -R .
chmod 777 -R logs/
chmod 777 -R uploads/
```

### Étape 5: CRON Jobs

```bash
crontab -e

# Ajouter:
*/10 * * * * php /path/to/smm/cron/auto-credit-queue.php
0 */6 * * * php /path/to/smm/cron/sync-services.php
*/10 * * * * php /path/to/smm/cron/check-orders.php
```

### Étape 6: Premier accès

```
URL: http://votre-domaine.com
Admin: admin / admin123 (À CHANGER!)
```

---

## ⚙️ Configuration

### Config.php

```php
// Environnement
define('ENVIRONMENT', 'production'); // development, staging, production

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'smm_master');
define('DB_USER', 'votre_user');
define('DB_PASS', 'votre_password');

// Site
define('SITE_URL', 'https://smm.mini-services.tech');
define('SITE_EMAIL', 'smm@mini-services.tech');

// Auto-crédit
define('AUTO_CREDIT_ENABLED', true);
define('AUTO_CREDIT_ALERT_EMAIL', 'admin@example.com');

// APIs
define('SMMFOLLOWS_API_KEY', 'votre_cle');
define('PAYPAL_CLIENT_ID', 'votre_client_id');
define('STRIPE_SECRET_KEY', 'votre_secret');
```

### Settings Base de Données

```sql
-- Email fournisseur PayPal
UPDATE settings SET key_value = 'provider@paypal.com'
WHERE key_name = 'provider_paypal_email';

-- Activer auto-crédit
UPDATE settings SET key_value = '1'
WHERE key_name = 'auto_credit_enabled';
```

---

## 📖 Utilisation

### Pour les Clients

1. **S'inscrire** : Créer un compte (bonus $1 offert)
2. **Ajouter des fonds** : PayPal, Stripe, ou Crypto
3. **Choisir un service** : Parcourir le catalogue
4. **Passer commande** : Indiquer lien et quantité
5. **Suivre** : Tracking en temps réel
6. **Support** : Créer un ticket si besoin

### Pour les Revendeurs

1. **Générer API Key** : Dashboard → API Access
2. **Intégrer API** : Documentation disponible
3. **Passer commandes** : Via API
4. **Suivre** : Analytics et logs
5. **Encaisser** : Marges automatiques

### Pour les Admins

1. **Dashboard** : Vue d'ensemble
2. **Gérer utilisateurs** : CRUD complet
3. **Gérer services** : Sync SMMFollows
4. **Traiter tickets** : Support client
5. **Configurer** : Settings personnalisés
6. **Analyser** : Rapports et stats

---

## 🏗️ Architecture

### Stack Technique

```
Backend:
- PHP 8.2+
- MySQL 8.0+
- PDO

Frontend:
- HTML5
- CSS3
- JavaScript (Vanilla)
- Font Awesome 6

APIs:
- SMMFollows API
- PayPal Payouts API
- Stripe Transfers API

Sécurité:
- HTTPS/SSL
- bcrypt password hashing
- CSRF protection
- SQL injection protection
- XSS prevention
- Rate limiting
```

### Structure Fichiers

```
smm-Mastery/
├── api/
│   ├── AutoCreditSystem.php    ⭐ Système auto-crédit
│   └── SMMFollowsAPI.php
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   ├── icons.css           ⭐ Styles icônes
│   │   └── dashboard.css
│   └── js/
├── includes/
│   ├── dashboard-header.php    ⭐ Header unifié
│   ├── dashboard-footer.php    ⭐ Footer unifié
│   ├── icons-config.php        ⭐ Config icônes
│   └── EmailManager.php        ⭐ Gestion emails
├── dashboard/
├── orders/
├── services/
├── support/
├── admin/
├── cron/
│   └── auto-credit-queue.php   ⭐ CRON auto-crédit
└── config.php
```

---

## 📚 Documentation

### Fichiers Documentation

- `📋 PLAN_AMELIORATIONS_V2.md` - Plan complet V2
- `📊 PROGRESSION_V2.md` - Suivi progression
- `🚀 GUIDE_APPLICATION_V2.md` - Guide application
- `📝 UPDATE_GUIDE.md` - Templates mise à jour
- `✅ CHECKLIST.md` - Checklist complète
- `📄 SESSION_RECAP.md` - Résumé session

### API Documentation

Documentation complète disponible dans `/pages/api.php`

Exemple requête:

```bash
curl -X POST https://smm.mini-services.tech/api/v1/order \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "service": 123,
    "link": "https://instagram.com/username",
    "quantity": 1000
  }'
```

---

## 🔒 Sécurité

### Bonnes Pratiques

- ✅ Toujours utiliser HTTPS en production
- ✅ Changer SECRET_KEY dans config.php
- ✅ Modifier identifiants admin par défaut
- ✅ Configurer firewall serveur
- ✅ Backups quotidiens automatiques
- ✅ Monitoring des logs
- ✅ Mises à jour régulières

### Logs à Surveiller

```bash
tail -f logs/errors.log          # Erreurs PHP
tail -f logs/auto-credit.log     # Système auto-crédit
tail -f logs/api-calls.log       # Appels API
```

---

## 🐛 Dépannage

### Problèmes Courants

**Erreur: "Class AutoCreditSystem not found"**

```bash
# Vérifier que le fichier existe
ls -la api/AutoCreditSystem.php
```

**Tables manquantes**

```bash
# Exécuter SQL V2
mysql -u root -p smm_master < database-updates-v2.sql
```

**Icônes ne s'affichent pas**

```html
<!-- Vérifier CDN Font Awesome dans <head> -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
/>
```

**Emails ne partent pas**

```php
// Configurer SMTP dans config.php
define('SMTP_HOST', 'mail.example.com');
define('SMTP_USERNAME', 'user@example.com');
define('SMTP_PASSWORD', 'password');
```

---

## 🤝 Support

### Besoin d'aide ?

- 📧 **Email:** smm@mini-services.tech
- 💬 **Tickets:** Créer un ticket dans le panel admin
- 📖 **Documentation:** Consulter les fichiers .md
- 🐛 **Bugs:** Signaler dans les issues

### Heures de Support

- **Lun-Ven:** 9h00 - 18h00 (GMT+1)
- **Sam-Dim:** Support limité
- **Urgences:** Email 24/7

---

## 📊 Roadmap

### V2.1 (En cours)

- [x] Système auto-crédit
- [x] Interface modernisée
- [x] Emails automatiques
- [ ] Tests end-to-end complets
- [ ] Déploiement production

### V2.5 (Q1 2026)

- [ ] API revendeurs complète
- [ ] Dashboard analytics avancé
- [ ] Programme fidélité
- [ ] Mobile app (PWA)

### V3.0 (Q2 2026)

- [ ] Multi-langue (FR/EN/ES/AR)
- [ ] Mode sombre
- [ ] Wallet crypto intégré
- [ ] IA recommendations

---

## 📜 Licence

© 2025 SMM Mastery. Tous droits réservés.

Ce logiciel est propriétaire. Toute reproduction, distribution ou modification non autorisée est strictement interdite.

---

## 👏 Crédits

**Développé par:** L'équipe SMM Mastery
**Version:** 2.0
**Date:** Octobre 2025

**Technologies utilisées:**

- PHP
- MySQL
- Font Awesome
- Chart.js
- SMMFollows API

---

## 🎉 Changelog

### V2.0 (12 Octobre 2025)

- ⭐ Système auto-crédit intelligent
- 🎨 Interface complètement redesignée
- 📧 Emails automatiques avec templates
- 🔧 Configuration multi-environnement
- 📚 Documentation complète
- 🐛 Corrections bugs V1

### V1.0 (11 Octobre 2025)

- 🚀 Release initiale
- 📦 Catalogue services
- 💳 Paiements intégrés
- 👥 Gestion utilisateurs
- 🎫 Support tickets

---

**🚀 SMM Mastery V2 - Votre partenaire pour une croissance sociale authentique**

Pour plus d'informations, visitez : [smm.mini-services.tech](https://smm.mini-services.tech)
