# 📖 GUIDE D'UTILISATION - INSTRUCTIONS CLAUDE

## 🎯 MÉTHODES D'UTILISATION SELON L'ENVIRONNEMENT

### 1️⃣ **CLAUDE WEB (claude.ai)**

#### **Méthode A : Attachement de fichier**

1. Ouvrir une nouvelle conversation sur claude.ai
2. Cliquer sur l'icône "📎" (trombone) pour attacher un fichier
3. Sélectionner `CLAUDE_INSTRUCTIONS.md`
4. Écrire votre prompt : "Lis les instructions attachées et aide-moi avec [votre demande]"

#### **Méthode B : Copier-coller du template**

1. Ouvrir `CLAUDE_PROMPT_TEMPLATES.md`
2. Copier le template approprié
3. Coller dans une nouvelle conversation
4. Remplacer `[VOTRE DEMANDE]` par votre besoin spécifique

### 2️⃣ **CLAUDE API (via outils tiers)**

#### **Pour les développeurs utilisant l'API :**

```python
# Exemple Python avec l'API Claude
import anthropic

# Charger les instructions
with open('CLAUDE_INSTRUCTIONS.md', 'r', encoding='utf-8') as f:
    instructions = f.read()

client = anthropic.Anthropic(api_key="votre-clé")
response = client.messages.create(
    model="claude-3-sonnet-20240229",
    system=instructions,  # Instructions en système prompt
    messages=[{"role": "user", "content": "Votre demande ici"}]
)
```

### 3️⃣ **CLAUDE DANS VS CODE (extensions)**

#### **Si vous utilisez une extension Claude pour VS Code :**

1. Configurer l'extension pour utiliser un fichier de contexte
2. Pointer vers `CLAUDE_INSTRUCTIONS.md` comme contexte système
3. Utiliser les templates dans vos prompts

---

## 🚀 WORKFLOW PRATIQUE RECOMMANDÉ

### **POUR UNE SESSION DE DÉVELOPPEMENT :**

#### **Étape 1 : Préparation (1 fois par jour)**

```
Nouvelle conversation Claude → Attacher CLAUDE_INSTRUCTIONS.md →
"Lis ces instructions et confirme que tu les as comprises"
```

#### **Étape 2 : Session active (pour chaque tâche)**

Utiliser ce template :

```
# SESSION ACTIVE - SMM Mastery

Contexte: Tu as les instructions du projet SMM Mastery.

AVANT ACTION:
1. Consulter SESSION_INDEX.md (D:\wamp64\www\smm\DOCS_DEV_TO_PROD\)
2. Vérifier progress\PROGRESS_UPDATED.md
3. Suivre guides appropriés

MA DEMANDE: [Votre demande spécifique]

APRÈS: Documenter changements + mettre à jour progression
```

### **POUR UNE CORRECTION RAPIDE :**

```
# CORRECTION RAPIDE - SMM Mastery

Instructions projet en mémoire.
Problème: [Décrire le problème]
Consulter: 05_FIXES_PATCHES\ pour solutions similaires
Documenter: Mettre à jour progression après correction
```

---

## 📋 TEMPLATES PRÊTS À COPIER

### **Template Complet (Première utilisation)**

```
# PROJET SMM Mastery - CONTEXTE COMPLET

Tu travailles sur SMM Mastery (plateforme PHP/MySQL + PayPal/Stripe + API SMMFollows).

STRUCTURE DOC: D:\wamp64\www\smm\DOCS_DEV_TO_PROD\
- SESSION_INDEX.md = Point d'entrée
- 01_PROJECT_MANAGEMENT\progress\ = États actuels
- 04_DEVELOPMENT_GUIDES\ = Guides développement
- 05_FIXES_PATCHES\ = Corrections
- 02_DATABASE\ = Scripts SQL

RÈGLES:
✅ Consulter doc existante AVANT action
✅ Documenter TOUS changements
✅ Mettre à jour progression après chaque tâche
✅ Respecter structure numérotée

WORKFLOW: SESSION_INDEX → Doc pertinente → Action → Documentation → Planning

[VOTRE DEMANDE ICI]
```

### **Template Rapide (Sessions suivantes)**

```
# SMM Mastery - Tu connais le contexte

Consulter: SESSION_INDEX.md + progress\PROGRESS_UPDATED.md
Demande: [Votre demande]
Après: Documenter + mettre à jour progression
```

---

## 💡 CONSEILS D'UTILISATION

### **✅ BONNES PRATIQUES**

1. **Une conversation = Une session de développement**

   - Attachez `CLAUDE_INSTRUCTIONS.md` en début de conversation
   - Utilisez la même conversation pour toute la session

2. **Utilisez les templates adaptés**

   - Template complet pour nouvelle session
   - Template rapide pour actions dans session active

3. **Soyez spécifique dans vos demandes**
   ```
   ❌ "Aide-moi avec le CSS"
   ✅ "Consulte 05_FIXES_PATCHES\css\ et aide-moi à corriger le responsive de la page dashboard"
   ```

### **🚨 ERREURS À ÉVITER**

❌ Oublier d'attacher les instructions en début de session  
❌ Mélanger plusieurs tâches non liées dans une conversation  
❌ Ne pas spécifier les chemins de documentation  
❌ Oublier de demander la mise à jour de progression

---

## 🔧 AUTOMATISATION (NIVEAU AVANCÉ)

### **Script Windows pour prompt automatique**

```batch
@echo off
echo # SMM Mastery - Session du %date%
echo.
echo Consulter: SESSION_INDEX.md + progress\PROGRESS_UPDATED.md
echo Demande:
set /p demande="Entrez votre demande: "
echo %demande%
echo Après: Documenter + mettre à jour progression
```

### **Extension VS Code personnalisée**

Créer un snippet avec le template pour usage rapide.

---

## 📱 RÉSUMÉ VISUEL

```
NOUVELLE SESSION
       ↓
[Attacher CLAUDE_INSTRUCTIONS.md]
       ↓
[Template selon la tâche]
       ↓
[Développement/Correction]
       ↓
[Demander maj documentation]
       ↓
SESSION TERMINÉE
```

---

**🎯 RÈGLE SIMPLE :** Commencez toujours par attacher `CLAUDE_INSTRUCTIONS.md`, puis utilisez les templates de `CLAUDE_PROMPT_TEMPLATES.md` selon votre besoin !
