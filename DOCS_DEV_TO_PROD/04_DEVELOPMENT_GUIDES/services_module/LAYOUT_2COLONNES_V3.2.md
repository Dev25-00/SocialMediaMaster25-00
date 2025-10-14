# 📐 LAYOUT 2 COLONNES MODAL - V3.2

**Date:** 14 Octobre 2025  
**Version:** 3.2  
**Fichiers:** `services/js/order-modal.js` + `services/css/order-modal.css`

---

## 🎯 OBJECTIF

Réorganiser le modal de commande avec un layout à 2 colonnes sur desktop pour améliorer l'expérience utilisateur en affichant les informations importantes (warnings, description) de manière visible et permanente.

---

## 📐 STRUCTURE LAYOUT

### **🖥️ DESKTOP (≥ 1024px)**

```
┌──────────────────────────────────────────────────────────────┐
│                     HEADER + TABS                            │
├──────────────────────┬───────────────────────────────────────┤
│                      │                                       │
│  COLONNE GAUCHE      │  COLONNE DROITE                      │
│  (Formulaire)        │  (Informations)                      │
│                      │                                       │
│  - Service selected  │  ┌─────────────────────────────────┐ │
│  - Link/URL          │  │ ⚠️ IMPORTANT REQUIREMENTS      │ │
│    └ Validation      │  │                                 │ │
│  - Quantity          │  │ 🔓 Public Account Required     │ │
│  - Drip-feed         │  │ ✓ Valid Link Format            │ │
│  - Total Charge      │  │ ⏰ Processing Time              │ │
│                      │  └─────────────────────────────────┘ │
│  (Scroll si long)    │                                       │
│                      │  ┌─────────────────────────────────┐ │
│                      │  │ ℹ️ SERVICE DETAILS             │ │
│                      │  │                                 │ │
│                      │  │ - Description                   │ │
│                      │  │ - Location                      │ │
│                      │  │ - Refill                        │ │
│                      │  │ - Speed                         │ │
│                      │  └─────────────────────────────────┘ │
│                      │                                       │
│                      │  (Scroll si long)                     │
│                      │                                       │
├──────────────────────┴───────────────────────────────────────┤
│                      FOOTER (Buttons)                        │
└──────────────────────────────────────────────────────────────┘
```

**Dimensions:**
- Colonne gauche : `max-width: 600px`, `flex: 1`
- Colonne droite : `max-width: 450px`, `flex: 1`
- Séparateur : `border-right: 1px solid rgba(255, 255, 255, 0.1)`

---

### **📱 MOBILE (< 1024px)**

```
┌────────────────────────────────┐
│      HEADER + TABS             │
├────────────────────────────────┤
│                                │
│  COLONNE UNIQUE                │
│                                │
│  - Service selected            │
│  - Link/URL                    │
│    └ Validation                │
│  - Quantity                    │
│  - Drip-feed                   │
│  - Total Charge                │
│                                │
│  ┌──────────────────────────┐  │
│  │ ⚠️ IMPORTANT REQUIREMENTS│  │
│  │                          │  │
│  │ (Warnings compacts)      │  │
│  └──────────────────────────┘  │
│                                │
│  ┌──────────────────────────┐  │
│  │ ℹ️ SERVICE DETAILS       │  │
│  │                          │  │
│  │ (Description compacte)   │  │
│  └──────────────────────────┘  │
│                                │
│  (Scroll vertical)             │
│                                │
├────────────────────────────────┤
│      FOOTER (Buttons)          │
└────────────────────────────────┘
```

**Changements mobile:**
- `flex-direction: column` au lieu de `row`
- Info panel en bas (après formulaire)
- `border-top` au lieu de `border-right`
- Padding réduit : `16px` au lieu de `30px`

---

## 🎨 COLONNE GAUCHE - FORMULAIRE

### **Contenu:**
1. **Service Selected** - Header avec platform + ID
2. **Link/URL** - Input avec validation regex temps réel
3. **Quantity** - Input numérique avec min/max
4. **Drip-feed** - Checkbox + options conditionnelles
5. **Total Charge** - Affichage prix + soldes

### **Styles:**

```css
.order-modal-form-section {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
    background: #1e1e2e;
    min-height: 0;
}

@media (min-width: 1024px) {
    .order-modal-form-section {
        max-width: 600px;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
}
```

### **Scroll:**
- Scroll indépendant de la colonne droite
- `overflow-y: auto` pour gérer les longs formulaires
- `min-height: 0` pour permettre le flex shrink

---

## ⚠️ COLONNE DROITE - INFORMATIONS

### **Contenu:**

#### **1. Important Requirements Box**

```html
<div class="order-info-warnings">
    <div class="order-warning-header">
        <i class="fas fa-exclamation-triangle"></i>
        <h4>Important Requirements</h4>
    </div>
    <div class="order-warning-list">
        <!-- Warning items -->
    </div>
</div>
```

**Warning Items:**
- 🔓 **Public Account Required**
  - "Your account must be set to public for the service to work"
- ✓ **Valid Link Format**
  - "Enter the full URL (e.g., https://instagram.com/username)"
- ⏰ **Processing Time**
  - "Service will start within minutes after order placement"

**Styles:**
```css
.order-info-warnings {
    background: linear-gradient(135deg, 
        rgba(251, 191, 36, 0.1) 0%, 
        rgba(245, 158, 11, 0.1) 100%
    );
    border: 2px solid rgba(251, 191, 36, 0.3);
    border-radius: 16px;
    padding: 24px;
}
```

---

#### **2. Service Details Box**

```html
<div class="order-service-description-section">
    <div class="order-description-title">
        <i class="fas fa-info-circle"></i>
        Service Details
    </div>
    <div id="orderServiceDetails">
        <!-- Description items -->
    </div>
</div>
```

**Content dynamique:**
- Description
- Location
- Refill (type + days)
- Drop Rate
- Speed
- Average Time

**Styles:**
```css
.order-modal-info-panel .order-service-description-section {
    background: rgba(102, 126, 234, 0.1);
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 16px;
    padding: 24px;
}
```

---

### **Panel complet:**

```css
@media (min-width: 1024px) {
    .order-modal-info-panel {
        display: flex;
        flex-direction: column;
        flex: 1;
        max-width: 450px;
        padding: 30px;
        overflow-y: auto;
        background: linear-gradient(135deg, 
            #2a2a3e 0%, 
            #1e1e2e 100%
        );
        gap: 24px;
    }
}
```

---

## 📏 DIMENSIONS & SPACING

### **Desktop:**

```css
/* Modal global */
max-width: 1200px;
width: 100%;
max-height: 90vh;

/* Colonne gauche */
max-width: 600px;
padding: 30px;

/* Colonne droite */
max-width: 450px;
padding: 30px;
gap: 24px; /* Entre les boxes */

/* Warning box */
padding: 24px;
gap: 16px; /* Entre les warning items */

/* Description box */
padding: 24px;
```

### **Mobile:**

```css
/* Colonne unique */
padding: 16px;

/* Warning box */
padding: 16px;
gap: 12px;

/* Description box */
padding: 16px;
```

---

## 🎯 AVANTAGES DU LAYOUT 2 COLONNES

### **UX Améliorée:**
1. ✅ **Informations toujours visibles** - Pas besoin de scroller pour voir warnings
2. ✅ **Focus sur le formulaire** - Largeur optimale (600px) pour la saisie
3. ✅ **Contexte permanent** - Description et exigences sous les yeux
4. ✅ **Séparation claire** - Formulaire vs informations

### **Visuel:**
1. ✅ **Design moderne** - Layout professionnel et épuré
2. ✅ **Utilisation espace** - Desktop large exploité intelligemment
3. ✅ **Couleurs codées** - Jaune (warnings), Bleu (infos)
4. ✅ **Icônes explicites** - Font Awesome pour clarté

### **Responsive:**
1. ✅ **Mobile-friendly** - Revert to single column automatiquement
2. ✅ **Progressive enhancement** - Base mobile, enhance desktop
3. ✅ **Breakpoint intelligent** - 1024px pour tablette/desktop

---

## 🔧 CODE HTML STRUCTURE

### **Tab Panel "New Order":**

```html
<div class="order-tab-panel active" data-tab-panel="new-order">
    
    <!-- COLONNE GAUCHE: FORMULAIRE -->
    <div class="order-modal-form-section">
        <div id="orderAlert"></div>
        <div class="order-selected-service" id="orderSelectedService"></div>
        
        <form id="orderForm">
            <!-- Link -->
            <div class="order-form-group">
                <label>
                    <i class="fas fa-link"></i>
                    Link / URL
                </label>
                <input type="text" id="orderLink" placeholder="..." required>
                <div class="order-link-validation" id="orderLinkValidation"></div>
            </div>
            
            <!-- Quantity -->
            <div class="order-form-group">...</div>
            
            <!-- Drip-feed -->
            <div class="order-form-group">...</div>
            
            <!-- Total Charge -->
            <div class="order-form-group">...</div>
        </form>
    </div>
    
    <!-- COLONNE DROITE: INFORMATIONS -->
    <div class="order-modal-info-panel">
        
        <!-- Warnings Box -->
        <div class="order-info-warnings">
            <div class="order-warning-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Important Requirements</h4>
            </div>
            <div class="order-warning-list">
                <div class="order-warning-item">
                    <i class="fas fa-unlock"></i>
                    <div>
                        <strong>Public Account Required</strong>
                        <p>Your account must be set to public for the service to work</p>
                    </div>
                </div>
                <!-- More warnings... -->
            </div>
        </div>
        
        <!-- Service Details -->
        <div class="order-service-description-section">
            <div class="order-description-title">
                <i class="fas fa-info-circle"></i>
                Service Details
            </div>
            <div id="orderServiceDetails">
                <!-- Rempli dynamiquement -->
            </div>
        </div>
        
    </div>
    
</div>
```

---

## 📱 MEDIA QUERIES

### **Desktop (≥ 1024px):**

```css
@media (min-width: 1024px) {
    /* Layout 2 colonnes */
    .order-tab-panel[data-tab-panel="new-order"].active {
        display: flex;
        flex-direction: row;
        gap: 0;
    }
    
    /* Colonne gauche */
    .order-modal-form-section {
        max-width: 600px;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Colonne droite */
    .order-modal-info-panel {
        display: flex;
        flex-direction: column;
        max-width: 450px;
    }
}
```

### **Mobile (< 1024px):**

```css
@media (max-width: 1023px) {
    /* Layout colonne unique */
    .order-tab-panel[data-tab-panel="new-order"].active {
        flex-direction: column;
    }
    
    /* Info panel en bas */
    .order-modal-info-panel {
        display: flex !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 20px;
        padding: 16px;
    }
}
```

---

## 🧪 TESTS RECOMMANDÉS

### **Test Desktop:**
1. Ouvrir modal sur écran large (≥ 1024px)
2. ✓ Vérifier 2 colonnes côte à côte
3. ✓ Warnings visible à droite (jaune)
4. ✓ Description visible à droite (bleu)
5. ✓ Border vertical entre colonnes
6. ✓ Scroll indépendant de chaque colonne

### **Test Tablet:**
1. Réduire fenêtre à ~1000px
2. ✓ Layout passe en colonne unique
3. ✓ Info panel en bas du formulaire
4. ✓ Border horizontal au-dessus info panel

### **Test Mobile:**
1. Ouvrir modal sur mobile (< 768px)
2. ✓ Colonne unique
3. ✓ Info panel en bas
4. ✓ Padding réduit (16px)
5. ✓ Textes lisibles (tailles adaptées)

---

## 📚 FICHIERS CONCERNÉS

- **Structure HTML:** `services/js/order-modal.js` (lignes ~170-340)
- **Styles layout:** `services/css/order-modal.css` (lignes ~130-190)
- **Styles warnings:** `services/css/order-modal.css` (lignes ~724-825)
- **Responsive:** `services/css/order-modal.css` (lignes ~1600-1650)

---

## ✅ STATUT: IMPLÉMENTÉ ET TESTÉ

**Date d'implémentation:** 14 Octobre 2025  
**Version:** 3.2.0  
**Testé sur:** Desktop (1920px), Laptop (1366px), Tablet (768px), Mobile (375px)  
**Navigateurs:** Chrome, Firefox, Edge, Safari  
**Pas de bugs connus**

---

**🎯 OBJECTIF ATTEINT:** Layout moderne, informatif et responsive !
