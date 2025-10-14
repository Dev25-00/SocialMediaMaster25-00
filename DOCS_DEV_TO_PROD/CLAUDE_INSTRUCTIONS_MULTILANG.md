# 🤖 INSTRUCTIONS CLAUDE - SYSTÈME MULTI-LANGUE SMM Mastery

**Version :** 2.0 - Enrichie Multi-langue  
**Date :** 14 Octobre 2025  
**Pour :** Claude AI (Anthropic)

---

## 📋 CONTEXTE PROJET ACTUALISÉ

Le projet **SMM Mastery** dispose maintenant d'un **système multi-langue complet** via Google Translate Widget.

### **Nouveaux Fichiers Clés**

```
includes/
├── google-translate-widget.php       ← Widget multi-langue principal
├── google-translate-widget-debug.php ← Version debug (ne pas utiliser en prod)
└── google-translate-widget-backup.php ← Backup v1.0

DOCS_DEV_TO_PROD/
├── PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md  ← Doc complète
├── PHASE14_VALIDATION_TRADUCTION.md          ← Guide de test
├── CLAUDE_INSTRUCTIONS_MULTILANG.md          ← Ce fichier
└── COPILOT_INSTRUCTIONS_MULTILANG.md         ← Instructions Copilot
```

---

## 🌍 SYSTÈME MULTI-LANGUE : FONCTIONNEMENT

### **Architecture**

```
┌─────────────────────────────────────────┐
│  WIDGET (includes/google-translate-widget.php)
├─────────────────────────────────────────┤
│  • HTML: Bouton + Dropdown              │
│  • CSS: Styles inline (~450 lignes)     │
│  • JS: Logique (~200 lignes)            │
│  • Google Translate API: Traduction     │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│  INTÉGRATION (headers)                  │
├─────────────────────────────────────────┤
│  • public-header.php                    │
│  • dashboard-top-bar.php                │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│  UTILISATEUR                            │
├─────────────────────────────────────────┤
│  1. Clique globe 🌍                     │
│  2. Sélectionne langue                  │
│  3. Page traduite automatiquement       │
│  4. Préférence sauvegardée (localStorage)│
└─────────────────────────────────────────┘
```

---

## ✅ RÈGLES POUR CLAUDE

### **RÈGLE 1 : Ne JAMAIS Modifier le Widget sans Comprendre**

Le widget est **complet et testé**. Ne pas modifier sauf :

- Bug avéré et reproductible
- Demande explicite utilisateur
- Amélioration documentée

**Avant toute modification :**

```bash
1. Lire PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md
2. Comprendre architecture
3. Créer backup
4. Tester localement
5. Documenter changement
```

---

### **RÈGLE 2 : Toujours Inclure le Widget dans Nouveaux Headers**

**Pour toute nouvelle page avec header personnalisé :**

```php
<!-- OBLIGATOIRE: Widget Multi-langue -->
<?php include __DIR__ . '/../includes/google-translate-widget.php'; ?>
```

**Emplacements types :**

- Après liens navigation
- Avant boutons CTA (Connexion/Inscription)
- Dans topbar dashboard (avant menu utilisateur)

**Exemple complet :**

```php
<nav class="navigation">
    <a href="/">Accueil</a>
    <a href="/services">Services</a>

    <!-- Widget Multi-langue -->
    <?php include __DIR__ . '/../includes/google-translate-widget.php'; ?>

    <a href="/login" class="btn">Connexion</a>
</nav>
```

---

### **RÈGLE 3 : CSS - Ne Pas Écraser les Styles du Widget**

Le widget utilise des **classes préfixées** : `.smm-translate-*`

**✅ FAIRE :**

```css
/* Ajouter styles complémentaires */
.my-header .smm-translate-wrapper {
  margin-left: auto; /* Aligner à droite */
}
```

**❌ NE PAS FAIRE :**

```css
/* Écraser styles internes */
.smm-translate-dropdown {
  position: relative !important; /* ⚠️ Casse le widget */
}
```

**Propriétés CRITIQUES à ne PAS modifier :**

- `position: fixed` sur dropdown
- `z-index` sur dropdown/loader
- `display: none` sur `#google_translate_element`

---

### **RÈGLE 4 : Debugging - Utiliser la Version Debug**

En cas de problème dropdown/traduction :

**Activer version debug :**

```php
<!-- Temporaire - Pour debugging uniquement -->
<?php include __DIR__ . '/google-translate-widget-debug.php'; ?>
```

**Restaurer version normale après :**

```php
<?php include __DIR__ . '/google-translate-widget.php'; ?>
```

**Logs debug à chercher (Console) :**

```
[SMM Translate] Widget initialisé ✅
[SMM Translate] Dropdown ouvert ✅
[SMM Translate] Traduction déclenchée ✅
```

---

### **RÈGLE 5 : Nouveaux Snippets/Templates**

**Pour toute nouvelle page/template SMM Mastery :**

#### **Template Page Publique**

```php
<?php
require_once 'config.php';
require_once 'functions.php';

$page_title = "Titre Page";
?>
<?php include 'includes/public-header.php'; ?>
<!-- Le widget est déjà inclus dans public-header.php -->

<main>
    <!-- Contenu -->
</main>

<?php include 'includes/public-footer.php'; ?>
```

#### **Template Page Dashboard**

```php
<?php
require_once '../config.php';
require_once '../functions.php';

$page_title_bar = "Dashboard";
?>
<?php include '../includes/dashboard-header-simple.php'; ?>
<!-- Le widget est déjà inclus dans dashboard-top-bar.php -->

<main>
    <!-- Contenu -->
</main>

<?php include '../includes/dashboard-footer-simple.php'; ?>
```

**⚠️ IMPORTANT :** Le widget est **déjà inclus** dans les headers. Ne pas dupliquer !

---

### **RÈGLE 6 : Documentation Automatique**

**Lors de modifications widget/multi-langue :**

1. **Mettre à jour version** dans widget :

```php
/**
 * @version 1.4 - [Description modification]
 * @date [Date]
 */
```

2. **Créer fichier HOTFIX** :

```
DOCS_DEV_TO_PROD/PHASE14_HOTFIX_V1.4_[DESCRIPTION].md
```

3. **Mettre à jour PROGRESS_UPDATED.md** :

```markdown
## Modifications Système Multi-langue

- [Date] v1.4 - [Description]
- Fichiers modifiés: [Liste]
- Tests effectués: [Résultats]
```

---

## 🛠️ WORKFLOWS COMMUNS

### **Workflow 1 : Ajouter Nouvelle Page**

```bash
1. Créer fichier page (ex: pages/new-page.php)
2. Inclure public-header.php
3. Widget automatiquement présent ✅
4. Tester traduction fonctionne
5. Documenter si nécessaire
```

**Code type :**

```php
<?php
require_once '../config.php';
$page_title = "Nouvelle Page";
include '../includes/public-header.php';
?>

<div class="content">
    <h1>Contenu</h1>
    <p>Ce texte sera traduisible automatiquement</p>
</div>

<?php include '../includes/public-footer.php'; ?>
```

---

### **Workflow 2 : Corriger Bug Widget**

```bash
1. Identifier symptôme précis
2. Consulter PHASE14_VALIDATION_TRADUCTION.md (section Dépannage)
3. Activer version debug si nécessaire
4. Reproduire bug
5. Copier logs console
6. Corriger dans google-translate-widget.php
7. Tester 5 langues minimum
8. Documenter correction
9. Mettre à jour version
```

---

### **Workflow 3 : Ajouter Langue**

Si besoin d'ajouter une nouvelle langue :

```javascript
// Dans google-translate-widget.php, section languages:
{ code: 'XX', name: 'Langue', flag: '🏳️', popular: false },
```

**Codes langues Google Translate :**
https://cloud.google.com/translate/docs/languages

**Exemple ajout Néerlandais :**

```javascript
{ code: 'nl', name: 'Nederlands', flag: '🇳🇱', popular: false },
```

**⚠️ Important :**

- Vérifier code langue valide Google Translate
- Ajouter dans `includedLanguages` (automatique via `.map()`)
- Tester que traduction fonctionne

---

### **Workflow 4 : Personnaliser Styles**

Pour adapter le widget à un nouveau design :

```css
/* Fichier custom-translate.css */

/* Changer couleurs bouton */
.smm-translate-btn {
  background: linear-gradient(
    135deg,
    #your-color 0%,
    #your-color2 100%
  ) !important;
}

/* Adapter taille mobile */
@media (max-width: 768px) {
  .smm-translate-btn {
    padding: 6px 12px !important;
    font-size: 12px !important;
  }
}
```

**Charger après widget :**

```html
<?php include 'includes/google-translate-widget.php'; ?>
<link rel="stylesheet" href="assets/css/custom-translate.css" />
```

---

## 🎯 CHECKLIST QUALITÉ

**Avant de valider toute modification :**

```
TESTS OBLIGATOIRES
[ ] Dropdown s'ouvre correctement
[ ] 5 langues testées (EN, ES, AR, ZH, RU minimum)
[ ] Traduction effective visible
[ ] Badge langue mis à jour
[ ] localStorage fonctionne
[ ] Navigation entre pages OK
[ ] Responsive mobile (375px, 768px, 1920px)
[ ] Aucune erreur console
[ ] Logs "[SMM Translate]" présents
[ ] Position dropdown correcte (pas coupé)

DOCUMENTATION
[ ] Version incrémentée dans widget
[ ] Changements documentés
[ ] PROGRESS_UPDATED.md mis à jour
[ ] Guide utilisateur ajusté si UI modifiée

CODE
[ ] Aucun console.log superflu
[ ] Code commenté si complexe
[ ] Pas de duplication code
[ ] Respect conventions projet
```

---

## 📚 RÉFÉRENCES RAPIDES

### **Fichiers Essentiels**

```
includes/google-translate-widget.php          ← Widget principal
includes/public-header.php                    ← Header pages publiques
includes/dashboard-top-bar.php                ← Header dashboard
```

### **Documentation**

```
PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md     ← Doc technique complète
PHASE14_VALIDATION_TRADUCTION.md             ← Tests et validation
PHASE14_HOTFIX_*.md                          ← Historique corrections
```

### **Commandes Console Utiles**

```javascript
// Vérifier widget chargé
typeof window.smmToggleDropdown; // "function"

// Lister langues
document.querySelector(".goog-te-combo").options.length;

// Forcer langue
window.smmChangeLanguage("en", "English");

// Vérifier localStorage
localStorage.getItem("smm_preferred_language");
```

---

## 🚨 ERREURS COURANTES À ÉVITER

### **Erreur 1 : Dupliquer Widget**

```php
<!-- ❌ MAUVAIS -->
<?php include 'includes/public-header.php'; ?>
<?php include 'includes/google-translate-widget.php'; ?> <!-- Déjà dans header ! -->
```

### **Erreur 2 : Modifier Position Dropdown**

```css
/* ❌ MAUVAIS - Casse le widget */
.smm-translate-dropdown {
  position: absolute !important; /* Au lieu de fixed */
}
```

### **Erreur 3 : Oublier Test Traduction**

```
✅ TOUJOURS tester que traduction fonctionne après modification
Pas seulement vérifier que dropdown s'ouvre !
```

### **Erreur 4 : Utiliser Version Debug en Production**

```php
<!-- ❌ JAMAIS EN PRODUCTION -->
<?php include 'includes/google-translate-widget-debug.php'; ?>
```

---

## 💡 BONNES PRATIQUES

### **1. Communication Utilisateur**

Si Claude détecte problème multi-langue :

```
"Je constate un problème avec le système multi-langue.
Laissez-moi consulter la documentation technique..."

[Lit PHASE14_MULTILANGUAGE_SYSTEM_COMPLETE.md]

"D'après la documentation, voici le problème et la solution..."
```

### **2. Modifications Progressives**

Toujours procéder par étapes :

```
1. Comprendre besoin utilisateur
2. Consulter documentation existante
3. Proposer solution documentée
4. Implémenter avec backup
5. Tester exhaustivement
6. Documenter résultat
```

### **3. Logs Explicites**

Ajouter logs clairs si debug :

```javascript
console.log("[SMM Translate][DEBUG] Action effectuée:", data);
```

---

## 🎓 FORMATION CONTINUE

### **Pour Rester À Jour**

- Consulter `PROGRESS_UPDATED.md` régulièrement
- Lire nouveaux `PHASE14_HOTFIX_*.md`
- Vérifier version widget avant modification
- Tester après chaque mise à jour projet

---

## ✅ VALIDATION FINALE

Claude doit pouvoir répondre OUI à :

```
[ ] Je connais l'emplacement du widget principal
[ ] Je sais comment l'inclure dans nouveau header
[ ] Je connais les propriétés CSS critiques
[ ] Je sais activer version debug
[ ] Je connais le workflow de correction bug
[ ] Je sais où documenter mes modifications
[ ] Je connais la checklist tests obligatoires
[ ] Je sais éviter les erreurs courantes
```

---

**📘 Version :** 2.0 - Instructions Enrichies Multi-langue  
**📅 Date :** 14 Octobre 2025  
**✅ Statut :** Instructions Production Ready

**🤖 Claude est maintenant formé au système multi-langue SMM Mastery !**
