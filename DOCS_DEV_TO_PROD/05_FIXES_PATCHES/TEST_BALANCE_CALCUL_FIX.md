# ⚡ TEST EXPRESS - Balance After Fix

**Version:** V3.2.2  
**Temps:** 1 minute  
**Criticité:** 🔴 URGENT (Bloque toutes les commandes)

---

## 🎯 BUG FIXÉ

**AVANT:** Prix × 1000 au lieu de ÷ 1000  
**APRÈS:** Calcul correct du prix total

---

## 🧪 TEST EN 30 SECONDES

### 1. Rafraîchir
```
Ctrl + Shift + R (hard refresh)
```

### 2. Ouvrir Modal
- Clique **Buy** sur n'importe quel service
- Note le **Current Balance** en haut

### 3. Test Calcul Simple

**Service:** Instagram Followers ($10/1K)  
**Quantity:** 5000

**Résultat Attendu:**
```
Total Charge: $50.00 ✅ (PAS $50000.00)
Balance After: [Current Balance - 50.00] ✅
```

**Si tu vois $50000.00 → LE BUG EST ENCORE LÀ! ❌**

---

## 📊 EXEMPLES RAPIDES

| Quantity | Price/1K | Total Attendu |
|----------|----------|---------------|
| 1000     | $10.00   | $10.00        |
| 5000     | $10.00   | $50.00        |
| 10000    | $10.00   | $100.00       |
| 5000     | $0.50    | $2.50         |
| 100      | $8.50    | $0.85         |

**Formule:** `(Quantity × Price) / 1000`

---

## ✅ SUCCESS CRITERIA

1. ✅ Total Charge = (Quantity × Price) / 1000
2. ✅ Balance After = Current Balance - Total Charge
3. ✅ Bouton actif si Balance After ≥ 0
4. ✅ Bouton "Insufficient Balance" si Balance After < 0

---

## 🐛 SI PROBLÈME PERSISTE

### Vérifier Cache
```powershell
# Vider cache navigateur
Ctrl + Shift + Delete → Clear cache

# Ou F12 → Network → Disable cache (checkbox)
```

### Console Logs
```
F12 → Console → Chercher:
"💰 Calculating charge:"
"💰 Balance calculation:"
```

**Valeurs à vérifier:**
- `totalCharge` doit être petit (ex: 50.00)
- `balanceAfter` doit être proche de `currentBalance`

---

## 📞 ROLLBACK SI NÉCESSAIRE

```powershell
cd D:\wamp64\www\smm\services
git checkout order-modal.js
```

Ou restaure manuellement:
```javascript
// Ancienne version (bugée mais simple)
calculatePrecisePrice(quantity, pricePerK) {
    return (quantity * pricePerK) / 1000;
}
```

---

**🎯 GO! Teste maintenant et confirme que $50.00 au lieu de $50000.00!**
