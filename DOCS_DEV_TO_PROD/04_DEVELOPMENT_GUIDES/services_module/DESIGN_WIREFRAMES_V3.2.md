# 🎨 DESIGN VISUEL MODAL V3.2 - WIREFRAMES

**Date:** 14 Octobre 2025  
**Version:** 3.2  

---

## 🖥️ DESKTOP VIEW (≥ 1024px)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  🛒 Order Service          [New Order] [Favorites] [Countries]          ✕   │
├─────────────────────────────┬───────────────────────────────────────────────┤
│                             │                                               │
│  ┌───────────────────────┐  │  ┌─────────────────────────────────────────┐ │
│  │ 🟣 Instagram · #12345 │  │  │ ⚠️  Important Requirements             │ │
│  │ Instagram Followers   │  │  │                                         │ │
│  │ 💎Premium  👑High     │  │  │  🔓 Public Account Required            │ │
│  └───────────────────────┘  │  │     Your account must be set to public  │ │
│                             │  │     for the service to work             │ │
│  🔗 Link / URL              │  │                                         │ │
│  ┌───────────────────────┐  │  │  ✓ Valid Link Format                   │ │
│  │ https://instagram.com │  │  │    Enter the full URL                  │ │
│  │ /therock              │  │  │    (e.g., https://...)                 │ │
│  └───────────────────────┘  │  │                                         │ │
│  ✓ Valid URL                │  │  ⏰ Processing Time                     │ │
│                             │  │     Service will start within minutes   │ │
│  # Quantity                 │  │     after order placement               │ │
│  ┌───────────────────────┐  │  └─────────────────────────────────────────┘ │
│  │        5000          ▲│  │                                               │
│  └──────────────────────▼┘  │  ┌─────────────────────────────────────────┐ │
│  Min: 1K    Max: 100K       │  │ ℹ️  Service Details                     │ │
│                             │  │                                         │ │
│  💧 Drip-feed              │  │  📝 Description                         │ │
│  ☐ Enable drip-feed        │  │     High quality Instagram followers    │ │
│                             │  │     from real active accounts...        │ │
│  💰 Total Charge            │  │                                         │ │
│  ┌───────────────────────┐  │  │  📍 Location: Worldwide                │ │
│  │ Current: $50.00       │  │  │  ♻️  Refill: 30 days                   │ │
│  │ After:   $42.50       │  │  │  📈 Speed: 1K-5K/day                   │ │
│  │               $7.50   │  │  │  ⏱️  Avg Time: 0-1 hour                │ │
│  └───────────────────────┘  │  └─────────────────────────────────────────┘ │
│                             │                                               │
│                             │                                               │
├─────────────────────────────┴───────────────────────────────────────────────┤
│  [📋 Copy Link]          [✕ Cancel]          [✓ Place Order]                │
└─────────────────────────────────────────────────────────────────────────────┘
```

**Caractéristiques:**
- ✅ 2 colonnes égales
- ✅ Séparateur vertical entre colonnes
- ✅ Scroll indépendant de chaque colonne
- ✅ Warnings toujours visibles (jaune)
- ✅ Description toujours visible (bleu)

---

## 📱 MOBILE VIEW (< 768px)

```
┌─────────────────────────┐
│ 🛒 Order Service    ✕  │
│ [New] [Fav] [Coun...]  │
├─────────────────────────┤
│                         │
│ ┌─────────────────────┐ │
│ │ 🟣 Instagram        │ │
│ │ Instagram Followers │ │
│ │ 💎Premium  👑High   │ │
│ └─────────────────────┘ │
│                         │
│ 🔗 Link / URL           │
│ ┌─────────────────────┐ │
│ │ @therock           │ │
│ └─────────────────────┘ │
│ ℹ️ Username accepted    │
│                         │
│ # Quantity              │
│ ┌─────────────────────┐ │
│ │      5000         ▲│ │
│ └────────────────────▼┘ │
│ Min: 1K   Max: 100K     │
│                         │
│ 💧 Drip-feed           │
│ ☐ Enable drip-feed     │
│                         │
│ 💰 Total Charge         │
│ ┌─────────────────────┐ │
│ │ Current: $50.00    │ │
│ │ After:   $42.50    │ │
│ │           $7.50    │ │
│ └─────────────────────┘ │
│                         │
│ ┌─────────────────────┐ │
│ │ ⚠️ Important Req.   │ │
│ │                     │ │
│ │ 🔓 Public Account  │ │
│ │ ✓ Valid Format     │ │
│ │ ⏰ Processing Time  │ │
│ └─────────────────────┘ │
│                         │
│ ┌─────────────────────┐ │
│ │ ℹ️ Service Details  │ │
│ │                     │ │
│ │ Description...      │ │
│ │ Location...         │ │
│ └─────────────────────┘ │
│                         │
│ (Scroll down)          │
│                         │
├─────────────────────────┤
│ [Copy] [Cancel] [Buy]  │
└─────────────────────────┘
```

**Caractéristiques:**
- ✅ Colonne unique verticale
- ✅ Info panel en bas (après formulaire)
- ✅ Warnings compacts
- ✅ Padding réduit
- ✅ Boutons compacts

---

## 🎨 VALIDATION LINK - ÉTATS VISUELS

### **1. État Initial (Vide)**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ https://instagram.com/username   │ │
│ └──────────────────────────────────┘ │
│                                      │
└──────────────────────────────────────┘
```

---

### **2. Valid URL (Success) - Feedback masqué**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ https://instagram.com/therock    │ │
│ └──────────────────────────────────┘ │
│                                      │
└──────────────────────────────────────┘
```
**Pas de feedback affiché si tout est OK**

---

### **3. Username (Info - Bleu)**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ @therock                         │ │
│ └──────────────────────────────────┘ │
│ ┌──────────────────────────────────┐ │
│ │ ℹ️ Username accepted (will be   │ │
│ │    converted to full URL)        │ │
│ └──────────────────────────────────┘ │
└──────────────────────────────────────┘
```
**Background:** `rgba(59, 130, 246, 0.15)` (bleu)

---

### **4. URL Inconnue (Warning - Jaune)**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ https://newplatform.xyz/user     │ │
│ └──────────────────────────────────┘ │
│ ┌──────────────────────────────────┐ │
│ │ ⚠️ URL accepted (verify platform │ │
│ │    compatibility)                │ │
│ └──────────────────────────────────┘ │
└──────────────────────────────────────┘
```
**Background:** `rgba(251, 191, 36, 0.15)` (jaune)

---

### **5. Erreur Espaces (Error - Rouge)**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ my profile name                  │ │
│ └──────────────────────────────────┘ │
│ ┌──────────────────────────────────┐ │
│ │ ✗ Link cannot contain spaces     │ │
│ └──────────────────────────────────┘ │
└──────────────────────────────────────┘
```
**Background:** `rgba(239, 68, 68, 0.15)` (rouge)

---

### **6. Erreur Trop Court (Error - Rouge)**
```
┌──────────────────────────────────────┐
│ 🔗 Link / URL                        │
│ ┌──────────────────────────────────┐ │
│ │ ab                               │ │
│ └──────────────────────────────────┘ │
│ ┌──────────────────────────────────┐ │
│ │ ✗ Link must be at least 3       │ │
│ │    characters                    │ │
│ └──────────────────────────────────┘ │
└──────────────────────────────────────┘
```

---

## 🔴 BOUTON SUBMIT - ÉTATS

### **1. Activé (Tout OK)**
```
┌─────────────────────────────┐
│  ✓ Place Order              │  ← Couleur: Violet/Bleu
└─────────────────────────────┘
- Background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
- Cursor: pointer
- Hover: lift effect + shadow
```

---

### **2. Désactivé - Solde Insuffisant**
```
┌─────────────────────────────┐
│  ⚠️ Insufficient Balance    │  ← Couleur: Rouge
└─────────────────────────────┘
- Background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%)
- Opacity: 0.7
- Cursor: not-allowed
- Pas de hover
```

---

### **3. Désactivé - Quantité Min**
```
┌─────────────────────────────┐
│  ⚠️ Min: 1K                 │  ← Couleur: Rouge
└─────────────────────────────┘
```

---

### **4. Désactivé - Quantité Max**
```
┌─────────────────────────────┐
│  ⚠️ Max: 100K               │  ← Couleur: Rouge
└─────────────────────────────┘
```

---

### **5. Processing (Soumission en cours)**
```
┌─────────────────────────────┐
│  ⏳ Processing...           │  ← Spinner animé
└─────────────────────────────┘
- Icon: spinning
- Disabled
```

---

## 📊 PANNEAU WARNINGS (Détail)

```
┌───────────────────────────────────────────────────────┐
│  ⚠️  Important Requirements                           │
├───────────────────────────────────────────────────────┤
│                                                       │
│  ┌─────────────────────────────────────────────────┐ │
│  │  🔓                                             │ │
│  │     Public Account Required                     │ │
│  │     Your account must be set to public for the  │ │
│  │     service to work                             │ │
│  └─────────────────────────────────────────────────┘ │
│                                                       │
│  ┌─────────────────────────────────────────────────┐ │
│  │  ✓                                              │ │
│  │     Valid Link Format                           │ │
│  │     Enter the full URL (e.g.,                   │ │
│  │     https://instagram.com/username)             │ │
│  └─────────────────────────────────────────────────┘ │
│                                                       │
│  ┌─────────────────────────────────────────────────┐ │
│  │  ⏰                                              │ │
│  │     Processing Time                             │ │
│  │     Service will start within minutes after     │ │
│  │     order placement                             │ │
│  └─────────────────────────────────────────────────┘ │
│                                                       │
└───────────────────────────────────────────────────────┘
```

**Style:**
- Background: Dégradé jaune/orange transparent
- Border: 2px solid jaune
- Border-radius: 16px
- Padding: 24px
- Chaque item:
  - Background: rgba(0, 0, 0, 0.2)
  - Border-left: 3px solid jaune
  - Icon size: 20px
  - Strong: blanc 95%
  - Text: blanc 70%

---

## 🎨 PALETTE COULEURS

```
🟣 Primary (Buttons):     #667eea → #764ba2 (gradient)
🟢 Success (Balance):     #10b981
🔴 Error (Insufficient):  #ef4444 → #dc2626 (gradient)
🟡 Warning (Info box):    #fbbf24
🔵 Info (Link feedback):  #3b82f6
⚫ Background:            #1e1e2e → #2a2a3e (gradient)
⚪ Text Primary:          rgba(255, 255, 255, 0.95)
⚪ Text Secondary:        rgba(255, 255, 255, 0.7)
```

---

## 📏 DIMENSIONS

```css
/* Modal Global */
max-width: 1200px
max-height: 90vh
border-radius: 20px

/* Desktop */
Form Column:    max-width: 600px
Info Column:    max-width: 450px
Padding:        30px
Gap:            24px

/* Mobile */
Padding:        16px
Gap:            12px

/* Elements */
Input height:   48px
Button height:  52px
Border radius:  10-16px
```

---

## ✨ ANIMATIONS

```css
/* Feedback link */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button hover */
transform: translateY(-2px);
box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);

/* Modal entrance */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

---

## 🎯 HIÉRARCHIE VISUELLE

1. **Header** - Titre + Tabs (Violet)
2. **Service Selected** - Info service (Violet clair)
3. **Formulaire** - Champs de saisie (Blanc)
4. **Warnings** - Box jaune (Important)
5. **Description** - Box bleue (Info)
6. **Footer** - Boutons d'action (Violet/Gris)

**Principe:** Les éléments importants (warnings, erreurs) sont mis en avant avec des couleurs vives.

---

## ✅ CHECKLIST DESIGN

- [ ] Layout 2 colonnes visible sur desktop
- [ ] Colonne unique sur mobile
- [ ] Warnings box jaune visible
- [ ] Description box bleue visible
- [ ] Feedback link coloré (rouge/jaune/bleu)
- [ ] Bouton rouge si désactivé
- [ ] Bouton violet si activé
- [ ] Icônes Font Awesome affichées
- [ ] Animations smooth (slideDown, lift)
- [ ] Border séparatrice entre colonnes
- [ ] Scroll indépendant fonctionnel

---

**Version:** 3.2.0  
**Design:** Modern, Clean, User-Friendly  
**Statut:** ✅ Ready for Production
