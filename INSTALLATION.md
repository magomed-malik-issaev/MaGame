# Guide d'installation - MaGame

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7+ ou MariaDB 10.3+
- Node.js et NPM (pour les assets frontend)
- Serveur web (Apache, Nginx) ou Laravel Valet/Homestead

## Étape 1 : Cloner le projet

```bash
git clone https://github.com/votre-username/MaGame.git
cd MaGame
```

## Étape 2 : Installer les dépendances

```bash
composer install
npm install
npm run dev
```

## Étape 3 : Configuration de l'environnement

Copiez le fichier `.env.example` et renommez-le en `.env`:

```bash
cp .env.example .env
```

Générez une clé d'application:

```bash
php artisan key:generate
```

## Étape 4 : Configuration de la base de données

Configurez les informations de votre base de données dans le fichier `.env`:
