# 🧪 Guide Test Crypto en Local (SANS Hosting Public)

**Projet :** SMM Mastery  
**Date :** 16 Octobre 2025  
**Contexte :** Tester paiements crypto sur WAMP (localhost) AVANT d'avoir un hosting

---

## 🎯 **Votre Situation**

✅ **Projet prêt** : SMM Mastery fonctionnel sur WAMP64  
❌ **Pas de hosting** : Pas de domaine/hébergement pour le moment  
💰 **En attente de fonds** : Pas urgent, mais veux tester le système  
🧪 **Besoin** : Valider que le système crypto fonctionne AVANT de payer un hosting

---

## 💡 **3 Solutions pour Tester MAINTENANT**

### **Option 1 : Ngrok Tunnel (RECOMMANDÉ)** ⭐⭐⭐

**Permet de tester VRAIMENT les webhooks crypto sans hosting !**

#### **Comment ça marche ?**

```
WAMP (localhost) ← Ngrok Tunnel ← Internet ← NOWPayments IPN
```

Ngrok crée un tunnel temporaire qui expose votre WAMP local à Internet.

#### **Installation (5 minutes)**

**Étape 1 : Télécharger Ngrok**

```powershell
# Windows
1. Aller sur : https://ngrok.com/download
2. Télécharger Windows ZIP
3. Extraire dans C:\ngrok\
4. Créer compte gratuit : https://dashboard.ngrok.com/signup
5. Copier Auth Token
```

**Étape 2 : Configuration**

```powershell
# PowerShell
cd C:\ngrok
.\ngrok config add-authtoken VOTRE_TOKEN_ICI
```

**Étape 3 : Lancer le tunnel**

```powershell
# Exposer votre WAMP (port 80 par défaut)
.\ngrok http 80
```

**Résultat :**

```
Forwarding   https://abc123.ngrok.io → http://localhost:80
```

#### **Configuration SMM Mastery avec Ngrok**

**1. Mettre à jour `config.php`**

```php
// Ajouter en haut de config.php (temporaire pour tests)
if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    define('SITE_URL', 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'] . '/smm');
} else {
    define('SITE_URL', 'http://localhost/smm');
}
```

**2. Configuration NOWPayments**

```
Dashboard NOWPayments → Settings → IPN Callback URL
└── https://abc123.ngrok.io/smm/payment/crypto-ipn.php
    (Remplacer abc123 par votre URL ngrok)
```

**3. Tester un paiement**

```
1. Ngrok tunnel actif : .\ngrok http 80
2. Créer dépôt crypto sur SMM Mastery
3. Payer avec crypto réel (ou testnet si supporté)
4. NOWPayments → Envoie IPN → Ngrok tunnel → WAMP → crédit balance
5. Vérifier logs : payment/logs/crypto_ipn.log
```

#### **Avantages Ngrok**

```
✅ Gratuit (avec limite 1 tunnel simultané)
✅ HTTPS automatique (SSL inclus)
✅ Teste VRAIMENT les webhooks
✅ Pas besoin d'hosting
✅ URL change à chaque restart (mais OK pour tests)
```

#### **Limites Ngrok (Plan Gratuit)**

```
⚠️ URL change à chaque restart (reconfigurer IPN URL)
⚠️ 40 connexions/minute max
⚠️ Tunnel expire après 2h d'inactivité
💡 Solution : Ngrok Pro (8 USD/mois) = URL fixe + illimité
```

---

### **Option 2 : Mode Simulation (NO WEBHOOKS)** ⭐⭐

**Créer un système de crédit crypto MANUEL pour tester SANS webhooks.**

#### **Comment ça marche ?**

```
Admin crée transaction "pending" → Admin la marque "confirmed" manuellement → Balance créditée
```

Je vais créer une page admin spéciale pour simuler des paiements crypto.

#### **Fichier à créer : `admin/simulate-crypto-payment.php`**

**Fonctionnalités :**

```
1. Formulaire admin : User ID + Montant + Gateway
2. Créer transaction "pending" (simule création paiement)
3. Bouton "Simulate IPN Confirmation" → crédit balance
4. Teste TOUTE la logique (bonus, logs, etc.) SANS vrai paiement
```

**Avantages :**

```
✅ 100% local (aucun service externe)
✅ Teste logique de crédit
✅ Pas besoin d'Internet
✅ Contrôle total du flow
```

**Inconvénients :**

```
⚠️ Ne teste PAS les webhooks réels
⚠️ Ne teste PAS la communication avec gateway
⚠️ Manquent validations HMAC
```

---

### **Option 3 : LocalTunnel (Alternative Gratuite à Ngrok)** ⭐⭐

**Comme Ngrok mais 100% gratuit, sans limite.**

#### **Installation**

```powershell
# Nécessite Node.js : https://nodejs.org/
npm install -g localtunnel

# Lancer tunnel
lt --port 80 --subdomain smm-mastery-test
```

**Résultat :**

```
Your URL: https://smm-mastery-test.loca.lt
```

#### **Configuration SMM Mastery**

```
IPN Callback URL : https://smm-mastery-test.loca.lt/smm/payment/crypto-ipn.php
```

#### **Avantages vs Ngrok**

```
✅ 100% gratuit
✅ Subdomain personnalisé (--subdomain)
✅ Pas de limite connexions
✅ Pas d'expiration
```

#### **Inconvénients**

```
⚠️ Moins stable que Ngrok
⚠️ Premier accès = page interstitielle (cliquer "Continue")
⚠️ Peut être bloqué par certains pays
```

---

## 🎯 **Ma Recommandation pour VOUS**

### **Phase 1 : MAINTENANT (Développement)**

**Utiliser Mode Simulation (Option 2)**

```
Pourquoi ?
├── Pas besoin d'Internet
├── Teste toute la logique métier
├── Gratuit et contrôlé
├── Parfait pour finaliser le code
└── En attendant d'avoir des fonds pour hosting
```

**Je crée pour vous :**

1. ✅ Page `admin/simulate-crypto-payment.php`
2. ✅ Simulation complète du flow crypto
3. ✅ Boutons admin pour tester chaque étape
4. ✅ Logs détaillés de chaque action

### **Phase 2 : Tests Finaux (Avant Production)**

**Utiliser Ngrok (Option 1)**

```
Quand ?
├── Code finalisé
├── Prêt à tester avec vrais webhooks
├── Besoin de valider intégration NOWPayments
└── 1-2 jours avant mise en production
```

**Actions :**

1. Installer Ngrok (gratuit)
2. Lancer tunnel : `ngrok http 80`
3. Configurer IPN NOWPayments avec URL ngrok
4. Faire 1-2 paiements test (5-10 USD)
5. Valider que TOUT fonctionne

### **Phase 3 : Production (Quand fonds disponibles)**

**Hosting + Domaine**

```
Recommandations :
├── Hostinger : 2-5 EUR/mois (bon rapport qualité/prix)
├── Contabo : 5 EUR/mois (performant)
├── DigitalOcean : 6 USD/mois (droplet)
└── OVH : 3-7 EUR/mois (VPS ou shared)
```

**Migration :**

1. Upload code vers hosting
2. Configurer domaine DNS
3. Mettre à jour IPN URLs (NOWPayments, PayPal, etc.)
4. Tests finaux sur domaine réel
5. Lancement ! 🚀

---

## 🛠️ **Créer Page Simulation Crypto (Option 2)**

**Interface admin simple :**

```php
┌─────────────────────────────────────────┐
│ 🧪 Simulate Crypto Payment              │
├─────────────────────────────────────────┤
│ User:        [Dropdown users]           │
│ Amount:      [___] USD                  │
│ Gateway:     [NOWPayments ▼]            │
│ Currency:    [USDT ▼]                   │
│                                         │
│ [1️⃣ Create Pending Payment]            │
│                                         │
│ Status: ⏳ Pending...                   │
│                                         │
│ [2️⃣ Simulate IPN Confirmation]         │
│                                         │
│ ✅ Payment confirmed! Balance credited │
│ User balance: $10.00 → $15.50          │
│ (Amount: $5.00 + Bonus: $0.50)         │
└─────────────────────────────────────────┘
```

---

## 📊 **Comparatif Solutions**

| Critère              | Simulation | Ngrok        | LocalTunnel | Hosting       |
| -------------------- | ---------- | ------------ | ----------- | ------------- |
| **Coût**             | Gratuit    | Gratuit\*    | Gratuit     | 3-10 EUR/mois |
| **Setup**            | 5 min      | 10 min       | 5 min       | 1-2 jours     |
| **Webhooks réels**   | ❌         | ✅           | ✅          | ✅            |
| **Internet requis**  | ❌         | ✅           | ✅          | ✅            |
| **URL stable**       | N/A        | ❌ (Pro: ✅) | ⚠️          | ✅            |
| **Bon pour dev**     | ✅✅✅     | ✅✅         | ✅✅        | ✅            |
| **Production ready** | ❌         | ❌           | ❌          | ✅            |

\*Ngrok gratuit = 1 tunnel, limite 40 req/min, URL change à chaque restart

---

## 🎯 **Fichiers à créer pour Mode Simulation**

1. **`admin/simulate-crypto-payment.php`** - Interface admin
2. **Helper dans `functions.php`** - `simulateCryptoPayment($pdo, $user_id, $amount, $gateway)`
3. **Logs** - Tracer chaque action

---

**💡 ASTUCE :** Mode simulation = parfait pour développer MAINTENANT, Ngrok = valider avant prod, Hosting = lancement final !
