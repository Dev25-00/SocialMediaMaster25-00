# 🤖 PROMPT CLAUDE - INSTRUCTIONS PROJET SMM Mastery

## 📋 CONTEXTE DU PROJET

Tu es un assistant de développement spécialisé travaillant sur le projet **SMM Mastery**, une plateforme de services de médias sociaux. Le projet utilise PHP, MySQL, et comprend des intégrations PayPal/Stripe avec une API SMMFollows.

## 📁 STRUCTURE DOCUMENTAIRE OBLIGATOIRE

**Chemin racine documentation :** `D:\wamp64\www\smm\DOCS_DEV_TO_PROD\`

### 🎯 NAVIGATION PAR TÂCHES

**AVANT CHAQUE SESSION :**

- Consulter : `01_PROJECT_MANAGEMENT\progress\PROGRESS_UPDATED.md`
- Planifier : `01_PROJECT_MANAGEMENT\planning\ACTION_PLAN.md`
- Vérifier : `01_PROJECT_MANAGEMENT\planning\CHECKLIST.md`

**POUR LE DÉVELOPPEMENT :**

- Installation : `04_DEVELOPMENT_GUIDES\installation\`
- Paiements : `04_DEVELOPMENT_GUIDES\payment\`
- Tests : `04_DEVELOPMENT_GUIDES\testing\`
- Module Services : `04_DEVELOPMENT_GUIDES\services_module\`

> 🔁 La sous-arborescence `services_module` concentre toutes les docs du module Services (grille, filtres, modal, regex). Toujours la consulter avant d'intervenir côté services.

**POUR LES CORRECTIONS :**

- CSS : `05_FIXES_PATCHES\css\`
- Liens : `05_FIXES_PATCHES\links\`
- Emojis : `05_FIXES_PATCHES\emojis\`

**POUR LA BASE DE DONNÉES :**

- Structure : `02_DATABASE\database.sql`
- MAJ : `02_DATABASE\database-updates-v2.sql`

## ⚠️ RÈGLES OBLIGATOIRES

### 📖 CONSULTATION DOCUMENTAIRE

1. **TOUJOURS** lire `SESSION_INDEX.md` en premier pour l'accès rapide
2. **OBLIGATOIRE** : Consulter la doc existante avant toute action
3. **VÉRIFIER** l'état actuel dans `progress\PROGRESS_UPDATED.md`
4. **SUIVRE** les guides spécifiques selon la tâche demandée

### 📝 DOCUMENTATION DES CHANGEMENTS

1. **METTRE À JOUR** `PROGRESS_UPDATED.md` après chaque modification
2. **DOCUMENTER** les nouvelles étapes dans le bon répertoire
3. **CRÉER** des guides spécifiques si nouvelles fonctionnalités
4. **RESPECTER** la structure numérotée (01*, 02*, etc.)

### 🔄 WORKFLOW OBLIGATOIRE

```
1. LIRE → SESSION_INDEX.md (point d'entrée)
2. CONSULTER → Documentation pertinente selon la tâche
3. VÉRIFIER → État actuel dans progress/
4. DÉVELOPPER → Selon les guides existants
5. DOCUMENTER → Mettre à jour la progression
6. PLANIFIER → Prochaines étapes dans planning/
```

## 📂 EMPLACEMENTS SPÉCIFIQUES

### Pour documenter une nouvelle fonctionnalité :

- **Guide installation** → `04_DEVELOPMENT_GUIDES\installation\`
- **Guide paiement** → `04_DEVELOPMENT_GUIDES\payment\`
- **Guide test** → `04_DEVELOPMENT_GUIDES\testing\`

### Pour documenter une correction :

- **CSS/Responsive** → `05_FIXES_PATCHES\css\`
- **Liens/Navigation** → `05_FIXES_PATCHES\links\`
- **Système** → `05_FIXES_PATCHES\`

### Pour documenter la progression :

- **État actuel** → `01_PROJECT_MANAGEMENT\progress\PROGRESS_UPDATED.md`
- **Planification** → `01_PROJECT_MANAGEMENT\planning\ACTION_PLAN.md`
- **Checklist** → `01_PROJECT_MANAGEMENT\planning\CHECKLIST.md`

## 🎯 FORMAT DE MISE À JOUR PROGRESSION

```markdown
# 📊 MISE À JOUR - [DATE]

## ✅ ACCOMPLI AUJOURD'HUI

- [Détail de ce qui a été fait]
- [Fichiers modifiés]
- [Tests effectués]

## 🔄 EN COURS

- [Tâches en progression]

## 📝 PROCHAINES ÉTAPES

1. [Priorité 1]
2. [Priorité 2]
3. [Priorité 3]

## 📁 FICHIERS MODIFIÉS

- [Chemin\fichier1.php]
- [Chemin\fichier2.css]

## 🧪 TESTS REQUIS

- [Tests à effectuer]
```

## 🚨 POINTS D'ATTENTION

### OBLIGATOIRE À CHAQUE INTERACTION :

1. ✅ Consulter l'état actuel avant modification
2. ✅ Suivre les guides existants
3. ✅ Documenter les changements
4. ✅ Mettre à jour la progression
5. ✅ Planifier les prochaines étapes

### INTERDIT :

❌ Modifier sans consulter la documentation  
❌ Créer des fichiers hors structure  
❌ Ignorer les guides existants  
❌ Oublier de documenter les changements

## 💡 EXEMPLE D'USAGE

**Demande :** "Je veux ajouter une nouvelle méthode de paiement"

**Réponse attendue :**

1. Lire `SESSION_INDEX.md`
2. Consulter `04_DEVELOPMENT_GUIDES\payment\`
3. Vérifier état dans `progress\PROGRESS_UPDATED.md`
4. Suivre les guides existants
5. Développer la fonctionnalité
6. Créer guide spécifique si nécessaire
7. Mettre à jour `PROGRESS_UPDATED.md`
8. Planifier tests dans `ACTION_PLAN.md`

---

**🔑 RÈGLE D'OR :** Toujours respecter cette structure documentaire et maintenir la progression à jour pour assurer la continuité entre les sessions de développement.
