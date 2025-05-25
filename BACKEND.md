# Documentation Backend - MaGame

## Technologies et frameworks

- **Framework** : Laravel 10
- **PHP** : Version 8.1+
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze

## Structure du backend

### Contrôleurs principaux

- `GameController.php` : Gestion des jeux et de la collection
- `CommentController.php` : Gestion des commentaires
- `RatingController.php` : Gestion des notes
- `AdminController.php` : Fonctionnalités administrateur
- `ProfileController.php` : Gestion des profils utilisateurs

### Modèles

- `User.php` : Utilisateurs de l'application
- `Game.php` : Jeux vidéo
- `Comment.php` : Commentaires sur les jeux
- `Rating.php` : Notes attribuées aux jeux
- `GameCollection.php` : Collection de jeux des utilisateurs

### Services

- `RawgApiService.php` : Service d'intégration avec l'API RAWG
  - Gestion du cache
  - Limites d'appels API
  - Requêtes aux endpoints

### Middleware

- `AdminMiddleware.php` : Restriction d'accès aux fonctionnalités admin
- Middleware d'authentification standard de Laravel

### Routes principales

- `/` : Accueil
- `/games` : Liste des jeux
- `/games/all` : Catalogue complet avec filtres
- `/games/search` : Recherche de jeux
- `/games/{id}` : Page détaillée d'un jeu
- `/my-games` : Collection personnelle
- `/admin/*` : Routes d'administration
- `/profile` : Gestion du profil utilisateur

## Intégration API

### RAWG API

- Récupération des jeux populaires
- Récupération des nouveaux jeux
- Recherche de jeux
- Détails d'un jeu spécifique
- Filtrage par genre, plateforme, date

### Fonctionnalités d'optimisation

- Mise en cache des requêtes API
- Limitation du nombre d'appels API
- Gestion des erreurs et timeouts

## Système de permissions

- `user` : Utilisateur standard
- `admin` : Accès complet incluant la modération

## Jobs et tâches planifiées

- `ClearExpiredCache` : Nettoyage du cache expiré
- `SyncGameRatings` : Synchronisation des notes entre l'API et la BDD locale

## Gestion des erreurs

- Logs personnalisés pour les erreurs d'API
- Page de debug pour les environnements de développement
- Gestion des jeux non trouvés (404)