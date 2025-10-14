# 📋 PLAN D'AMÉLIORATIONS SMM Mastery V2

Date: 12 Octobre 2025
Statut: En cours

---

## 🎯 OBJECTIFS PRIORITAIRES

### 1. UNIFICATION DES INCLUDES (CRITIQUE)

**Temps estimé: 2-3 heures**

#### A. Header/Footer Pages Publiques

- ✅ Créer `includes/public-header.php` (déjà fait)
- ✅ Créer `includes/public-footer.php` (déjà fait)
- 🔄 **REMPLACER TOUS LES EMOJIS PAR DES ICÔNES CDN PRO (Font Awesome / Boxicons)**
- ✅ Logo "SMM Mastery" doit pointer vers `/index.php`
- 🔄 Appliquer à: about, contact, disclaimer, faq, pricing, privacy, refund, terms

#### B. Header/Footer Pages Dashboard (Session)

- 🔄 Créer `includes/dashboard-header.php` avec session check
- 🔄 Créer `includes/dashboard-footer.php`
- 🔄 Navigation responsive avec user info
- 🔄 Appliquer à: dashboard/_, orders/_, services/_, support/_

#### C. Corrections CSS Admin

- 🔄 Fixer dépassement horizontal sur `admin/users.php`
- 🔄 Fixer dépassement horizontal sur `admin/services.php`

#### D. Icônes Professionnelles

- 🔄 Intégrer CDN Font Awesome ou Boxicons
- 🔄 Remplacer TOUS les emojis (💚💙💎👑📊📈💳🎫📧⚙️📱💻🚀✅❌⚠️)
- 🔄 Ajouter animations de brillance/glow sur icônes importantes

---

### 2. CONFIGURATION HÉBERGEMENT SOUS-DOMAINE (CRITIQUE)

**Temps estimé: 1 heure**

#### A. Configuration Domaine

- 🔄 Adapter `config.php` pour `smm.mini-services.tech`
- 🔄 Vérifier .htaccess pour sous-domaine
- 🔄 Tester redirections et chemins absolus

#### B. Configuration Email

- 🔄 Définir email principal: `contact@mini-services.tech` OU `smm@mini-services.tech`
- 🔄 Configurer SMTP dans config.php
- 🔄 Tester envoi emails (registration, orders, support)

#### C. Paramètres Sous-Domaine

- 🔄 URLs absolues vs relatives
- 🔄 Assets paths (CSS, JS, images)
- 🔄 API callbacks (PayPal, Stripe)
- 🔄 Session cookies domain

---

### 3. FONCTIONNALITÉS CRITIQUES (HAUTE PRIORITÉ)

**Temps estimé: 6-8 heures**

#### A. Admin Panel Support Tickets ✅

**STATUS: Déjà implémenté dans `admin/tickets.php`**

- ✅ Liste des tickets
- 🔄 Interface de réponse améliorée
- 🔄 Filtres et recherche
- 🔄 Statuts et priorités
- 🔄 Notifications email admin

#### B. Système Intelligent de Crédit Automatique ⭐⭐⭐

**PRIORITÉ MAXIMALE - Système novateur**

**Flux actuel problématique:**

```
Client paie → Crédite SMM Mastery → Client commande → Passe commande SMMFollows
          ❌ Risque: Fonds bloqués dans SMMFollows
```

**Nouveau flux intelligent:**

```
1. Client paie (PayPal/Stripe/Crypto)
2. Calcul automatique:
   - Montant reçu
   - Bénéfice (marge)
   - Montant à créditer chez SMMFollows
3. Crédit uniquement sur SMM Mastery (solde utilisateur)
4. Client passe commande
5. EN BACKGROUND:
   a. Calcul coût réel fournisseur
   b. API auto-crédit SMMFollows (via PayPal/Stripe)
   c. Passage commande SMMFollows
   d. Update statut
6. Si crédit fournisseur échoue:
   - Email alert admin
   - Commande en queue
   - Retry automatique (3x)
   - Alerte si échec final
```

**Fichiers à créer/modifier:**

- 🔄 `api/AutoCreditSystem.php` (Nouvelle classe)
- 🔄 `payment/process-order.php` (Gestion intelligente)
- 🔄 `cron/auto-credit-queue.php` (Queue processing)
- 🔄 Modifier `orders/new.php` (Integration système)
- 🔄 Logs détaillés: `logs/credit-system.log`

**APIs nécessaires:**

- SMMFollows API (add funds)
- PayPal Payouts API
- Stripe Transfers API
- Crypto wallet API

#### C. Emails de Confirmation Commande

- 🔄 Template email confirmation commande
- 🔄 Email quand commande "Processing"
- 🔄 Email quand commande "Completed"
- 🔄 Détails: Order ID, Service, Quantité, Délai estimé
- 🔄 Utiliser PHPMailer avec templates HTML

#### D. Audit Complet Fichiers V1

- 🔄 Vérifier toutes les fonctions
- 🔄 Tester tous les endpoints
- 🔄 Sécurité (SQL injection, XSS, CSRF)
- 🔄 Performance (requêtes, caching)
- 🔄 Documentation code

---

## 📅 PLANNING D'EXÉCUTION

### PHASE 1: UNIFICATION & ICÔNES (Jour 1 - 3h)

1. Intégrer CDN Font Awesome
2. Créer dashboard-header.php et dashboard-footer.php
3. Remplacer tous les emojis
4. Fixer dépassements CSS admin
5. Tester responsive

### PHASE 2: CONFIGURATION HÉBERGEMENT (Jour 1 - 1h)

1. Adapter config.php pour sous-domaine
2. Configurer emails
3. Tester chemins et URLs

### PHASE 3: SYSTÈME AUTO-CRÉDIT (Jour 2-3 - 8h)

1. Développer AutoCreditSystem.php
2. Intégrer aux paiements
3. Créer CRON queue processing
4. Tests exhaustifs
5. Gestion erreurs et alertes

### PHASE 4: EMAILS & FINITIONS (Jour 3 - 2h)

1. Templates emails commande
2. Configuration PHPMailer
3. Tests envoi
4. Audit final

### PHASE 5: TESTS & DÉPLOIEMENT (Jour 4 - 2h)

1. Tests complets end-to-end
2. Documentation mise à jour
3. Backup
4. Déploiement sur smm.mini-services.tech

---

## 🎯 PRIORITÉ D'EXÉCUTION

**MAINTENANT (Immédiat):**

1. ✅ Création ce document
2. 🔄 Intégration icônes CDN
3. 🔄 Unification headers/footers

**AUJOURD'HUI:**

1. 🔄 Configuration sous-domaine
2. 🔄 Emails de confirmation

**CETTE SEMAINE:**

1. 🔄 Système auto-crédit intelligent
2. 🔄 Tests exhaustifs
3. 🔄 Déploiement

---

## 📊 SUIVI DE PROGRESSION

- [ ] 1A. Headers/Footers unifiés
- [ ] 1B. Icônes professionnelles
- [ ] 1C. Corrections CSS admin
- [ ] 2A. Config sous-domaine
- [ ] 2B. Config emails
- [ ] 3A. Admin tickets amélioré
- [ ] 3B. Système auto-crédit
- [ ] 3C. Emails confirmation
- [ ] 3D. Audit V1

**Progression globale: 15% → Objectif 100%**

---

## 🚀 PROCHAINE ACTION IMMÉDIATE

**Commencer par:** Intégration Font Awesome + Unification headers/footers + Remplacement emojis

**Fichier à créer en premier:** `includes/icons-config.php`
