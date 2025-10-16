# 🧪 GUIDE DE TEST - WIDGET TRADUCTION v2.0

## 📋 Tests rapides à effectuer

### 1. **Test de chargement initial**
```
✅ Ouvrir http://localhost/smm/orders/history.php
✅ Ouvrir la console (F12)
✅ Vérifier les logs :
   - "[SMM Translate] Initialisation widget v2.0..."
   - "[SMM Translate] Widget initialisé ✅"
```

### 2. **Test du mode actuel**
```javascript
// Dans la console, taper :
SMM_TRANSLATE.state

// Résultat attendu :
{
  currentLang: "fr",
  isDropdownOpen: false,
  mode: "google" // ou "fallback"
  isGoogleAvailable: true // ou false
  googleCheckComplete: true
}
```

### 3. **Test changement de langue**
1. Cliquer sur le bouton de traduction (globe)
2. Vérifier le badge de mode (Google ou Fallback)
3. Sélectionner une langue (ex: Deutsch)
4. Observer :
   - Loader apparaît (3 secondes max)
   - Traduction s'effectue
   - Badge langue mis à jour

### 4. **Test mode fallback forcé**
```javascript
// Forcer le mode fallback
SMM_TRANSLATE.state.mode = 'fallback';
SMM_TRANSLATE.changeLanguage('en');

// La page devrait se recharger avec ?lang=en
```

### 5. **Test performance**
- Le widget doit détecter Google en < 3 secondes
- Si Google indisponible → Fallback automatique
- Logs console < 20 lignes

## 🔍 Vérification console

### Logs normaux (mode Google)
```
[SMM Translate] Initialisation widget v2.0...
[SMM Translate] Langue actuelle: fr
[SMM Translate] Widget initialisé ✅
[SMM Translate] Vérification Google Translate...
[SMM Translate] ✅ Google Translate disponible
```

### Logs normaux (mode fallback)
```
[SMM Translate] Initialisation widget v2.0...
[SMM Translate] Langue actuelle: fr
[SMM Translate] Widget initialisé ✅
[SMM Translate] Vérification Google Translate...
[SMM Translate] ⚠️ Google Translate non disponible, mode fallback
```

## ✅ Checklist finale

- [ ] Widget visible dans le header
- [ ] Dropdown s'ouvre au clic
- [ ] Liste des langues complète
- [ ] Recherche de langue fonctionne
- [ ] Badge de mode visible (Google/Fallback)
- [ ] Changement de langue < 3 secondes
- [ ] Pas d'erreurs console
- [ ] Logs propres (< 20 lignes)
- [ ] Responsive mobile OK

## 🚀 Commandes debug utiles

```javascript
// État complet
console.log(SMM_TRANSLATE);

// Changer langue manuellement
SMM_TRANSLATE.changeLanguage('es');

// Ouvrir/fermer dropdown
SMM_TRANSLATE.toggleDropdown();

// Vérifier mode
console.log('Mode:', SMM_TRANSLATE.state.mode);

// Forcer rechargement avec langue
window.location.href = '?lang=de';
```

## ⚠️ Si problème

1. **Widget invisible** → Vérifier includes dans la page
2. **Google ne charge pas** → Normal, fallback automatique
3. **Dropdown ne s'ouvre pas** → Vérifier z-index CSS
4. **Traduction lente** → Mode fallback nécessite rechargement

---

**Test effectué le :** ___________  
**Par :** ___________  
**Résultat :** ✅ OK / ❌ KO
