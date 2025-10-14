# ⚡ ACTIONS RAPIDES - Modal V3

## 🎯 TL;DR (30 secondes)

**AVANT:** Share ne marchait pas ❌ + Modal pas responsive ❌  
**APRÈS:** Share = 1 clic + Toast ✅ + Modal 100% responsive ✅

---

## 🚀 TEST IMMÉDIAT (2 minutes)

### **Étape 1: Rafraîchir** (5 sec)

```
Ctrl + Shift + R
```

### **Étape 2: Tester Share** (30 sec)

```
1. Ouvrir modal (Buy)
2. Cliquer "Copy Link" (bouton vert)
3. Voir toast vert ✅
4. Coller (Ctrl+V)
5. Vérifier URL: ?service=9397
```

### **Étape 3: Tester Responsive** (1 min)

```
1. F12 (DevTools)
2. Ctrl+Shift+M (Mode mobile)
3. Choisir iPhone
4. Ouvrir modal
5. Vérifier footer vertical
```

---

## 📱 BREAKPOINTS (mémo rapide)

| Taille     | Layout                                   |
| ---------- | ---------------------------------------- |
| < 480px    | 📱 Mobile XS - Footer vertical, Tabs 2x2 |
| 480-767px  | 📱 Mobile - Footer vertical              |
| 768-1023px | 💻 Tablet - Footer horizontal            |
| 1024px+    | 🖥️ Desktop - Max 1200px                  |

---

## ✅ CHECKLIST EXPRESS

- [ ] Share fonctionne (desktop)
- [ ] Toast apparaît
- [ ] Modal responsive mobile
- [ ] Footer vertical mobile
- [ ] Footer horizontal desktop

---

## 🐛 SI PROBLÈME

### Share ne fonctionne pas:

```javascript
// Console
navigator.clipboard.writeText("test");
```

### Toast invisible:

```javascript
// Console
window.orderModal.showToast("Test", "success");
```

### Pas responsive:

```
Ctrl+Shift+R (hard refresh)
```

---

## 📚 DOCUMENTATION COMPLÈTE

- `REFONTE_MODAL_RESPONSIVE_SHARE_SIMPLE.md` → Full détails
- `GUIDE_RAPIDE_MODAL_V3.md` → Guide pratique
- `TESTS_VISUELS_MODAL_V3.md` → Tests complets

---

## 🎨 DEBUG VISUEL (optionnel)

Inclure dans HTML:

```html
<link rel="stylesheet" href="services/debug-responsive.css" />
```

Badge breakpoint apparaît en haut à gauche.

---

## 💬 RAPPORT RAPIDE

Copiez et complétez:

```
✅/❌ Share fonctionne: ___
✅/❌ Toast s'affiche: ___
✅/❌ Modal responsive: ___
✅/❌ Footer adapté: ___

Navigateur: Chrome/Firefox/Safari/Edge
Device testé: Desktop/Mobile/Tablet

Commentaire:
___
```

---

**Version:** 3.0  
**Tests requis:** 2 minutes  
**Documentation:** 3 fichiers créés
