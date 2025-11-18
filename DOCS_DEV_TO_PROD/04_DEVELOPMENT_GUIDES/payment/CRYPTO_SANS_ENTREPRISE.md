# 🌍 Guide Crypto Payment - Sans Entreprise Officielle

**Projet :** SMM Mastery  
**Date :** 16 Octobre 2025  
**Contexte :** Solutions crypto pour freelancers/individus sans entreprise enregistrée

---

## 🎯 **Votre Situation**

❌ **Problème :** CoinPayments, Stripe, PayPal Business nécessitent une entreprise officielle  
✅ **Solution :** Utiliser des processeurs crypto sans KYC strict

---

## 💳 **Solutions Recommandées (Par Ordre)**

### 1️⃣ **NOWPayments** ⭐⭐⭐ (MEILLEUR CHOIX)

#### **Pourquoi NOWPayments ?**

- ✅ **Pas de KYC pour commencer** - inscription simple avec email
- ✅ **Fees bas** : 0.5-1% (vs 5-10% pour CoinPayments)
- ✅ **Multi-devises** : BTC, ETH, USDT, USDC, LTC, etc. (150+ coins)
- ✅ **API simple** : Documentation claire, webhooks IPN fiables
- ✅ **Retrait direct** : vers votre wallet crypto personnel (aucun compte bancaire requis)
- ✅ **Support freelancers** : accepte les individus

#### **Configuration NOWPayments**

**Étape 1 : Inscription**

```
1. Aller sur : https://nowpayments.io
2. Créer compte avec email (pas de documents requis au début)
3. Activer 2FA (recommandé)
```

**Étape 2 : Obtenir les clés API**

```
1. Dashboard → Settings → API Keys
2. Copier : API Key + IPN Secret
3. Configurer wallet de réception (BTC, ETH, USDT, etc.)
```

**Étape 3 : Configuration SMM Mastery**

```
Admin → Paramètres → Crypto (Multi-Gateway)
├── Processeur : NOWPayments
├── API Key : [Votre clé API]
├── IPN Secret : [Votre secret IPN]
├── Devise préférée : USDT (recommandé pour stabilité)
└── IPN Callback : https://votresite.com/payment/crypto-ipn.php
```

**Étape 4 : Tester**

```
1. Faire un dépôt test de 5-10 USD en USDT
2. Vérifier réception dans NOWPayments dashboard
3. Vérifier crédit automatique sur SMM Mastery
4. Retirer vers votre wallet personnel
```

#### **Fees NOWPayments**

```
Deposit : 0.5% (min 0.5 USD)
Withdrawal : Variable selon crypto (ex: USDT TRC-20 = ~1 USD)
```

#### **Retraits NOWPayments → Votre Wallet**

```
1. Dashboard → Payouts
2. Sélectionner crypto (ex: USDT TRC-20)
3. Entrer adresse wallet personnel
4. Confirmer (généralement traité en <1h)
```

---

### 2️⃣ **BTCPay Server** ⭐⭐ (SOLUTION AVANCÉE)

#### **Pourquoi BTCPay Server ?**

- ✅ **100% gratuit** et open-source
- ✅ **AUCUNE vérification** - vous contrôlez tout
- ✅ **0% fees** - sauf frais réseau blockchain
- ✅ **Fonds directs** - paiements arrivent directement dans votre wallet
- ✅ **Privacy** - aucune entreprise tierce ne voit vos transactions
- ⚠️ **Nécessite un VPS** (5-10 USD/mois) et compétences techniques

#### **Configuration BTCPay Server**

**Étape 1 : Héberger BTCPay**

```
Option A - VPS Self-Hosted (recommandé)
├── Fournisseur : Hostinger, DigitalOcean, Contabo
├── Coût : 5-10 USD/mois
├── Installation : https://docs.btcpayserver.org/Docker/
└── Compétences : Linux de base, Docker

Option B - Hébergement tiers (facile mais moins privacy)
├── LunaNode : https://launchbtcpay.lunanode.com
├── Voltage : https://voltage.cloud/btcpay
└── Coût : ~10 USD/mois
```

**Étape 2 : Créer un Store**

```
1. Accéder à votre instance BTCPay
2. Créer un store (boutique)
3. Connecter wallet BTC/Lightning
4. Générer API Key (Permissions: Store management)
```

**Étape 3 : Configuration SMM Mastery**

```
Admin → Paramètres → Crypto (Multi-Gateway)
├── Processeur : BTCPay Server
├── Server URL : https://votre-btcpay.com
├── Store ID : [ID de votre store]
├── API Key : [Clé API GreenField]
└── Webhook URL : https://votresite.com/payment/crypto-ipn.php
```

#### **Avantages BTCPay**

```
✅ Contrôle total (pas de censure possible)
✅ Anonymat complet (pas de KYC)
✅ Multi-crypto (BTC, Lightning Network, Altcoins)
✅ Pas de middleman qui peut bloquer vos fonds
```

#### **Inconvénients BTCPay**

```
⚠️ Nécessite VPS (coût récurrent)
⚠️ Setup technique (Docker, Linux)
⚠️ Maintenance (mises à jour, monitoring)
```

---

### 3️⃣ **CoinGate** (Alternative avec KYC léger)

#### **Pourquoi CoinGate ?**

- ⚠️ **KYC requis** mais accepte freelancers (passeport/ID suffit)
- ✅ **Support SEPA** - retraits en EUR vers compte bancaire
- ✅ **Interface simple** - bon pour débutants
- ⚠️ **Fees moyens** : 1% + frais réseau

#### **Configuration CoinGate**

```
1. Inscription : https://coingate.com
2. Vérification : ID + preuve d'adresse (facture électricité/internet)
3. API Key : Dashboard → Developer → API
4. SMM Mastery : Admin → Paramètres → CoinGate
```

---

## 📊 **Comparatif des Solutions**

| Critère            | NOWPayments   | BTCPay Server       | CoinGate      | CoinPayments           |
| ------------------ | ------------- | ------------------- | ------------- | ---------------------- |
| **KYC**            | ❌ Non        | ❌ Non              | ⚠️ Léger      | ✅ Strict (entreprise) |
| **Fees**           | 0.5-1%        | 0% (sauf réseau)    | 1%            | 5-10%                  |
| **Setup**          | ⭐⭐⭐ Facile | ⭐ Difficile        | ⭐⭐ Moyen    | ⭐⭐ Moyen             |
| **Freelancer OK**  | ✅ Oui        | ✅ Oui              | ✅ Oui        | ❌ Non                 |
| **Coût récurrent** | 0 USD         | 5-10 USD/mois (VPS) | 0 USD         | 0 USD                  |
| **Privacy**        | ⭐⭐ Moyen    | ⭐⭐⭐ Excellent    | ⭐ Faible     | ⭐ Faible              |
| **Retraits**       | Wallet crypto | Direct wallet       | SEPA + Wallet | Wallet crypto          |

---

## 🚀 **Ma Recommandation pour Vous**

### **Démarrer avec NOWPayments** (Phase 1 - Court terme)

```
✅ Setup en 15 minutes
✅ Pas de documents requis
✅ Fees acceptables (0.5%)
✅ Bon pour tester le marché
```

### **Migrer vers BTCPay Server** (Phase 2 - Moyen terme)

```
Quand SMM Mastery génère >500 USD/mois :
├── 0% fees = économies significatives
├── Contrôle total de vos fonds
├── Privacy maximale
└── Coût VPS amorti par volume
```

---

## 🔒 **Retrait des Fonds (Maroc)**

### **Option 1 : Crypto → P2P Local (Recommandé)**

```
1. NOWPayments → Retrait USDT TRC-20 (fees 1 USD)
2. Binance P2P : USDT → MAD (dirham marocain)
3. Rencontre en personne ou virement bancaire local
4. Taux : Généralement meilleur que banques
```

### **Option 2 : Crypto → Exchange → Banque**

```
1. Retrait vers Binance/Kraken
2. Vendre crypto contre EUR/USD
3. SEPA vers compte bancaire (si accessible depuis Maroc)
⚠️ Vérifier réglementations locales
```

### **Option 3 : Garder en Crypto**

```
✅ USDT/USDC = stablecoins (1:1 avec USD)
✅ Utiliser directement pour dépenses (cartes crypto Binance, Crypto.com)
✅ Éviter volatilité BTC/ETH
```

---

## ⚡ **Configuration Rapide NOWPayments (15 min)**

### **Étape 1 : Inscription (5 min)**

```bash
1. https://nowpayments.io → Sign Up
2. Email + mot de passe fort
3. Confirmer email
4. Activer 2FA (Google Authenticator)
```

### **Étape 2 : Configuration API (5 min)**

```bash
1. Dashboard → Settings → API Keys
2. Generate New API Key
3. Copier : API Key + IPN Secret
4. Settings → Payout Address → Ajouter wallet USDT (TRC-20 recommandé)
```

### **Étape 3 : SMM Mastery (5 min)**

```php
Admin → Paramètres → Crypto (Multi-Gateway)
├── Processeur : NOWPayments
├── API Key : [coller votre clé]
├── IPN Secret : [coller votre secret]
├── Devise préférée : USDT
└── Sauvegarder

// Tester avec petit dépôt (5-10 USD)
```

---

## 📝 **Conformité Légale (Maroc)**

### **Réglementation Crypto au Maroc**

```
⚠️ Office des Changes (Maroc) :
├── Crypto non régulée officiellement
├── Pas d'interdiction explicite pour particuliers
├── Zone grise légale (2025)
└── Recommandation : Montants modérés, usage personnel

✅ Bonnes pratiques :
├── Déclarer revenus si montants significatifs
├── Garder traces transactions (fiscalité future)
├── Consulter comptable si >100k MAD/an
```

---

## 🛠️ **Support Technique**

### **Documentation API**

- NOWPayments : https://documenter.getpostman.com/view/7907941/S1a32n38
- BTCPay : https://docs.btcpayserver.org/API/Greenfield/v1/
- CoinGate : https://developer.coingate.com/docs

### **Fichiers Concernés**

```
payment/gateways/nowpayments.php      → Handler paiement NOWPayments
payment/gateways/ipn/nowpayments-ipn.php → IPN verification (à implémenter)
payment/gateways/btcpay.php           → Handler paiement BTCPay
admin/settings.php                     → Configuration admin
```

---

## ✅ **Checklist Finale**

- [ ] Créer compte NOWPayments (0 KYC)
- [ ] Obtenir API Key + IPN Secret
- [ ] Configurer wallet de réception (USDT TRC-20)
- [ ] Entrer credentials dans SMM Mastery
- [ ] Tester avec dépôt 5-10 USD
- [ ] Vérifier crédit automatique balance
- [ ] Tester retrait vers wallet personnel
- [ ] Documenter process pour clients

---

**🎯 RÉSULTAT :** Système crypto 100% fonctionnel sans entreprise officielle, frais minimaux (0.5%), retraits directs vers wallet.

**💡 ASTUCE :** Offrir bonus crypto (ex: +10% sur dépôts crypto) pour encourager adoption et réduire frais PayPal.
