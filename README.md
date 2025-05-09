# MaGame - Plateforme de Gestion de Collection de Jeux Vidéo

## Description
MaGame est une application web moderne permettant aux utilisateurs de gérer leur collection de jeux vidéo. Développée avec Laravel et Tailwind CSS, elle offre une interface utilisateur intuitive et responsive pour suivre vos jeux favoris, en cours et terminés.

## Fonctionnalités Principales

### Gestion de Collection
- Ajout de jeux à votre collection personnelle
- Organisation des jeux en trois catégories :
  - Favoris
  - En cours
  - Terminés
- Possibilité de changer le statut d'un jeu à tout moment
- Interface avec onglets pour une navigation facile entre les catégories

### Intégration RAWG API
- Recherche de jeux via l'API RAWG
- Affichage des détails complets des jeux :
  - Images
  - Descriptions
  - Notes
  - Dates de sortie
  - Plateformes
  - Genres
  - Éditeurs

### Système d'Utilisateurs
- Inscription et authentification
- Profils utilisateurs personnalisés
- Rôles utilisateurs (Admin et User)
- Gestion des commentaires avec modération

### Interface Moderne
- Design responsive avec Tailwind CSS
- Interface utilisateur intuitive
- Navigation fluide
- Thème sombre élégant

## Prérequis Techniques

- PHP 8.0 ou supérieur
- Composer
- Node.js et NPM
- MySQL ou PostgreSQL
- Clé API RAWG

## Installation

1. Cloner le repository :
```bash
git clone [URL_DU_REPO]
cd MaGame
```

2. Installer les dépendances PHP :
```bash
composer install
```

3. Installer les dépendances JavaScript :
```bash
npm install
```

4. Copier le fichier d'environnement :
```bash
cp .env.example .env
```

5. Configurer la base de données dans le fichier `.env`

6. Générer la clé d'application :
```bash
php artisan key:generate
```

7. Installer Laravel Breeze pour l'authentification :
```bash
composer require laravel/breeze --dev
php artisan breeze:install
```

8. Exécuter les migrations :
```bash
php artisan migrate
```

9. Compiler les assets :
```bash
npm run dev
```

10. Lancer le serveur :
```bash
php artisan serve
```

## Configuration de l'API RAWG

1. Obtenir une clé API sur [RAWG](https://rawg.io/apidocs)
2. Ajouter la clé dans le fichier `.env` :
```
RAWG_API_KEY=votre_clé_api
```

## Structure du Projet

```
MaGame/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   │   
│   ├── Models/
│   └── Services/
├── resources/
│   ├── views/
│   └── css/
├── routes/
├── database/
│   └── migrations/
└── public/
```

## Fonctionnalités à Venir

- Système de notation des jeux
- Recommandations personnalisées
- Statistiques de jeu
- Intégration avec d'autres plateformes de jeux
- Système de trophées et succès

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur le repository GitHub.

## Auteurs

- [Votre Nom] - Développeur principal

## Remerciements

- RAWG pour leur API de jeux vidéo
- Laravel pour le framework PHP
- Tailwind CSS pour le framework CSS
