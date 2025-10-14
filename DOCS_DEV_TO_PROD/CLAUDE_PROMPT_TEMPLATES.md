# 📋 PROMPT TEMPLATE POUR CLAUDE

## 🎯 PROMPT À COPIER-COLLER

```
# CONTEXTE PROJET SMM Mastery

Tu travailles sur le projet SMM Mastery (plateforme PHP/MySQL de services médias sociaux).

## STRUCTURE DOCUMENTATION OBLIGATOIRE
Racine: D:\wamp64\www\smm\DOCS_DEV_TO_PROD\

AVANT TOUTE ACTION:
1. Lire SESSION_INDEX.md (point d'entrée)
2. Consulter 01_PROJECT_MANAGEMENT\progress\PROGRESS_UPDATED.md (état actuel)
3. Vérifier 01_PROJECT_MANAGEMENT\planning\ACTION_PLAN.md (plan)

GUIDES PAR DOMAINE:
- Installation: 04_DEVELOPMENT_GUIDES\installation\
- Paiements: 04_DEVELOPMENT_GUIDES\payment\
- Tests: 04_DEVELOPMENT_GUIDES\testing\
- CSS/Corrections: 05_FIXES_PATCHES\css\
- Liens: 05_FIXES_PATCHES\links\
- Base données: 02_DATABASE\

RÈGLES:
✅ Consulter doc existante AVANT modification
✅ Documenter TOUS les changements dans progress\
✅ Respecter la structure numérotée
✅ Mettre à jour ACTION_PLAN.md après chaque session

WORKFLOW:
SESSION_INDEX.md → Doc pertinente → Développement → Mise à jour progression → Planification suivante

[VOTRE DEMANDE ICI]
```

## 🔧 PROMPT ADAPTÉ PAR TÂCHE

### 🛠️ POUR DÉVELOPPEMENT NOUVEAU

```
# DÉVELOPPEMENT - PROJET SMM Mastery

Contexte: [Votre demande]

OBLIGATOIRE AVANT ACTION:
1. Lire SESSION_INDEX.md
2. Consulter 04_DEVELOPMENT_GUIDES\ selon le domaine
3. Vérifier état dans progress\PROGRESS_UPDATED.md

APRÈS DÉVELOPPEMENT:
1. Documenter dans le guide approprié
2. Mettre à jour PROGRESS_UPDATED.md
3. Ajouter étapes suivantes dans ACTION_PLAN.md

[Votre demande spécifique]
```

### 🔧 POUR CORRECTIONS/BUGS

```
# CORRECTION - PROJET SMM Mastery

Contexte: [Votre problème]

OBLIGATOIRE:
1. Consulter SESSION_INDEX.md
2. Vérifier 05_FIXES_PATCHES\ pour corrections similaires
3. Lire état actuel dans progress\PROGRESS_UPDATED.md

APRÈS CORRECTION:
1. Documenter dans 05_FIXES_PATCHES\[domaine]\
2. Mettre à jour progression
3. Planifier tests de validation

[Votre problème spécifique]
```

### 📊 POUR SUIVI/PLANNING

```
# SUIVI PROJET - SMM Mastery

CONSULTER:
1. SESSION_INDEX.md (navigation)
2. 01_PROJECT_MANAGEMENT\progress\ (états)
3. 01_PROJECT_MANAGEMENT\planning\ (plans)

METTRE À JOUR:
- PROGRESS_UPDATED.md avec nouvel état
- ACTION_PLAN.md avec prochaines étapes
- CHECKLIST.md si nécessaire

[Votre demande de suivi]
```

## 💡 EXEMPLES CONCRETS

### Exemple 1 - Nouvelle fonctionnalité

```
# DÉVELOPPEMENT - PROJET SMM Mastery

Je veux ajouter un système de notifications email.

OBLIGATOIRE AVANT ACTION:
1. Lire SESSION_INDEX.md
2. Consulter 04_DEVELOPMENT_GUIDES\ pour fonctionnalités similaires
3. Vérifier état dans progress\PROGRESS_UPDATED.md

APRÈS DÉVELOPPEMENT:
1. Créer guide dans 04_DEVELOPMENT_GUIDES\
2. Mettre à jour PROGRESS_UPDATED.md
3. Ajouter tests dans ACTION_PLAN.md
```

### Exemple 2 - Correction CSS

```
# CORRECTION - PROJET SMM Mastery

Le responsive ne fonctionne pas sur la page de commandes.

OBLIGATOIRE:
1. Consulter SESSION_INDEX.md
2. Vérifier 05_FIXES_PATCHES\css\ pour corrections CSS
3. Lire état actuel dans progress\PROGRESS_UPDATED.md

APRÈS CORRECTION:
1. Documenter dans 05_FIXES_PATCHES\css\
2. Mettre à jour progression
3. Planifier tests multi-devices
```

---

## 🎯 PROMPT MINIMAL (VERSION COURTE)

```
# SMM Mastery - Structure doc: D:\wamp64\www\smm\DOCS_DEV_TO_PROD\

AVANT: Lire SESSION_INDEX.md + progress\PROGRESS_UPDATED.md
GUIDES: 04_DEVELOPMENT_GUIDES\ | FIXES: 05_FIXES_PATCHES\
APRÈS: Documenter changements + mettre à jour progression

[VOTRE DEMANDE]
```

---

**💡 CONSEIL :** Utilisez le prompt adapté à votre tâche pour des instructions plus précises !
