# 🔌 CONFIGURATION API SMMFOLLOWS - GUIDE COMPLET

## ✅ CE QUI A ÉTÉ CRÉÉ

### Nouvelles pages admin :
1. ✅ `admin/dashboard.php` - Dashboard admin avec stats
2. ✅ `admin/settings.php` - Configuration de l'API et paramètres
3. ✅ `admin/api-test.php` - Page de test complète de l'API
4. ✅ `admin/sync-services.php` - Synchronisation manuelle des services
5. ✅ `admin/sidebar.php` - Navigation admin

---

## 🚀 CONFIGURATION RAPIDE (5 MINUTES)

### Étape 1 : Accéder à l'Admin (1 min)

1. **Connectez-vous** avec votre compte admin
   ```
   http://localhost/smm/auth/login.php
   Username: admin
   Password: Admin@123
   ```

2. **Accédez au panel admin**
   ```
   http://localhost/smm/admin/dashboard.php
   ```

### Étape 2 : Configurer l'API Key (2 min)

1. **Cliquez sur "Paramètres"** dans la sidebar admin
   ```
   http://localhost/smm/admin/settings.php
   ```

2. **Entrez votre API Key SMMFollows**
   ```
   API Key : ee754a1d73166198cc2b1a9ff0d8502f
   ```

3. **Cliquez sur "💾 Sauvegarder les paramètres"**

### Étape 3 : Tester la Connexion (1 min)

1. **Allez sur la page de test**
   ```
   http://localhost/smm/admin/api-test.php
   ```

2. **Cliquez sur "Tester GetBalance()"**
   - ✅ Si ça marche : Vous verrez votre solde SMMFollows
   - ❌ Si ça échoue : Vérifiez votre API Key

3. **Cliquez sur "Tester GetServices()"**
   - ✅ Si ça marche : Vous verrez les 5 premiers services
   - ❌ Si ça échoue : Problème de connexion API

### Étape 4 : Synchroniser les Services (1 min)

1. **Allez sur Synchronisation**
   ```
   http://localhost/smm/admin/sync-services.php
   ```

2. **Cliquez sur "🚀 Lancer la synchronisation"**
   - Attendez 1-2 minutes
   - Vous verrez le résumé avec les nouveaux services

3. **Vérifiez les services**
   ```
   http://localhost/smm/services/index.php
   ```

---

## 🧪 TESTS À FAIRE

### Test 1 : Balance (Solde)
```
1. Admin > Test API > Test 2
2. Cliquez "Tester GetBalance()"
3. Résultat attendu : {"success":true,"balance":"XX.XX"}
```

### Test 2 : Services
```
1. Admin > Test API > Test 1
2. Cliquez "Tester GetServices()"
3. Résultat attendu : Liste de 5 services avec prix, min, max
```

### Test 3 : Synchronisation
```
1. Admin > Sync Services
2. Cliquez "Lancer"
3. Résultat attendu : X services synchronisés
4. Vérifiez sur Services > Liste
```

### Test 4 : Création Commande (Test Réel ⚠️)
```
⚠️ ATTENTION : Ceci va créer une VRAIE commande

1. Admin > Test API > Test 4
2. Entrez :
   - Service ID : (récupérez un ID depuis les services)
   - Link : https://instagram.com/test
   - Quantity : 100 (quantité minimum)
3. Confirmez
4. Si ça marche : Vous recevrez un Order ID
```

---

## 📊 COMPRENDRE LES RÉSULTATS

### ✅ Connexion Réussie
```json
{
  "success": true,
  "message": "Solde récupéré avec succès",
  "balance": "50.00"
}
```
✅ Votre API fonctionne parfaitement !

### ❌ Erreur API Key Invalide
```json
{
  "success": false,
  "message": "Erreur API",
  "error": "Incorrect API key"
}
```
❌ Vérifiez votre API Key dans Paramètres

### ❌ Erreur de Connexion
```json
{
  "success": false,
  "message": "Erreur API",
  "error": "Could not connect to..."
}
```
❌ Problème réseau ou API SMMFollows indisponible

---

## 🎯 FONCTIONNALITÉS DE L'API

### Fonctions Implémentées

1. **getServices()** ✅
   - Récupère tous les services disponibles
   - Retourne : ID, nom, prix, min, max, type

2. **getBalance()** ✅
   - Récupère votre solde SMMFollows
   - Retourne : Montant en USD

3. **createOrder()** ✅
   - Crée une nouvelle commande
   - Paramètres : service_id, link, quantity
   - Retourne : Order ID

4. **getOrderStatus()** ✅
   - Vérifie le statut d'une commande
   - Paramètre : order_id
   - Retourne : Statut, charge, start_count, remains

5. **createRefill()** ✅
   - Demande un refill pour une commande
   - Paramètre : order_id
   - Retourne : Refill ID

### Fonctions de Mapping

6. **mapCategory()** ✅
   - Détermine la catégorie automatiquement
   - Retourne : "Instagram Followers", "YouTube Views", etc.

7. **extractPlatform()** ✅
   - Extrait la plateforme du nom
   - Retourne : "Instagram", "YouTube", "TikTok", etc.

8. **determineTier()** ✅
   - Calcule le tier selon le prix
   - Retourne : "budget", "standard", "premium", "ultimate"

9. **calculateSellPrice()** ✅
   - Calcule le prix de vente avec marge
   - Budget: x5, Standard: x2.5, Premium: x2, Ultimate: x1.5

---

## 📋 STRUCTURE DE LA BASE DE DONNÉES

### Services Synchronisés

Chaque service SMMFollows est enregistré dans votre BDD avec :

```sql
- provider_id : ID sur SMMFollows
- provider_name : "SMMFollows"
- category : "Instagram Followers", "YouTube Views", etc.
- platform : "Instagram", "YouTube", etc.
- name : Nom complet du service
- tier : "budget", "standard", "premium", "ultimate"
- cost_price : Prix d'achat (pour 1000)
- sell_price : Prix de vente (pour 1000) [AUTOMATIQUE]
- drop_rate : "No Drop", "Low Drop", "High Drop"
- refill_days : 0, 30, 60, 90, 365
- min_quantity : Quantité minimum
- max_quantity : Quantité maximum
- is_active : 1 (actif) ou 0 (désactivé)
```

---

## 💰 CALCUL DES MARGES

### Marges Automatiques par Tier

**Budget Tier** (Services < $1.50/1K)
```
Prix d'achat : $0.50/1K
Marge : x5 (500%)
Prix de vente : $2.50/1K
Profit : $2.00/1K
```

**Standard Tier** (Services $1.50-$8/1K)
```
Prix d'achat : $4.00/1K
Marge : x2.5 (250%)
Prix de vente : $10.00/1K
Profit : $6.00/1K
```

**Premium Tier** (Services $8-$25/1K)
```
Prix d'achat : $15.00/1K
Marge : x2 (200%)
Prix de vente : $30.00/1K
Profit : $15.00/1K
```

**Ultimate Tier** (Services > $25/1K)
```
Prix d'achat : $35.00/1K
Marge : x1.5 (150%)
Prix de vente : $52.50/1K
Profit : $17.50/1K
```

---

## 🔄 SYNCHRONISATION AUTOMATIQUE

### Option 1 : CRON Job (Recommandé)

Ajoutez dans votre crontab :
```bash
# Synchroniser toutes les 6 heures
0 */6 * * * php D:/wamp64/www/smm/cron/sync-services.php
```

### Option 2 : Manuel

Via l'interface admin :
```
Admin > Sync Services > Lancer
```

### Fréquence Recommandée

- **Production** : Toutes les 6 heures
- **Test/Dev** : 1 fois par jour

---

## 🛠️ DÉPANNAGE

### Problème : "API Key non configurée"

**Solution :**
```
1. Admin > Paramètres
2. Entrez votre API Key : ee754a1d73166198cc2b1a9ff0d8502f
3. Sauvegardez
```

### Problème : "Services vides"

**Solution :**
```
1. Admin > Test API
2. Testez GetServices()
3. Si ça marche, lancez Sync Services
4. Si ça échoue, vérifiez l'API Key
```

### Problème : "Insufficient funds"

**Solution :**
```
1. Connectez-vous sur smmfollows.com
2. Ajoutez du solde (minimum $10-20)
3. Retestez
```

### Problème : "Invalid link"

**Solution :**
```
Le lien doit :
- Être un URL valide (https://...)
- Pointer vers un profil public
- Respecter le format de la plateforme
```

---

## 📞 RESSOURCES

### Documentation Officielle
- **API Docs** : https://smmfollows.com/api
- **Mon Compte** : https://smmfollows.com/account
- **Support** : https://smmfollows.com/support

### Vos Accès
- **API URL** : https://smmfollows.com/api/v2
- **API Key** : ee754a1d73166198cc2b1a9ff0d8502f
- **Méthode** : POST
- **Format** : JSON

---

## ✅ CHECKLIST CONFIGURATION

Avant de passer à l'étape suivante :

- [ ] ✅ Admin Panel accessible
- [ ] ✅ API Key configurée dans Paramètres
- [ ] ✅ Test GetBalance() réussi
- [ ] ✅ Test GetServices() réussi
- [ ] ✅ Synchronisation lancée et terminée
- [ ] ✅ Services visibles dans la liste
- [ ] ✅ Test création commande (optionnel mais recommandé)

---

## 🎉 PROCHAINES ÉTAPES

Maintenant que l'API fonctionne :

1. **Tester une vraie commande**
   - Choisissez un service pas cher
   - Passez une commande de test
   - Vérifiez le tracking

2. **Configurer les paiements**
   - PayPal Business
   - Stripe (optionnel)

3. **Personnaliser le site**
   - Logo
   - Couleurs
   - Textes

4. **Lancer le marketing**
   - Réseaux sociaux
   - SEO
   - Publicités

---

**🎊 FÉLICITATIONS !**

Votre API SMMFollows est maintenant connectée et fonctionnelle !
Vous pouvez commencer à vendre des services ! 💰

---

**Date** : 11 Octobre 2025
**API Key** : ee754a1d73166198cc2b1a9ff0d8502f
**Statut** : ✅ PRÊT À UTILISER
