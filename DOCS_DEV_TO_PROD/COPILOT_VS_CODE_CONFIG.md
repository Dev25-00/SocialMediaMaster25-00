# ⚙️ CONFIGURATION COPILOT VS CODE - SMM Mastery

## 📁 FICHIER .copilot-instructions

**Emplacement :** Racine du projet `D:\wamp64\www\smm\.copilot-instructions`

### 📝 **Contenu du fichier .copilot-instructions :**

```
# SMM Mastery Project - GitHub Copilot Instructions

## Project Context
- **Name:** SMM Mastery
- **Type:** Social Media Management Platform
- **Stack:** PHP 8+, MySQL, JavaScript, CSS, HTML
- **Integrations:** PayPal, Stripe, SMMFollows API
- **Environment:** WAMP64 (Windows)

## Documentation Structure
- **Root:** DOCS_DEV_TO_PROD/
- **Guides:** 04_DEVELOPMENT_GUIDES/
- **Fixes:** 05_FIXES_PATCHES/
- **Database:** 02_DATABASE/
- **Progress:** 01_PROJECT_MANAGEMENT/progress/

## Code Standards

### PHP
- Use PHP 8+ features
- Always sanitize inputs: htmlspecialchars(), filter_var()
- Use prepared statements for database queries
- Include proper error handling with try-catch
- Add comprehensive docblocks with @param, @return, @throws
- Follow existing authentication patterns from auth/ folder

### CSS
- Mobile-first responsive design
- Always include fixes.css for responsive corrections
- Use existing CSS classes from main.css
- Follow BEM naming convention where applicable
- Support for IE11+ (legacy browser support)

### JavaScript
- ES6+ syntax preferred
- Always include mobile-menu.js on pages with navigation
- Use vanilla JavaScript (no external libraries unless specified)
- Add event listeners after DOM content loaded
- Include proper error handling

### HTML
- Semantic HTML5 structure
- Include proper meta tags for responsive design
- Use existing header/footer includes: includes/public-header.php, includes/public-footer.php
- Maintain accessibility standards (alt tags, proper heading hierarchy)

## Security Requirements
- CSRF protection on all forms
- SQL injection prevention with prepared statements
- XSS prevention with proper output encoding
- Session management following existing patterns
- Input validation on both client and server side

## File Structure Patterns
- **Public pages:** Root directory (index.php, etc.)
- **Authentication:** auth/ folder
- **Dashboard:** dashboard/ folder
- **Admin:** admin/ folder
- **API endpoints:** api/ folder
- **Assets:** assets/css/, assets/js/, assets/images/
- **Includes:** includes/ folder for reusable components

## Database Patterns
- Use existing PDO connection from config.php
- Follow naming convention: snake_case for tables and columns
- Always use transactions for multi-table operations
- Include proper error logging with error_log()

## Documentation Requirements
- Update progress files in 01_PROJECT_MANAGEMENT/progress/ after significant changes
- Document new features in appropriate 04_DEVELOPMENT_GUIDES/ subfolder
- Record bug fixes in 05_FIXES_PATCHES/ with detailed explanations
- Include inline comments for complex business logic

## Responsive Design Rules
- Mobile breakpoint: 320px-767px
- Tablet breakpoint: 768px-1023px
- Desktop breakpoint: 1024px+
- Always test on mobile devices
- Ensure touch-friendly interface (44px minimum touch targets)

## Integration Guidelines
- **PayPal:** Use sandbox for development, document in 04_DEVELOPMENT_GUIDES/payment/
- **Stripe:** Follow existing patterns, include proper webhooks
- **SMMFollows API:** Document API calls, handle rate limiting
- **Email:** Use existing EmailManager.php class

Remember: Always maintain consistency with existing code patterns and update documentation when adding new features.
```

## 🔧 **SNIPPETS VS CODE**

**Emplacement :** `.vscode/snippets/php.json`

```json
{
  "SMM Mastery PHP Function": {
    "prefix": "smmfunc",
    "body": [
      "/**",
      " * ${1:Function description}",
      " * ",
      " * @param ${2:type} $${3:param} ${4:Description}",
      " * @return ${5:type} ${6:Description}",
      " * @throws Exception Si erreur",
      " * ",
      " * @documentation 04_DEVELOPMENT_GUIDES\\${7:domaine}\\",
      " * @version 1.0",
      " * @date ${CURRENT_YEAR}-${CURRENT_MONTH}-${CURRENT_DATE}",
      " */",
      "function ${8:functionName}($${3:param}) {",
      "    try {",
      "        ${0:// Implementation}",
      "    } catch (Exception $e) {",
      "        error_log('Erreur ${8:functionName}: ' . $e->getMessage());",
      "        throw $e;",
      "    }",
      "}"
    ],
    "description": "Template fonction PHP SMM Mastery"
  },

  "SMM Mastery Database Query": {
    "prefix": "smmdb",
    "body": [
      "try {",
      "    $stmt = $pdo->prepare(\"${1:SELECT * FROM table WHERE column = ?}\");",
      "    $stmt->execute([${2:$param}]);",
      "    $${3:result} = $stmt->${4|fetch,fetchAll|}(PDO::FETCH_ASSOC);",
      "    ${0}",
      "} catch (PDOException $e) {",
      "    error_log('Erreur base de données: ' . $e->getMessage());",
      "    throw new Exception('Erreur lors de la requête');",
      "}"
    ],
    "description": "Template requête base de données SMM Mastery"
  },

  "SMM Mastery Page Header": {
    "prefix": "smmpage",
    "body": [
      "<?php",
      "/**",
      " * SMM Mastery - ${1:Page Title}",
      " * ${2:Page description}",
      " * ",
      " * @author SMM Mastery Team",
      " * @version 1.0",
      " * @date ${CURRENT_YEAR}-${CURRENT_MONTH}-${CURRENT_DATE}",
      " * @documentation 04_DEVELOPMENT_GUIDES\\${3:domaine}\\",
      " */",
      "",
      "// Configuration et sécurité",
      "require_once __DIR__ . '/${4|config.php,../config.php,../../config.php|}';",
      "",
      "// Vérification authentification (si nécessaire)",
      "// if (!isset($_SESSION['user_id'])) {",
      "//     header('Location: ../auth/login.php');",
      "//     exit();",
      "// }",
      "",
      "${0}"
    ],
    "description": "Template en-tête page PHP SMM Mastery"
  }
}
```

## 📋 **SETTINGS VS CODE**

**Emplacement :** `.vscode/settings.json`

```json
{
  "github.copilot.enable": {
    "*": true,
    "plaintext": false,
    "markdown": true,
    "scminput": false
  },
  "github.copilot.editor.enableAutoCompletions": true,
  "github.copilot.advanced": {
    "debug.overrideEngine": "codex"
  },
  "files.associations": {
    "*.php": "php",
    ".copilot-instructions": "plaintext"
  },
  "php.suggest.basic": true,
  "php.validate.enable": true,
  "emmet.includeLanguages": {
    "php": "html"
  },
  "files.exclude": {
    "**/node_modules": true,
    "**/vendor": true,
    "**/.git": true,
    "**/logs": true
  }
}
```

---

## 🚀 **INSTALLATION**

### **Étapes d'installation :**

1. **Créer le fichier d'instructions :**

```powershell
# Dans la racine du projet SMM
New-Item -Path "D:\wamp64\www\smm\.copilot-instructions" -ItemType File
# Copier le contenu du template ci-dessus
```

2. **Créer le dossier .vscode :**

```powershell
New-Item -Path "D:\wamp64\www\smm\.vscode" -ItemType Directory
New-Item -Path "D:\wamp64\www\smm\.vscode\snippets" -ItemType Directory
```

3. **Ajouter les snippets :**

```powershell
# Créer php.json avec les snippets
New-Item -Path "D:\wamp64\www\smm\.vscode\snippets\php.json" -ItemType File
```

4. **Configurer VS Code :**

```powershell
New-Item -Path "D:\wamp64\www\smm\.vscode\settings.json" -ItemType File
```

---

**💡 RÉSULTAT :** Copilot comprendra automatiquement le contexte SMM Mastery et proposera du code cohérent avec votre projet !
