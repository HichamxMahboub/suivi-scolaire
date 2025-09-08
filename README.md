# Suivi Scolaire

Application de gestion scolaire développée avec Laravel 12, PHP 8.3, Tailwind CSS & Vite.

## Prérequis
- PHP ^8.2, extensions (pdo_mysql, sqlite3, mbstring, xml, bcmath, gd)
- Composer
- Node.js LTS & npm
- MySQL/MariaDB ou SQLite

## Installation
```bash
git clone https://github.com/HichamxMahboub/suivi-scolaire.git
cd suivi-scolaire
cp .env.example .env
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
``` 

## Configuration
Modifiez `.env` :
```
APP_ENV=local|production
APP_DEBUG=true|false
DB_CONNECTION=sqlite|mysql
DB_DATABASE=database/database.sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=
``` 

## Commandes utiles
- `php artisan serve` : serveur local (http://127.0.0.1:8000)
- `php artisan test` : exécuter les tests PHPUnit
- `npm run dev` / `npm run build` : assets front-end (Vite + Tailwind)
- `php artisan migrate:fresh --seed` : reset DB + seed

## CI/CD
Un workflow GitHub Actions est configuré pour :
- Valider le code PHP (Pint) et le formater
- Installer PHP + dépendances via composer
- Installer Node.js + npm + build assets
- Exécuter les tests PHPUnit

### Déploiement
Pousser sur `main` déclenche le workflow. Le badge ci-dessous indique le statut :

![CI](https://github.com/HichamxMahboub/suivi-scolaire/actions/workflows/ci.yml/badge.svg)
