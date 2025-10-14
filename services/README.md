# 📦 Module Services

Cette page regroupe uniquement le code actif du module services. Toute la documentation détaillée (guides, changelog, wireframes, correctifs) a été déplacée vers le répertoire central afin de conserver un dossier applicatif léger.

## 📚 Documentation

- **Chemin principal :** `DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\services_module\`
- Contenu : `README.md`, `QUICK_START.md`, `CHANGELOG.md`, guides de validation, wireframes, correctifs, etc.
- Les en-têtes de fichiers PHP/JS/CSS pointent désormais vers ce dossier pour garder les références à jour.

## 📁 Structure actuelle

```
services/
├── index.php          # Page principale (grille + filtres + modal)
├── css/               # Styles actifs du module
├── js/                # Scripts actifs (services manager, modal, cards)
└── archive/           # Anciennes versions conservées pour référence
```

## 🔁 Archivage

- Les anciennes implémentations (CSS/JS) restent disponibles dans `archive/`.
- Reportez-vous à `archive/README.md` pour l'historique détaillé et les chemins d'origine.

## ✅ Bonnes pratiques

- Mettre à jour les guides situés dans `services_module` pour toute évolution du module.
- Conserver `services/` comme dossier exécutable : pas de documentation volumineuse ici.
- Respecter les conventions décrites dans `.github/instructions/` et les guides Copilot.
