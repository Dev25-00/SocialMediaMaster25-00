# 📋 CHANGELOG - Module Services

Toutes les modifications notables du module Services sont documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

---

## [3.2.0] - 2025-10-14

### ✅ Ajouté

- **Validation REGEX du Link/URL** : Nouveau système de validation intelligent

  - Support URL complètes : `https://instagram.com/username`
  - Support usernames simples : `@username` ou `username`
  - Support IDs numériques : `123456789012345`
  - Détection automatique de 15+ plateformes populaires
  - Feedback visuel en temps réel avec 3 types : error (rouge), warning (jaune), info (bleu)
  - Fonction `validateLink()` avec patterns regex avancés
  - Fonction `showLinkValidation()` pour affichage feedback
  - Fichiers : `js/order-modal.js` (lignes ~1310-1450)

- **Layout 2 Colonnes Desktop** : Nouvelle organisation du modal

  - **Colonne gauche** : Formulaire de commande (max-width: 600px)
  - **Colonne droite** : Panneau d'informations (max-width: 450px)
  - Responsive : Colonne unique sur mobile (< 1024px)
  - Border séparatrice entre les colonnes
  - Fichiers : `js/order-modal.js`, `css/order-modal.css`

- **Panneau "Important Requirements"** : Warnings visuels pour l'utilisateur
  - Box jaune avec icône d'avertissement
  - 3 exigences affichées :
    - 🔓 **Public Account Required** - Compte public obligatoire
    - ✓ **Valid Link Format** - Format de lien valide requis
    - ⏰ **Processing Time** - Délai de traitement indiqué
  - Design avec icônes Font Awesome
  - Visible sur desktop (colonne droite), en bas sur mobile
  - Fichiers : `js/order-modal.js` (structure HTML), `css/order-modal.css` (styles)

### 🎨 Amélioré

- **UX Validation Link** : Feedback instantané lors de la saisie

  - Validation déclenchée sur event `input`
  - Messages contextuels selon le type d'erreur
  - Couleurs adaptées : rouge (erreur), jaune (warning), bleu (info)
  - Animation `slideDown` pour apparition douce
  - Masquage automatique si lien valide

- **Organisation visuelle Desktop** : Layout moderne et fonctionnel

  - Formulaire à gauche (focus sur la saisie)
  - Informations à droite (contexte et aide)
  - Utilisation optimale de l'espace disponible
  - Scroll indépendant des deux colonnes

- **Responsive Mobile** : Adaptation intelligente
  - Panneau d'informations déplacé en bas
  - Border-top au lieu de border-right
  - Padding réduit (16px au lieu de 30px)
  - Tailles de police adaptées
  - Gap réduit entre les éléments

### 🔒 Sécurité

- **Validation stricte des URLs** : Protection contre injections
  - Regex patterns sécurisés
  - Vérification de la présence de protocole (http/https)
  - Détection des espaces (interdits dans URLs)
  - Vérification longueur minimum (3 caractères)
  - Validation domaines connus pour détecter tentatives de phishing

### 🐛 Corrigé

- **Type input Link** : Changé de `type="url"` à `type="text"`
  - Le type `url` du navigateur est trop strict
  - Empêchait la saisie de usernames simples
  - Notre validation regex est plus flexible et précise

### 🔧 Technique

- Patterns regex optimisés pour performance
- Méthode `validateLink()` retourne objet `{ isValid, message, type }`
- Méthode `showLinkValidation()` gère l'affichage du feedback
- CSS Grid/Flexbox pour layout responsive
- Media queries : `@media (min-width: 1024px)` pour desktop
- Classes CSS modulaires : `.order-link-error`, `.order-link-warning`, `.order-link-info`
- Event listener ajouté sur input link pour validation temps réel

---

## [3.1.0] - 2025-10-14

### ✅ Ajouté

- **Validation complète du formulaire de commande** : Nouvelle fonction `validateForm()`
  - Validation en temps réel lors de la saisie (link, quantity, drip-feed)
  - Désactivation automatique du bouton "Place Order" si formulaire invalide
  - Messages d'erreur dynamiques et contextuels sur le bouton
  - Vérification du solde utilisateur avant soumission
  - Validation des limites min/max de quantité
  - Validation des champs drip-feed (runs >= 2, interval >= 1)
  - Classe CSS `.btn-disabled` pour styling du bouton désactivé
  - Logs console détaillés pour débogage
  - Fichiers : `js/order-modal.js`, `css/order-modal.css`
  - Documentation : `VALIDATION_ORDER_MODAL_V3.1.md`

### 🎨 Amélioré

- **Bouton de soumission** : Feedback visuel amélioré
  - Couleur rouge si formulaire invalide (dégradé #ef4444 → #dc2626)
  - Messages dynamiques : "Insufficient Balance", "Min: 1K", "Max: 10M", etc.
  - Opacité réduite (70%) quand désactivé
  - Suppression des effets hover quand désactivé
  - Cursor `not-allowed` pour indiquer l'état désactivé

### 🔒 Sécurité

- **Double validation** : Côté client ET avant soumission
  - Validation dans `validateForm()` en temps réel
  - Validation obligatoire dans `submitOrder()` avant fetch API
  - Protection contre bypass JavaScript
  - Impossible de soumettre avec solde insuffisant
  - Respect strict des limites min/max du service

### 🐛 Corrigé

- **Soumission de commandes invalides** : Désormais impossible
  - Quantité en dehors des limites min/max
  - Solde insuffisant pour couvrir la commande
  - Champs requis manquants (link, quantity)
  - Configuration drip-feed incomplète

### 🔧 Technique

- Event listeners sur `input` pour validation temps réel
- Calcul précis du solde avec `calculatePrecisePrice()` (8 décimales)
- Formatage adaptatif des nombres (1K, 10M, etc.)
- Priorisation des messages d'erreur (solde → quantité → autres)
- Return boolean de `validateForm()` pour chaînage de validation

---

## [3.0.2] - 2025-10-14

### ✅ Ajouté

- **Recherche par ID** : Nouveau champ de recherche dans la barre de filtres
  - Champ input compact avec placeholder "ID service..."
  - Bouton "×" pour effacer la recherche (apparaît automatiquement)
  - Debounce 300ms pour optimiser les requêtes API
  - Soumission immédiate avec touche Entrée
  - Responsive : 95px→120px (desktop), 60px→75px (mobile)
  - Animation glow quand le champ est actif
  - Fichiers : `index.php`, `css/filters.css`, `css/mobile-filters.css`, `js/services-manager.js`

### 🐛 Corrigé

- **API search_id** : Ajout du support du paramètre `search_id` dans l'API

  - Recherche **exacte** par `provider_id` (l'ID du service chez le fournisseur)
  - Condition : `provider_id = :search_id` pour garantir précision 100%
  - Priorité : La recherche par ID filtre en premier (avant autres filtres)
  - Fichier : `api/services.php`
  - ⚠️ Note : Utilise `provider_id` (colonne BDD) et non `service_id`

- **Affichage ID service** : L'ID du service est maintenant visible dans chaque card
  - Badge "ID: XXXXX" affiché dans le header de la card
  - Cliquable pour copier l'ID dans le presse-papier
  - Utilise `provider_id` pour la recherche exacte
  - Fichier : `js/services-manager.js`
  - Fix : Correction du doublon de déclaration `idBadge`

### 🔧 Technique

- Encodage URL sécurisé avec `encodeURIComponent()`
- Pattern SQL avec requête préparée (protection injection SQL)
- Event listeners : input (debounced), blur, keypress (Enter), click (clear)
- Intégration complète avec système de filtres existant
- Copie au clic de l'ID avec notification toast

---

## [3.0.1] - 2025-10-14

### 🐛 Corrigé

- **Responsive mobile** : Prix et bouton "Buy" décalés à droite et à peine visibles
  - Layout cards passé de horizontal (row) à vertical (column) en mobile
  - Footer (prix + bouton) prend maintenant 100% de la largeur
  - Prix augmenté à 16px (au lieu de 11-12px)
  - Bouton Buy agrandi : 8px x 16px padding, min-width 70px
  - Meilleure séparation visuelle avec border-top
  - Fichier : `css/mobile-filters.css` (+90 lignes)
  - Documentation : `DOCS_DEV_TO_PROD\HOTFIX_MOBILE_CARDS_PRIX_BOUTON.md`

### 🔧 Technique

- Breakpoints : <599px (mobile), <400px (très petit mobile)
- Utilisation de !important pour priorité sur styles desktop

---

## [3.0.0] - 2025-10-14

### ✅ Ajouté

- **Structure organisée** : Création des dossiers `css/`, `js/`, `archive/`
- **Documentation complète** : README.md principal (300+ lignes)
- **Documentation archives** : README.md dans archive/ (100+ lignes)
- **Commentaires JSDoc** : ~150 lignes de documentation JavaScript
- **Commentaires PHP** : ~50 lignes de documentation dans index.php
- **Rapport de réorganisation** : PHASE13_SERVICES_REORGANISATION_RAPPORT.md

### 🔄 Modifié

- **Renommé** : `filters-2lines.css` → `css/filters.css`
- **Renommé** : `mobile-filters-fix-v3.css` → `css/mobile-filters.css`
- **Renommé** : `services-manager-multiline.js` → `js/services-manager.js`
- **Renommé** : `CARDS_ENHANCEMENT_V2.js` → `js/cards-enhancement.js`
- **Déplacé** : `order-modal.css` → `css/order-modal.css`
- **Déplacé** : `order-modal.js` → `js/order-modal.js`
- **Mis à jour** : `index.php` - Tous les liens CSS/JS vers nouveaux chemins
- **Amélioré** : Headers de tous les fichiers avec documentation complète
- **Commenté** : Toutes les propriétés et méthodes principales

### 📦 Archivé

- `filters-multiline.css` → `archive/`
- `mobile-filters-fix.css` (v1) → `archive/`
- `mobile-filters-fix-v2.css` (v2) → `archive/`
- `services-manager-multiline.js` (v2) → `archive/`
- `CARDS_ENHANCEMENT_V2.js` (v2) → `archive/`
- `order-modal.js` (original) → `archive/`

### 🗑️ Supprimé

- Aucun fichier supprimé (tous archivés pour historique)

### 🔧 Technique

- **Compatibilité** : Tous les liens fonctionnent avec nouveaux chemins
- **Versioning** : Cache-busting avec `?v=<?php echo time(); ?>`
- **Performance** : Aucune régression, même structure de code

---

## [2.9.0] - 2025-10-13

### ✅ Ajouté

- **Métadonnées enrichies** : Quality, Location, Speed badges
- **Filtres améliorés** : Drop rate, Refill type détaillé
- **Responsive optimisé** : Mobile ultra-compact (v3)

### 🔄 Modifié

- **Filtres** : Passage à 2 lignes (suppression 3ème ligne)
- **Grid** : Optimisation 1-5 colonnes selon écran
- **Modal** : Amélioration UX avec tabs

---

## [2.5.0] - 2025-10-12

### ✅ Ajouté

- **Cards modernes** : Design avec badges et métadonnées
- **Filtres sticky** : Barre de filtres collée en haut lors du scroll
- **Infinite scroll** : Chargement progressif 20 services/page

### 🔄 Modifié

- **UI/UX** : Refonte complète des services cards
- **Filtres** : Multi-lignes avec icons et couleurs

---

## [2.0.0] - 2025-10-10

### ✅ Ajouté

- **API JavaScript** : Chargement asynchrone des services
- **AbortController** : Annulation des requêtes en cours
- **Debounce** : Optimisation filtres rapides

### 🔄 Modifié

- **Architecture** : Passage de PHP à JavaScript pour chargement
- **Performance** : Réduction temps de chargement initial

---

## [1.0.0] - 2025-09-01

### ✅ Ajouté

- **Version initiale** : Page services avec filtres basiques
- **Modal commande** : Création de commande basique
- **Grid responsive** : 1-3 colonnes selon écran

---

## Types de Changements

- **✅ Ajouté** : Nouvelles fonctionnalités
- **🔄 Modifié** : Changements dans fonctionnalités existantes
- **🗑️ Supprimé** : Fonctionnalités supprimées
- **🐛 Corrigé** : Corrections de bugs
- **🔒 Sécurité** : Corrections de vulnérabilités
- **📦 Archivé** : Fichiers déplacés dans archive/
- **⚡ Performance** : Améliorations de performance
- **🎨 Style** : Changements visuels sans impact fonctionnel
- **📝 Documentation** : Changements dans la documentation uniquement
- **🔧 Technique** : Changements internes sans impact utilisateur

---

**Dernière mise à jour :** 14 Octobre 2025  
**Mainteneur :** Équipe SMM Mastery  
**Format :** Keep a Changelog 1.0.0
