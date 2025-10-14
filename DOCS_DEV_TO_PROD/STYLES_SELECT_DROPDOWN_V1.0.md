# 🎨 STYLES MODERNISÉS POUR SELECT/DROPDOWN - V1.0

**Date :** 14 Octobre 2025  
**Fichier :** `assets/css/main.css`  
**Objectif :** Design moderne et lisse pour tous les selects du site

---

## 📊 MODIFICATIONS APPORTÉES

### **1. STYLES DE BASE POUR SELECT**

#### **Apparence Personnalisée**

```css
select {
  appearance: none; /* Supprime le style natif du navigateur */
  -webkit-appearance: none;
  -moz-appearance: none;
}
```

#### **Flèche Personnalisée SVG**

```css
background-image: url("data:image/svg+xml...");
background-position: right 12px center;
background-size: 20px;
padding-right: 44px; /* Espace pour la flèche */
```

**Rendu :** Flèche chevron grise élégante à droite

#### **Bordures Arrondies**

```css
border-radius: 12px; /* Coins arrondis */
border: 2px solid #e5e7eb;
```

#### **Effet d'Ombre**

```css
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
```

---

### **2. ÉTATS INTERACTIFS**

#### **État Hover (Survol)**

```css
select:hover {
  border-color: #2563eb; /* Bordure bleue */
  box-shadow: 0 4px 8px rgba(37, 99, 235, 0.1);
}
```

**Effet :** Bordure devient bleue + ombre plus prononcée

#### **État Focus (Sélectionné)**

```css
select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); /* Glow bleu */
  background-image: url("..."); /* Flèche devient bleue */
}
```

**Effet :** Ring bleu autour + flèche change de couleur

#### **État Disabled (Désactivé)**

```css
select:disabled {
  background-color: #f3f4f6;
  color: #9ca3af;
  cursor: not-allowed;
  opacity: 0.6;
}
```

**Effet :** Grisé et non-cliquable

---

### **3. STYLES DES OPTIONS (DROPDOWN)**

#### **Options Standards**

```css
select option {
  padding: 12px 16px;
  font-size: 14px;
  color: #111827;
  background: white;
  border-radius: 8px;
  transition: all 0.2s ease;
}
```

#### **Options Sélectionnées/Hover**

```css
select option:hover,
select option:checked {
  background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
  color: white;
  font-weight: 500;
}
```

**Effet :** Fond bleu dégradé + texte blanc + gras

#### **Options Désactivées**

```css
select option:disabled {
  color: #9ca3af;
  background: #f9fafb;
  cursor: not-allowed;
}
```

---

### **4. VALIDATION VISUELLE**

#### **Select Valide (✅)**

```css
select.valid {
  border-color: #10b981; /* Vert */
  background-image: url("..."); /* Flèche verte */
}

select.valid:focus {
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); /* Ring vert */
}
```

#### **Select Invalide (❌)**

```css
select.invalid {
  border-color: #ef4444; /* Rouge */
  background-image: url("..."); /* Flèche rouge */
}

select.invalid:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1); /* Ring rouge */
}
```

---

### **5. MODE SOMBRE**

```css
.dark-mode select {
  background-color: #1f2937; /* Fond sombre */
  color: #f9fafb; /* Texte clair */
  border-color: #374151;
  background-image: url("..."); /* Flèche grise claire */
}

.dark-mode select option {
  background: #1f2937;
  color: #f9fafb;
}

.dark-mode select option:hover,
.dark-mode select option:checked {
  background: #2563eb; /* Bleu pour sélection */
}
```

---

### **6. RESPONSIVE MOBILE**

```css
@media (max-width: 768px) {
  select {
    font-size: 16px; /* Empêche le zoom automatique iOS */
    padding: 14px 44px 14px 16px;
  }

  select option {
    padding: 14px 16px;
    font-size: 16px;
  }
}
```

**Important :** `font-size: 16px` empêche iOS de zoomer automatiquement sur les selects

---

## 🎨 RENDU VISUEL

### **État Normal**

```
┌─────────────────────────────────┐
│ Sélectionnez une option      ▼ │
└─────────────────────────────────┘
```

- Bordure grise claire
- Ombre légère
- Flèche chevron grise

### **État Hover**

```
┌─────────────────────────────────┐
│ Sélectionnez une option      ▼ │ ← Bordure bleue
└─────────────────────────────────┘
   ↑ Ombre plus prononcée
```

### **État Focus (Ouvert)**

```
┌─────────────────────────────────┐
│ Sélectionnez une option      ▼ │ ← Ring bleu autour
└─────────────────────────────────┘
   ┌─────────────────────────────┐
   │ Option 1                    │
   │ Option 2                    │ ← Fond bleu dégradé
   │ Option 3                    │
   └─────────────────────────────┘
```

### **Validation État**

**Valide :**

```
┌─────────────────────────────────┐
│ Option sélectionnée          ▼ │ ← Bordure verte + flèche verte
└─────────────────────────────────┘
```

**Invalide :**

```
┌─────────────────────────────────┐
│ Champ requis                 ▼ │ ← Bordure rouge + flèche rouge
└─────────────────────────────────┘
```

---

## 💻 UTILISATION

### **HTML Standard**

```html
<select>
  <option value="">Choisir une option</option>
  <option value="1">Option 1</option>
  <option value="2">Option 2</option>
  <option value="3" disabled>Option désactivée</option>
</select>
```

### **Avec Validation**

```html
<select class="valid">
  <option value="1">Option valide</option>
</select>

<select class="invalid">
  <option value="">Veuillez sélectionner</option>
</select>
```

### **JavaScript pour Validation**

```javascript
const select = document.querySelector("select");

// Ajouter classe valid
select.classList.add("valid");
select.classList.remove("invalid");

// Ajouter classe invalid
select.classList.add("invalid");
select.classList.remove("valid");
```

---

## 🔧 INTÉGRATION DANS ORDER-MODAL

### **Drip-feed Selects**

Les selects de drip-feed bénéficient automatiquement de ces styles :

```html
<!-- Dans order-modal.js -->
<input
  type="number"
  id="orderDripfeedRuns"
  name="dripfeed_runs"
  min="2"
  placeholder="ex: 10"
/>
<input
  type="number"
  id="orderDripfeedInterval"
  name="dripfeed_interval"
  min="1"
  placeholder="ex: 60"
/>
```

**Note :** Les inputs number peuvent aussi être convertis en select si besoin :

```html
<select id="orderDripfeedRuns">
  <option value="">Nombre d'exécutions</option>
  <option value="2">2 lots</option>
  <option value="5">5 lots</option>
  <option value="10">10 lots</option>
  <option value="20">20 lots</option>
</select>
```

---

## 📋 CHECKLIST D'APPLICATION

### **Styles Appliqués**

- ✅ Bordures arrondies (12px)
- ✅ Flèche personnalisée SVG
- ✅ Ombre subtile
- ✅ Effet hover bleu
- ✅ Ring focus bleu
- ✅ Options avec fond gradient au survol
- ✅ États valid/invalid avec couleurs
- ✅ Mode sombre supporté
- ✅ Responsive mobile (16px pour iOS)

### **Compatibilité**

- ✅ Chrome/Edge (Chromium)
- ✅ Firefox
- ✅ Safari (macOS/iOS)
- ✅ Opera
- ✅ Mobile Android
- ✅ Mobile iOS

---

## 🎯 AVANTAGES

### **1. Cohérence Visuelle**

- Tous les selects du site ont le même style
- Design moderne et professionnel
- Intégration parfaite avec le design system

### **2. UX Améliorée**

- Feedback visuel clair (hover, focus, valid, invalid)
- Lisibilité optimale
- Accessibilité respectée (outline focus)

### **3. Personnalisation Facile**

- CSS centralisé dans main.css
- Variables CSS pour couleurs
- Facilement modifiable

### **4. Performance**

- SVG inline (pas de requête HTTP)
- Transitions fluides
- Pas de JavaScript nécessaire

---

## 🔄 EXTENSIONS FUTURES

### **Select avec Icônes**

```html
<select>
  <option value="fr">🇫🇷 Français</option>
  <option value="en">🇬🇧 English</option>
  <option value="es">🇪🇸 Español</option>
</select>
```

### **Select Multi-niveau**

```html
<select>
  <optgroup label="Tier Standard">
    <option value="1">Service 1</option>
    <option value="2">Service 2</option>
  </optgroup>
  <optgroup label="Tier Premium">
    <option value="3">Service 3</option>
  </optgroup>
</select>
```

### **Select avec Recherche**

Pour des cas avancés, utiliser une librairie comme **Select2** ou **Choices.js**

---

## 📚 DOCUMENTATION COMPLÉMENTAIRE

- **Configuration icônes :** `ICONS_CONFIG_CENTRALISE_V1.0.md`
- **Modal de commande :** `TRADUCTION_FRANCAISE_MODAL_V3.1.md`
- **Design system :** `assets/css/main.css` (variables CSS ligne 13-25)

---

## 🎨 COULEURS UTILISÉES

| État         | Couleur    | Hex       | Usage          |
| ------------ | ---------- | --------- | -------------- |
| **Primaire** | Bleu       | `#2563eb` | Hover, Focus   |
| **Succès**   | Vert       | `#10b981` | Validation OK  |
| **Erreur**   | Rouge      | `#ef4444` | Validation KO  |
| **Bordure**  | Gris clair | `#e5e7eb` | État normal    |
| **Texte**    | Gris foncé | `#111827` | Texte select   |
| **Disabled** | Gris moyen | `#9ca3af` | État désactivé |

---

**🎨 STYLES MODERNISÉS - SELECT/DROPDOWN V1.0 PRÊT !**
