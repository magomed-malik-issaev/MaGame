# Documentation Frontend - MaGame

## Technologies utilisées

- **Templating** : Blade (Laravel)
- **CSS** : Tailwind CSS
- **JavaScript** : Alpine.js pour les interactions
- **Responsive Design** : Compatible mobile, tablette et desktop

## Structure des vues

### Layouts
- `layouts/app.blade.php` - Layout principal avec navigation et footer
- `layouts/guest.blade.php` - Layout pour visiteurs non-connectés

### Composants
- `components/application-logo.blade.php` - Logo de l'application
- `components/button.blade.php` - Boutons réutilisables
- `components/input.blade.php` - Champs de formulaire
- `components/nav-link.blade.php` - Liens de navigation
- `components/dropdown.blade.php` - Menu déroulant

### Pages principales
- **Accueil** (`games/index.blade.php`) - Affiche les jeux populaires et nouveaux
- **Catalogue** (`games/all.blade.php`) - Liste tous les jeux avec filtres
- **Détail du jeu** (`games/show.blade.php`) - Informations détaillées sur un jeu
- **Collection** (`games/my-games.blade.php`) - Collection personnelle de l'utilisateur
- **Recherche** (`games/search.blade.php`) - Résultats de recherche

### Système d'authentification
- `auth/*` - Pages de connexion, inscription, réinitialisation de mot de passe

## Interface utilisateur

### Thème et design
- **Palette de couleurs** : Principalement des nuances de violet, gris foncé et blanc
- **Police de caractères** : Système par défaut (sans-serif)
- **Icônes** : SVG inline

### Composants UI customisés
- Cards pour les jeux
- Système de notation avec étoiles
- Formulaires stylisés
- Badges pour les statuts (favori, en cours, terminé)

## Fonctionnalités JavaScript

### Interactions
- Formulaires d'ajout/suppression de jeux dans la collection (AJAX)
- Système de notation dynamique
- Filtrage des jeux en temps réel
- Pagination côté client et serveur

### Librairies additionnelles
- `alpinejs` - Pour la gestion des interactions légères
- `axios` - Pour les requêtes AJAX

## Ressources statiques

- **CSS** : `public/css/app.css` (compilé depuis Tailwind)
- **JavaScript** : `public/js/app.js`
- **Images** : `public/images/`
- **Icônes** : Intégrées via SVG inline

## Optimisations

- Lazy loading des images
- Mise en cache des éléments d'UI fréquemment utilisés
- Responsive design pour tous les appareils