# 🧪 TEST RAPIDE - Balance Calculation Fix

**Version:** V3.2.2  
**Temps:** 2 minutes  
**Objectif:** Vérifier que Balance After = Current Balance - Total Charge

---

## ⚡ TEST EN 30 SECONDES

### Pré-requis

✅ Connecté avec un compte ayant un solde > 0  
✅ Modal order accessible depuis services/index.php

### Étapes

1. **Vérifier ton solde actuel**

   - Regarde le top-bar (coin haut-droite)
   - Note le montant (ex: $50.00)

2. **Ouvrir le modal**

   - Clique "Buy" sur n'importe quel service
   - Modal s'ouvre avec onglet "New Order"

3. **Vérifier l'affichage**

   ```
   Total Charge: $X.XX

   Current balance: $50.00  ← Doit correspondre au top-bar
   Balance after: $XX.XX    ← Doit être = Current - Total
   ```

4. **Changer la quantité**

   - Augmente/diminue la quantity
   - Observe: Balance After se met à jour en temps réel

5. **Tester solde insuffisant**
   - Mets une quantity énorme (ex: 100000)
   - Balance After devient rouge et négatif
   - Bouton change: "Insufficient Balance" (désactivé)

---

## ✅ SUCCESS CRITERIA

| Test                            | Attendu                         | Status |
| ------------------------------- | ------------------------------- | ------ |
| Current balance affiché         | ✅ Montant identique au top-bar | ⬜     |
| Balance after correct           | ✅ = Current - Total Charge     | ⬜     |
| Couleur verte si positif        | ✅ Balance after > 0 → vert     | ⬜     |
| Couleur rouge si négatif        | ✅ Balance after < 0 → rouge    | ⬜     |
| Bouton désactivé si insuffisant | ✅ "Insufficient Balance"       | ⬜     |
| Console log visible             | ✅ "💵 Balance info: {...}"     | ⬜     |

---

## 🔍 DEBUG SI PROBLÈME

### "Current balance affiche $0.00"

**Cause:** Élément `.balance-amount` non trouvé

**Solution:**

1. Ouvre DevTools (F12)
2. Console → cherche: `⚠️ Balance element not found`
3. Vérifie que `includes/dashboard-top-bar.php` est inclus
4. Inspect top-bar: doit contenir `<span class="balance-amount">$XX.XX</span>`

### "Balance after incorrect"

**Cause:** Parsing du format currency échoue

**Solution:**

1. Console → cherche log: `💵 Balance info:`
2. Vérifie que `parsed` correspond au vrai solde
3. Si `text` contient virgules (ex: "$1,234.56"), vérifie regex `/[$,]/g`

### "Console log ne s'affiche pas"

**Cause:** Fonction `updateCharge()` pas appelée

**Solution:**

1. Change la quantity
2. Si rien → vérifie event listener sur `#orderQuantity`
3. Vérifie dans JS: `document.getElementById('orderQuantity').addEventListener('input', ...)`

---

## 📸 CAPTURE D'ÉCRAN AVANT/APRÈS

### ❌ AVANT (V3.2.1)

```
┌─────────────────────────────────┐
│ Total Charge                    │
│ $5.00                           │
│                                 │
│ Balance after: $0.00 ← FAUX!    │
└─────────────────────────────────┘
```

### ✅ APRÈS (V3.2.2)

```
┌─────────────────────────────────┐
│ Total Charge                    │
│ $5.00                           │
│                                 │
│ Current balance: $50.00 ← NEW!  │
│ Balance after: $45.00 ← CORRECT │
└─────────────────────────────────┘
```

---

## 🎯 SCÉNARIOS DE TEST

### Scénario 1: Solde Suffisant

```
Solde: $100.00
Service: Instagram Followers (min: 100, price: $5/1000)
Quantity: 500
Total: $2.50

✅ Attendu:
- Current balance: $100.00 (vert)
- Balance after: $97.50 (vert)
- Bouton: "Place Order" (actif)
```

### Scénario 2: Solde Limite

```
Solde: $10.00
Quantity qui coûte exactement $10.00

✅ Attendu:
- Current balance: $10.00 (vert)
- Balance after: $0.00 (vert)
- Bouton: "Place Order" (actif)
```

### Scénario 3: Solde Insuffisant

```
Solde: $5.00
Quantity qui coûte $15.00

✅ Attendu:
- Current balance: $5.00 (vert)
- Balance after: -$10.00 (ROUGE)
- Bouton: "Insufficient Balance" (désactivé, gris)
```

---

## 🐛 ISSUES CONNUES

### Issue 1: Virgules dans grands nombres

**Status:** ✅ RÉSOLU  
**Solution:** Regex `/[$,]/g` gère "$1,234.56" correctement

### Issue 2: Balance pas mis à jour après order

**Status:** ⚠️ À VÉRIFIER  
**Todo:** Après submit order, rafraîchir `.balance-amount` dans top-bar

### Issue 3: Décimales > 2

**Status:** ✅ RÉSOLU  
**Solution:** `formatPrice()` arrondit à 2 décimales max

---

## 🔄 REFRESH SI PROBLÈME

Si les changements ne sont pas visibles:

```powershell
# Hard refresh navigateur
Ctrl + Shift + R

# Ou vider cache
Ctrl + Shift + Delete → Clear cache
```

Si toujours pas:

```powershell
# Vérifier fichiers modifiés
cd D:\wamp64\www\smm\services
git diff order-modal.js
git diff order-modal.css
```

---

## ✅ VALIDATION FINALE

**Tous les tests passent?**

- ✅ Current balance affiché correctement
- ✅ Balance after calculé juste
- ✅ Couleurs dynamiques (vert/rouge)
- ✅ Bouton désactivé si insuffisant
- ✅ Console log visible
- ✅ Pas d'erreurs JS dans console

**🎉 FIX VALIDÉ! Balance calculation fonctionne parfaitement.**

---

**Questions? Voir:** `FIX_BALANCE_CALCULATION.md` (documentation complète)
