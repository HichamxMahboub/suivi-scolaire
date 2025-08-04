# Suivi Scolaire – Gestion des élèves et notes

## Fonctionnalités principales

- Gestion des élèves (CRUD, import/export CSV, profils)
- Gestion des classes (CRUD, stats, affectation)
- Gestion des notes (CRUD, stats, export)
- Dashboard moderne avec statistiques et alertes
- Gestion des utilisateurs (admin, enseignant)
- Interface moderne, responsive, accessible (Tailwind CSS, animations, focus, aria-labels)
- Authentification sécurisée (login, reset password, email)

## Installation et démarrage rapide

1. **Cloner le projet et installer les dépendances PHP**
   ```bash
   composer install
   ```
2. **Installer les dépendances front-end et compiler le CSS**
   ```bash
   npm install
   npm run dev
   ```
3. **Configurer l'environnement**
   - Copier `.env.example` en `.env` et adapter les variables si besoin
   - Générer la clé d'application :
     ```bash
     php artisan key:generate
     ```
4. **Lancer les migrations et les seeders**
   ```bash
   php artisan migrate --seed
   ```
5. **Lancer le serveur**
   ```bash
   php artisan serve
   ```
   Accès : http://127.0.0.1:8000

## Tests automatisés

- Lancer tous les tests :
  ```bash
  php artisan test
  ```
- 100% des tests passent (auth, profils, élèves, classes, reset password, etc.)
- Environnement de test isolé (`SESSION_DRIVER=array`, `CACHE_DRIVER=array`, `QUEUE_CONNECTION=sync`, `MAIL_MAILER=array`)

## Nettoyage et structure

- Fichiers inutiles supprimés (CSV d'exemple, README en doublon, code mort)
- Migrations et seeders harmonisés
- Vues et composants Blade uniformisés (accessibilité, design)
- Code conforme aux bonnes pratiques Laravel 12

## Bonnes pratiques

- Utiliser les composants Blade (`primary-button`, `secondary-button`, etc.) pour tous les boutons
- Respecter l'accessibilité (focus, aria-labels, contraste)
- Recompiler le CSS après toute modification : `npm run dev`
- Nettoyer le cache navigateur si besoin

## Accès administrateur par défaut

- **Email** : `root@example.com`
- **Mot de passe** : `root1234`  
> Pensez à changer ce mot de passe après la première connexion pour des raisons de sécurité.

## Contribution

- Merci de respecter la structure et les conventions du projet
- Toute PR doit passer les tests et respecter l'accessibilité

---

Projet basé sur Laravel 12. Pour plus d'infos, voir [laravel.com](https://laravel.com) 