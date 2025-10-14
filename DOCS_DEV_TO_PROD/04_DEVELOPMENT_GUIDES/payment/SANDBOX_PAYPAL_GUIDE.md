# 🧪 GUIDE COMPLET - SANDBOX PAYPAL

**Date :** 11 Octobre 2025  
**Objectif :** Tester les paiements gratuitement avec argent fictif

---

## 🎯 POURQUOI UTILISER LE SANDBOX ?

### **Le problème :**

- ❌ On ne peut pas payer vers son propre compte PayPal
- ❌ On ne peut pas utiliser la même carte sur 2 comptes
- ❌ Les vrais paiements coûtent de l'argent

### **La solution : SANDBOX**

- ✅ Argent fictif illimité
- ✅ Tests gratuits à l'infini
- ✅ Simulation réaliste de PayPal
- ✅ Aucun risque financier

---

## 📝 ÉTAPE 1 : CRÉER UN COMPTE DÉVELOPPEUR (5 MIN)

### **1. Aller sur le site développeur PayPal**

```
URL : https://developer.paypal.com/
```

### **2. Se connecter**

- Cliquer sur **"Log in to Dashboard"** (en haut à droite)
- Utiliser votre **vrai compte PayPal** pour vous connecter
- Accepter les conditions développeur

### **3. Vérifier que vous êtes connecté**

Vous devriez voir :

- Dashboard développeur
- Menu avec "Sandbox" > "Accounts"

---

## 🏦 ÉTAPE 2 : CRÉER DES COMPTES SANDBOX (5 MIN)

PayPal créé automatiquement 2 comptes de test pour vous :

1. **Compte Business** (pour recevoir l'argent)
2. **Compte Personnel** (pour payer)

### **1. Accéder aux comptes Sandbox**

```
Dashboard > Sandbox > Accounts
URL : https://developer.paypal.com/dashboard/accounts
```

### **2. Vérifier les comptes créés**

Vous devriez voir 2 comptes :

**Compte 1 : BUSINESS (Marchand)**

- Type : Business
- Email : `sb-xxxxx@business.example.com`
- Balance : $5,000.00 (fictif)
- **C'est ce compte qui recevra les paiements**

**Compte 2 : PERSONAL (Client)**

- Type : Personal
- Email : `sb-yyyyy@personal.example.com`
- Balance : $5,000.00 (fictif)
- **C'est ce compte qui fera les paiements**

### **3. Noter les informations importantes**

Pour chaque compte, cliquer sur les **"..."** puis **"View/Edit account"** :

**Compte BUSINESS :**

```
Email : sb-xxxxx@business.example.com
Password : (visible dans les détails)
```

**Compte PERSONAL :**

```
Email : sb-yyyyy@personal.example.com
Password : (visible dans les détails)
```

⚠️ **IMPORTANT : Notez bien ces informations !**

---

## ⚙️ ÉTAPE 3 : CONFIGURER VOTRE SITE (2 MIN)

### **1. Aller dans Admin > Paramètres**

```
http://localhost/smm/admin/settings.php
```

### **2. Section "💳 PayPal"**

**Configuration Sandbox :**

```
Email PayPal Business : sb-xxxxx@business.example.com
                       ↑ Votre email BUSINESS Sandbox

Mode PayPal : Sandbox (Test)
             ↑ IMPORTANT : Mode Sandbox !
```

### **3. Sauvegarder**

Cliquer sur **"💾 Sauvegarder les paramètres"**

---

## 🧪 ÉTAPE 4 : TESTER UN PAIEMENT (3 MIN)

### **1. Aller sur la page de recharge**

```
http://localhost/smm/dashboard/balance.php
```

### **2. Entrer un montant de test**

```
Montant : 10.00$ (ou n'importe quel montant)
```

### **3. Cliquer sur "Payer avec PayPal"**

### **4. Sur la page PayPal Sandbox**

Vous serez redirigé vers : `https://www.sandbox.paypal.com`

**Connexion avec le compte PERSONAL (client) :**

```
Email : sb-yyyyy@personal.example.com
Password : [le mot de passe noté]
```

### **5. Confirmer le paiement**

- Vérifier le montant : 10.00 USD
- Cliquer sur **"Payer maintenant"**
- Vous serez redirigé vers votre site

### **6. Vérifier le crédit (Manuel sur localhost)**

**IMPORTANT :** Sur localhost, le crédit ne se fait PAS automatiquement !

**2 solutions :**

**Solution A : test-payment.php**

```
http://localhost/smm/payment/test-payment.php
→ Entrer 10$ → Créditer
```

**Solution B : Admin**

```
Admin > Utilisateurs > Votre compte > 💰 Solde
→ Ajouter 10$
```

---

## 📊 VÉRIFIER QUE ÇA FONCTIONNE

### **1. Vérifier le paiement sur PayPal Sandbox**

Se connecter au compte BUSINESS :

```
URL : https://www.sandbox.paypal.com
Email : sb-xxxxx@business.example.com
Password : [mot de passe]
```

Vous devriez voir :

- ✅ Transaction reçue de 10.00 USD
- ✅ Balance augmentée
- ✅ Détails de la transaction

---

## 🔄 PROCESSUS COMPLET

```
┌─────────────────────────────────────────────────────┐
│  1. CLIENT entre 10$ sur votre site                │
│     → Clic "Payer avec PayPal"                      │
└─────────────────┬───────────────────────────────────┘
                  │
                  ↓
┌─────────────────────────────────────────────────────┐
│  2. Redirection vers sandbox.paypal.com             │
│     → Client se connecte (compte PERSONAL)          │
│     → Confirme le paiement de 10$                   │
└─────────────────┬───────────────────────────────────┘
                  │
                  ↓
┌─────────────────────────────────────────────────────┐
│  3. PayPal transfère vers compte BUSINESS           │
│     → Compte Business reçoit 10$                    │
│     → (En production : IPN crédite automatiquement) │
└─────────────────┬───────────────────────────────────┘
                  │
                  ↓
┌─────────────────────────────────────────────────────┐
│  4. Sur LOCALHOST : Crédit manuel nécessaire        │
│     → Utiliser test-payment.php OU                  │
│     → Admin > Utilisateurs > Ajuster solde          │
└─────────────────────────────────────────────────────┘
```

---

## 💡 AVANTAGES DU SANDBOX

| Fonctionnalité        | Sandbox              | Live                 |
| --------------------- | -------------------- | -------------------- |
| **Coût**              | Gratuit ✅           | Réel 💰              |
| **Argent**            | Fictif               | Vrai                 |
| **Tests**             | Illimités ✅         | Coûteux              |
| **Cartes**            | Pas besoin ✅        | Nécessaire           |
| **IPN sur localhost** | Ne fonctionne pas ⚠️ | Ne fonctionne pas ⚠️ |
| **IPN en production** | Fonctionne ✅        | Fonctionne ✅        |

---

## 🚨 LIMITATIONS SUR LOCALHOST

### **Ce qui NE marche PAS :**

❌ IPN (notifications automatiques)  
❌ Crédit automatique du solde

### **Pourquoi ?**

PayPal ne peut pas envoyer de notifications vers `localhost` (pas accessible depuis Internet)

### **Solutions :**

1. ✅ Utiliser `test-payment.php` pour créditer manuellement
2. ✅ Créditer depuis Admin > Utilisateurs
3. ✅ Déployer sur un vrai serveur (IPN marchera)

---

## 🚀 EN PRODUCTION (Plus tard)

Quand vous déployez sur un vrai hébergement :

### **Configuration Production :**

```
Email PayPal : votre_vrai@email.com
Mode PayPal : Live (Production)
URL IPN : https://votredomaine.com/payment/paypal-ipn.php
```

### **Résultat :**

- ✅ Vrais paiements
- ✅ IPN fonctionne automatiquement
- ✅ Crédits automatiques
- ✅ Tout est automatisé !

---

## 📋 CHECKLIST RAPIDE

Pour tester maintenant avec Sandbox :

- [ ] Créer compte développeur sur developer.paypal.com
- [ ] Vérifier les 2 comptes Sandbox (Business + Personal)
- [ ] Noter les emails et mots de passe
- [ ] Configurer le site (Admin > Settings)
  - [ ] Email = compte BUSINESS sandbox
  - [ ] Mode = Sandbox
- [ ] Tester un paiement
  - [ ] Entrer montant sur balance.php
  - [ ] Se connecter avec compte PERSONAL
  - [ ] Confirmer le paiement
- [ ] Créditer manuellement (test-payment.php ou Admin)
- [ ] Vérifier la transaction sur sandbox.paypal.com

---

## 🆘 PROBLÈMES FRÉQUENTS

### **"Je ne vois pas mes comptes Sandbox"**

→ Aller sur : https://developer.paypal.com/dashboard/accounts
→ Si vide, cliquer sur "Create Account" pour créer manuellement

### **"Le paiement échoue"**

→ Vérifier que Mode = Sandbox dans settings
→ Vérifier que vous utilisez l'email BUSINESS sandbox

### **"Le solde n'est pas crédité"**

→ Normal sur localhost ! Utiliser test-payment.php

### **"J'ai oublié le mot de passe Sandbox"**

→ Dashboard > Sandbox > Accounts > ... > View/Edit
→ Le mot de passe est visible

---

## 💬 RÉCAPITULATIF

**Pour développer et tester :**

1. ✅ Utiliser Sandbox PayPal (gratuit, argent fictif)
2. ✅ Tester les paiements autant que vous voulez
3. ✅ Créditer manuellement avec test-payment.php

**En production (plus tard) :**

1. ✅ Passer en mode Live
2. ✅ Utiliser votre vrai compte PayPal
3. ✅ IPN créditera automatiquement

---

## 🎉 RÉSULTAT ATTENDU

Avec le Sandbox, vous pourrez :

- ✅ Tester les paiements gratuitement
- ✅ Simuler de vrais clients
- ✅ Vérifier tout le processus
- ✅ Développer tranquillement
- ✅ Déployer quand prêt !

---

**Questions ? Besoin d'aide pour configurer ? Dites-moi ! 🚀**

---

**Fichier créé automatiquement - SMM Mastery v1.0**
