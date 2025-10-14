# 🧪 GUIDE DE TEST - VALIDATION MODAL V3.1

**Date:** 14 Octobre 2025  
**Version:** 3.1  
**Temps estimé:** 10-15 minutes

---

## 🎯 OBJECTIF DES TESTS

Valider que le bouton "Place Order" du modal de commande est correctement désactivé/activé selon les critères de validation.

---

## ⚙️ PRÉPARATION

### 1. **Recharger la page services**
```
URL: http://localhost/smm/services/
Action: Ctrl + F5 (hard refresh pour vider le cache JS/CSS)
```

### 2. **Vérifier le solde**
```
Top-bar → Balance: Noter le montant actuel
Exemple: $50.00
```

### 3. **Ouvrir la console développeur**
```
Chrome/Edge: F12
Firefox: F12
Console: Tab "Console"
```

---

## 🧪 SCÉNARIOS DE TEST

### ✅ **TEST 1: VALIDATION QUANTITÉ MINIMUM**

**Étapes:**
1. Cliquer sur "Buy" d'un service (ex: Instagram Followers)
2. Noter les limites Min/Max affichées
   - Exemple: Min: **1,000** | Max: **100,000**
3. Dans le champ **Quantity**, entrer: `500` (< minimum)
4. Observer le bouton "Place Order"

**✓ Résultat attendu:**
- Bouton **DÉSACTIVÉ** (rouge)
- Texte: **"⚠️ Min: 1K"**
- Cursor: `not-allowed`
- Console: `🔍 Form validation: { quantityValid: false, errors: [...] }`

**✓ Validation réussie si:**
- Impossible de cliquer sur le bouton
- Couleur rouge visible
- Message clair sur le minimum

---

### ✅ **TEST 2: VALIDATION QUANTITÉ MAXIMUM**

**Étapes:**
1. Même service qu'avant
2. Entrer quantité: `200000` (> maximum 100,000)
3. Observer le bouton

**✓ Résultat attendu:**
- Bouton **DÉSACTIVÉ** (rouge)
- Texte: **"⚠️ Max: 100K"**
- Console: `errors: ["Quantity cannot exceed 100000"]`

**✓ Correction:**
1. Entrer quantité valide: `50000`
2. Bouton devient **VERT**
3. Texte: **"✓ Place Order"**

---

### ✅ **TEST 3: VALIDATION QUANTITÉ DANS LES LIMITES**

**Étapes:**
1. Entrer quantité: `10000` (entre min et max)
2. Remplir champ **Link**: `https://instagram.com/test`
3. Observer le bouton

**✓ Résultat attendu:**
- Bouton **ACTIVÉ** (violet/bleu)
- Texte: **"✓ Place Order"**
- Effet hover fonctionne (lift + shadow)
- Console: `errors: []` (array vide)

---

### ✅ **TEST 4: VALIDATION SOLDE INSUFFISANT**

**Étapes:**
1. Choisir un service cher (ex: $5.00/1K)
2. Entrer quantité énorme: `1000000` (1M)
   - Coût calculé: $5,000.00
3. Si votre solde < $5,000:
4. Observer le bouton

**✓ Résultat attendu:**
- Bouton **DÉSACTIVÉ** (rouge)
- Texte: **"⚠️ Insufficient Balance"**
- "Balance after" affiché en **ROUGE** et **NÉGATIF**
- Console: `errors: ["Insufficient balance"]`

**✓ Validation du calcul:**
```
Current balance: $50.00
Total charge: $5,000.00
Balance after: -$4,950.00  ← EN ROUGE
```

---

### ✅ **TEST 5: VALIDATION LINK MANQUANT**

**Étapes:**
1. Effacer complètement le champ **Link**
2. Entrer quantité valide: `5000`
3. Observer le bouton

**✓ Résultat attendu:**
- Bouton **DÉSACTIVÉ**
- Texte: **"⚠️ Complete Form"**
- Console: `errors: ["Link/URL is required"]`

**✓ Correction:**
1. Taper dans Link: `ht` (2 caractères)
2. Bouton toujours **DÉSACTIVÉ** (< 3 caractères)
3. Taper: `http` (3+ caractères)
4. Bouton devient **ACTIVÉ** (si autres conditions OK)

---

### ✅ **TEST 6: VALIDATION DRIP-FEED**

**Étapes:**
1. Remplir Link et Quantity correctement
2. Cocher la case **"Enable Drip-feed"**
3. Laisser les champs Runs et Interval **VIDES**
4. Observer le bouton

**✓ Résultat attendu:**
- Bouton **DÉSACTIVÉ**
- Texte: **"⚠️ Check Drip-feed"**
- Console: `errors: ["Drip-feed: Runs must be at least 2", ...]`

**✓ Correction:**
1. Remplir **Runs**: `5`
2. Remplir **Interval**: `30`
3. Bouton devient **ACTIVÉ**

---

### ✅ **TEST 7: VALIDATION TEMPS RÉEL**

**Étapes:**
1. Ouvrir modal
2. Link: `https://test.com`
3. Modifier **Quantity progressivement** sans cliquer ailleurs:
   - Effacer tout → `0`
   - Observer bouton (désactivé)
   - Taper `5`
   - Observer bouton (désactivé si < min)
   - Taper `00` → `500`
   - Observer bouton (désactivé si < min)
   - Continuer: `5000`
   - Observer bouton (activé si valide)

**✓ Résultat attendu:**
- Bouton change d'état **EN TEMPS RÉEL**
- Pas besoin de cliquer ailleurs ou de soumettre
- Messages d'erreur changent instantanément

---

### ✅ **TEST 8: TENTATIVE DE BYPASS (SÉCURITÉ)**

**Étapes:**
1. Créer une commande **INVALIDE** (ex: quantité < min)
2. Bouton désactivé
3. Ouvrir la console développeur
4. Taper:
```javascript
document.getElementById('orderBtnSubmit').disabled = false;
```
5. Le bouton devient cliquable
6. **CLIQUER** sur "Place Order"

**✓ Résultat attendu:**
- **Alert rouge** apparaît: "Please complete all required fields correctly"
- **Pas de requête API** envoyée (visible dans Network tab)
- **Pas de redirection**
- Console: `⚠️ Form validation failed - cannot submit order`

**✓ Sécurité validée:**
- Même si le bouton est débloqué en JS, la fonction `submitOrder()` refuse de continuer
- Double validation fonctionne correctement

---

### ✅ **TEST 9: COMMANDE VALIDE (FIN À FIN)**

**Étapes:**
1. Remplir tous les champs correctement:
   - **Link**: `https://instagram.com/testuser`
   - **Quantity**: `5000` (valide)
   - **Drip-feed**: Décoché (ou configuré correctement)
2. Vérifier:
   - Solde suffisant
   - Quantité entre min/max
3. Bouton **VERT** "✓ Place Order"
4. **CLIQUER** sur le bouton

**✓ Résultat attendu:**
- Bouton change: **"⏳ Processing..."**
- Bouton désactivé pendant traitement
- Requête API envoyée (visible Network tab: `create-order.php`)
- Si succès:
  - **Alert vert** : "Order placed successfully!"
  - **Redirection** vers tracking page après 2 secondes
- Si erreur serveur:
  - **Alert rouge** avec message d'erreur
  - Bouton redevient "✓ Place Order"

---

## 📊 VÉRIFICATION CONSOLE

### **Messages attendus dans la console:**

#### ✅ **Lors de l'ouverture du modal:**
```javascript
💰 Calculating charge: { quantity: 1000, price: 0.0015, ... }
💵 Balance info: { parsed: 50.00, totalCharge: 1.50 }
🔍 Form validation: {
  link: '✗ Missing',
  quantity: 1000,
  quantityValid: true,
  balanceValid: true,
  errors: ['Link/URL is required']
}
```

#### ✅ **Lors du changement de quantité:**
```javascript
📊 Quantity changed: 5000
💰 Calculating charge: { quantity: 5000, ... }
🔍 Form validation: { errors: [] }  // Si valide
```

#### ✅ **Lors de la soumission valide:**
```javascript
📤 Submit button clicked
🔍 Form validation: { errors: [] }
// Puis requête API...
```

#### ✅ **Lors de la tentative de bypass:**
```javascript
📤 Submit button clicked
⚠️ Form validation failed - cannot submit order
```

---

## ❌ PROBLÈMES POSSIBLES & SOLUTIONS

### **PROBLÈME 1: Bouton ne change jamais d'état**

**Symptômes:**
- Bouton toujours désactivé
- Ou toujours activé

**Solutions:**
1. Hard refresh: `Ctrl + F5`
2. Vider cache navigateur
3. Vérifier console pour erreurs JS
4. Vérifier que `validateForm()` est appelée (console logs)

---

### **PROBLÈME 2: Balance not found**

**Symptômes:**
- Console: `⚠️ Balance element not found (.balance-amount)`

**Solutions:**
1. Vérifier que vous êtes connecté (top-bar visible)
2. Vérifier la structure HTML de la top-bar
3. Vérifier la classe CSS: `.balance-amount`

---

### **PROBLÈME 3: Min/Max non respectés**

**Symptômes:**
- Bouton activé alors que quantité < min
- Ou quantité > max

**Solutions:**
1. Vérifier les data attributes de la card:
   - `data-min-quantity`
   - `data-max-quantity`
2. Console: Vérifier `currentService.min_quantity` et `max_quantity`
3. Vérifier que les valeurs sont bien des **nombres** (pas des strings)

---

## 📝 CHECKLIST FINALE

Cocher chaque test réussi:

- [ ] ✅ Test 1: Quantité minimum validée
- [ ] ✅ Test 2: Quantité maximum validée
- [ ] ✅ Test 3: Quantité valide activée
- [ ] ✅ Test 4: Solde insuffisant détecté
- [ ] ✅ Test 5: Link manquant détecté
- [ ] ✅ Test 6: Drip-feed validé
- [ ] ✅ Test 7: Validation temps réel fonctionne
- [ ] ✅ Test 8: Bypass impossible (sécurité)
- [ ] ✅ Test 9: Commande valide soumise avec succès

---

## 🎉 VALIDATION COMPLÈTE

Si tous les tests sont **✓ RÉUSSIS**, la validation du modal est **100% fonctionnelle**.

**Prochaines étapes:**
- Tester sur mobile (responsive)
- Tester sur différents navigateurs
- Tester avec vrais services de production
- Monitorer les logs de soumission

---

**Date de test:** _______________  
**Testé par:** _______________  
**Navigateur:** _______________  
**Résultat:** ✅ SUCCÈS / ❌ ÉCHEC

---

**📖 Documentation complète:** `VALIDATION_ORDER_MODAL_V3.1.md`
