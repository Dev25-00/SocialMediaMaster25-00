# 🚀 GUIDE PRATIQUE COPILOT - SMM Mastery

## ⚡ UTILISATION QUOTIDIENNE

### **1. ACTIVATION AUTOMATIQUE**

✅ Copilot est configuré avec `.copilot-instructions` dans la racine  
✅ Les snippets SMM Mastery sont disponibles  
✅ VS Code reconnaît automatiquement le contexte

### **2. SNIPPETS DISPONIBLES**

#### 🔧 **Tapez ces raccourcis dans VS Code :**

| Raccourci | Description              | Usage              |
| --------- | ------------------------ | ------------------ |
| `smmfunc` | Fonction PHP documentée  | Nouvelle fonction  |
| `smmdb`   | Requête base de données  | Accès BDD sécurisé |
| `smmpage` | En-tête page PHP         | Nouvelle page      |
| `smmcss`  | Composant CSS responsive | Nouveau style      |
| `smmauth` | Vérification auth        | Protection page    |
| `smmapi`  | Endpoint API             | Nouvelle API       |

#### 💡 **Exemple d'usage :**

```php
// Tapez "smmfunc" puis TAB
// Copilot génère automatiquement :
/**
 * Function description
 *
 * @param type $param Description
 * @return type Description
 * @throws Exception Si erreur
 *
 * @documentation 04_DEVELOPMENT_GUIDES\domaine\
 * @version 1.0
 * @date 2025-10-12
 */
function functionName($param) {
    try {
        // Implementation
    } catch (Exception $e) {
        error_log('Erreur functionName: ' . $e->getMessage());
        throw $e;
    }
}
```

## 🎯 PROMPTS OPTIMISÉS POUR COPILOT

### **Dans les commentaires PHP :**

```php
// SMM Mastery: Créer fonction de validation email avec sanitisation
// Documentation: 04_DEVELOPMENT_GUIDES\authentication\
// Sécurité: Requis filtrage + échappement HTML
```

### **Dans les commentaires CSS :**

```css
/* SMM Mastery: Responsive card component mobile-first
 * Breakpoints: 768px tablet, 1024px desktop
 * Référence: 05_FIXES_PATCHES\css\
 */
```

### **Dans les commentaires JS :**

```javascript
// SMM Mastery: Menu mobile toggle avec gestion responsive
// Compatible: ES6+, mobile-first
// Inclure: Gestion événements touch
```

## 🔄 WORKFLOW AVEC COPILOT

### **DÉVELOPPEMENT NOUVELLE FONCTIONNALITÉ :**

1. **Commenter l'intention :**

```php
// SMM Mastery: Page de gestion des commandes utilisateur
// Fonctionnalités: Liste, filtrages, pagination, statuts
// Documentation: 04_DEVELOPMENT_GUIDES\orders\
// Base: Utiliser patterns existants dashboard/
```

2. **Copilot propose le code**
3. **Réviser et ajuster selon standards SMM Mastery**
4. **Documenter dans la progression**

### **CORRECTION BUG :**

1. **Identifier le problème :**

```php
// SMM Mastery: Corriger responsive menu mobile
// Problème: Menu ne se ferme pas sur mobile
// Référence: 05_FIXES_PATCHES\css\menu-mobile-fix.md
// Solution: Ajouter gestion événement outside click
```

2. **Laisser Copilot proposer**
3. **Tester sur mobile**
4. **Documenter la correction**

## 🎨 PATTERNS RECONNUS PAR COPILOT

### **STRUCTURE PROJET DÉTECTÉE :**

```
- auth/ → Authentification patterns
- dashboard/ → Interface utilisateur
- admin/ → Interface admin
- api/ → Endpoints REST
- assets/ → Ressources statiques
- includes/ → Composants réutilisables
```

### **CONVENTIONS AUTOMATIQUES :**

- ✅ Sanitisation inputs automatique
- ✅ Requêtes préparées par défaut
- ✅ Gestion erreurs try-catch
- ✅ Documentation inline
- ✅ Responsive mobile-first
- ✅ Sécurité CSRF/XSS

## 💡 TRUCS ET ASTUCES

### **1. COPILOT COMPREND LE CONTEXTE**

```php
// Au lieu de tout expliquer :
// "Créer une fonction de connexion base de données avec PDO,
//  gestion erreurs, sanitisation, etc..."

// Écrivez simplement :
// SMM Mastery: Connexion base de données standard
// Copilot génère automatiquement le code sécurisé !
```

### **2. UTILISER LES FICHIERS EXISTANTS**

Quand vous travaillez sur un fichier, Copilot analyse automatiquement :

- Les `includes/` utilisés
- Les patterns de sécurité
- La structure HTML existante
- Les classes CSS disponibles

### **3. RÉFÉRENCER LA DOCUMENTATION**

```php
// SMM Mastery: Suivre pattern paiement PayPal
// Guide: 04_DEVELOPMENT_GUIDES\payment\PAYPAL_INTEGRATION.md
// Environnement: Sandbox pour développement
```

### **4. DEMANDER DES VARIATIONS**

```php
// SMM Mastery: Adapter ce code pour Stripe au lieu de PayPal
// Copilot proposera automatiquement les modifications
```

## 🚨 VALIDATION COPILOT

### **VÉRIFICATIONS AUTOMATIQUES :**

- ✅ Code suit les standards SMM Mastery
- ✅ Sécurité respectée (sanitisation, etc.)
- ✅ Documentation inline présente
- ✅ Responsive mobile-first
- ✅ Compatibilité avec structure existante

### **SI COPILOT S'ÉCARTE DES STANDARDS :**

```php
// SMM Mastery: IMPORTANT - Respecter patterns existants
// Sécurité: Obligatoire sanitisation + requêtes préparées
// CSS: Mobile-first + utiliser fixes.css
// Documentation: Headers requis + mise à jour progression
```

## 📊 MISE À JOUR PROGRESSION

### **APRÈS DÉVELOPPEMENT AVEC COPILOT :**

```php
/**
 * MISE À JOUR AUTOMATIQUE PROGRESSION
 *
 * Fichier: 01_PROJECT_MANAGEMENT\progress\PROGRESS_UPDATED.md
 * Action: [Décrire ce qui a été développé avec Copilot]
 * Fichiers modifiés: [Liste des fichiers]
 * Tests: [Tests effectués]
 *
 * Prochaines étapes: [Ce qui reste à faire]
 */
```

---

## 🎯 RÉSUMÉ PRATIQUE

### **COPILOT SMM Mastery EN 3 ÉTAPES :**

1. **OUVRIR VS CODE** → Projet SMM automatiquement reconnu
2. **COMMENTER INTENTION** → "SMM Mastery: [Ce que vous voulez]"
3. **LAISSER COPILOT PROPOSER** → Code automatiquement optimisé

### **RACCOURCIS ESSENTIELS :**

- `Ctrl+I` → Chat Copilot inline
- `Tab` → Accepter suggestion
- `Ctrl+→` → Accepter mot par mot
- `Esc` → Rejeter suggestion

---

**🔑 RÈGLE D'OR :** Copilot connaît SMM Mastery, faites-lui confiance mais vérifiez toujours la cohérence avec l'existant !
