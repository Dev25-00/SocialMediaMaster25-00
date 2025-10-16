# ✅ CHECKLIST TESTS - MODULE TRADUCTION MULTILANGUE

**Date:** 14 Octobre 2025  
**Version:** Phase 14 - Correctifs Google Translate Widget  
**Environnement:** http://localhost/smm/

---

## 🖥️ DESKTOP TESTS (Chrome/Firefox 1200px+)

### **INDEX.PHP (Landing Page)**

- [ ] **Widget traduction visible** dans header navigation
- [ ] **Clic sur dropdown traduction** ouvre menu langues
- [ ] **Sélection FR→EN** déclenche traduction (texte change)
- [ ] **Loader fullscreen** apparaît pendant traduction (écran noir avec spinner)
- [ ] **Dropdown positionné correctement** (pas décalé à droite)

### **DASHBOARD/INDEX.PHP (User Dashboard)**

- [ ] **Top bar sticky** reste collé en haut lors du scroll
- [ ] **Widget traduction** fonctionnel dans top bar
- [ ] **Pas de margins/padding indésirés** (top bar touche bords viewport)
- [ ] **Traduction FR→EN** change contenu dashboard
- [ ] **Balance, stats, cartes** se traduisent correctement

### **SERVICES/INDEX.PHP (Catalogue Services)**

- [ ] **Filtres section sticky** reste visible pendant scroll
- [ ] **Top bar sticky** fonctionne avec filtres sticky
- [ ] **Pas de double scroll** (scroll unique page)
- [ ] **Widget traduction** accessible dans header
- [ ] **Services se traduisent** (titres, descriptions)

---

## 📱 MOBILE/TABLET TESTS (375px - 768px)

### **Responsive Layout (768px)**

- [ ] **Top bar responsive** s'adapte largeur tablet
- [ ] **Dropdown traduction centré** (pas décalé droite)
- [ ] **Widget traduction** reste accessible touch
- [ ] **Sticky headers** fonctionnent mobile
- [ ] **Hamburger menu** visible et cliquable

### **Mobile Portrait (375px)**

- [ ] **Icône profile ne dépasse pas** header droite
- [ ] **Top bar éléments** rentrent dans largeur mobile
- [ ] **Dropdown traduction** s'affiche correctement
- [ ] **Loader fullscreen** couvre tout l'écran mobile
- [ ] **Navigation sticky** scroll mobile fluide

---

## 🔧 TESTS TECHNIQUES (DevTools)

### **Console JavaScript**

- [ ] **Aucune erreur JS** dans console
- [ ] **Messages debug traduction** `[SMM Translate]` visibles
- [ ] **Google Translate API** charge sans erreur 404/CORS
- [ ] **localStorage** sauvegarde langue préférée
- [ ] **Retry logic** fonctionne si API lente

### **Réseau/Performance**

- [ ] **translate.google.com/translate_a/element.js** charge
- [ ] **Pas de 404** sur ressources CSS/JS
- [ ] **Z-index loader** = 2147483647 (DevTools Elements)
- [ ] **Position sticky** appliquée (Computed styles)
- [ ] **Overflow:visible** sur .main-content (pas hidden)

---

## 🎯 SCENARIOS DE TEST CRITIQUES

### **Test Traduction Complète**

1. Ouvrir http://localhost/smm/
2. Clic dropdown traduction → Sélectionner "English"
3. ✅ **Loader apparaît fullscreen** (noir + spinner)
4. ✅ **Texte français devient anglais** (navigation, boutons, contenu)
5. ✅ **Préférence sauvegardée** (refresh page → reste EN)

### **Test Headers Sticky Multi-Pages**

1. Dashboard: Scroll → top bar reste sticky
2. Services: Scroll → top bar ET filtres restent sticky
3. Mobile: Headers sticky fonctionnent tactile
4. Transition pages: Headers cohérents
5. Pas de "saut" visuel ou double scroll

### **Test Mobile Overflow/Layout**

1. Chrome DevTools → Device 375px iPhone
2. Header éléments: balance + traduction + profile rentrent
3. Dropdown traduction: centré, pas coupé
4. Sticky scroll: fluide sans débordement horizontal
5. Touch targets: boutons cliquables facilement

---

## 🚨 POINTS DE VIGILANCE

### **Erreurs Connues à Vérifier**

- ❌ **Google Translate lent à initialiser** → Retry logic activé ?
- ❌ **Dropdown hors viewport** → Position left/right calculée ?
- ❌ **Sticky bloqué par overflow** → Parent containers checked ?
- ❌ **Mobile profile overflow** → Icon sizes optimized ?
- ❌ **Double scroll services** → Sidebar overflow vs page ?

### **Fallbacks si Problème**

- **Traduction marche pas** → Vérifier .goog-te-combo dans DOM
- **Loader invisible** → F12 Elements → chercher z-index override
- **Sticky pas sticky** → F12 Computed → chercher overflow:hidden parent
- **Mobile déborde** → Réduire gaps/padding top-bar-right

---

## 📝 RAPPORT TEST

### **Résultats à Reporter**

```
✅ OK / ❌ NOK - [Description test]

DESKTOP:
□ Index traduction: ___
□ Dashboard sticky: ___
□ Services filtres: ___

MOBILE:
□ Layout responsive: ___
□ Touch interaction: ___
□ Profile overflow: ___

TECHNIQUE:
□ Console clean: ___
□ API loading: ___
□ Performance: ___
```

### **Si Problème Détecté**

1. **Screenshot** du problème (desktop + mobile)
2. **Console errors** (copier messages JS)
3. **DevTools inspection** (CSS computed values)
4. **URL/Page** où problème se produit
5. **Steps to reproduce** précis

---

**🔗 Documentation Technique:** `DOCS_DEV_TO_PROD/PHASE14_TRANSLATION_BUGFIXES.md`  
**🛠️ Fichiers Modifiés:** `google-translate-widget.php`, `dashboard-top-bar.php`, `dashboard.css`
