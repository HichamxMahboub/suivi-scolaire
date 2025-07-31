# Système de Suivi Scolaire - École

## Installation Production

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM

### Installation
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan key:generate
php artisan migrate
```

### Démarrage
```bash
# Windows
start_production.bat

# Linux/Mac
php artisan serve
```

### Accès Administrateur
- Email: admin@ecole.ma
- Mot de passe: admin123

## Fonctionnalités

### 📚 Gestion des Élèves
- ✅ Inscription et gestion complète des élèves
- ✅ Import/Export Excel des données élèves
- ✅ Gestion des photos et documents
- ✅ **Nouveau** : Upload et modification des photos d'élèves
- ✅ **Nouveau** : Profils élèves avec sections indépendantes (Base, Contact, Médical)
- ✅ **Nouveau** : Bouton "Afficher plus" pour informations détaillées
- ✅ **Nouveau** : Permissions par rôle pour l'accès aux informations médicales
- ✅ Suivi du parcours scolaire
- ✅ Gestion des redoublements

### 💬 Système de Messagerie
- ✅ **Nouveau** : Messagerie complète entre utilisateurs
- ✅ **Nouveau** : Système de rôles (Admin, Encadrant, Médical, Enseignant)
- ✅ **Nouveau** : Permissions basées sur les rôles
- ✅ **Nouveau** : Messages favoris et archivage
- ✅ **Nouveau** : Notifications en temps réel
- ✅ **Nouveau** : Filtres par type et priorité
- ✅ **Nouveau** : Statistiques de messagerie

### 👥 Gestion des Utilisateurs et Rôles
- ✅ **Nouveau** : Système de rôles avancé
  - **Admin** : Accès complet, gestion des utilisateurs
  - **Encadrant** : Gestion élèves (sauf médical), notes
  - **Médical** : Accès exclusif aux informations médicales
  - **Enseignant** : Consultation élèves, gestion notes
- ✅ **Nouveau** : Permissions granulaires par fonctionnalité
- ✅ **Nouveau** : Interface de gestion des rôles

### 📊 Système de Notes
- ✅ Support pour 3 types d'établissements :
  - **Primaire** : Notes sur 10
  - **Collège** : Notes sur 20  
  - **Lycée** : Notes sur 20
- ✅ Calcul automatique des moyennes
- ✅ Bulletins de notes automatisés
- ✅ Statistiques et rapports

### 👥 Gestion des Encadrants
- ✅ Création automatique des encadrants
- ✅ Attribution des élèves aux encadrants
- ✅ Suivi des responsabilités

### 📈 Tableaux de Bord
- ✅ Dashboard temps réel
- ✅ Statistiques détaillées
- ✅ Visualisations graphiques

## Commandes Utiles

### Gestion des Utilisateurs
```bash
# Créer un administrateur
php artisan user:create-admin --email=admin@ecole.ma --password=admin123 --name="Administrateur"

# Créer un utilisateur avec un rôle spécifique
php artisan user:create-role email@example.com password "Nom Utilisateur" role
# Exemples :
php artisan user:create-role medecin@ecole.ma pass123 "Dr. Martin" medical
php artisan user:create-role encadrant@ecole.ma pass123 "Pierre Dupont" encadrant

# Lister les administrateurs
php artisan user:list-admins

# Créer un utilisateur root
php artisan user:create-root email@example.com password

# Réinitialiser un mot de passe
php artisan user:reset-password email@example.com
```

### Test du Système de Messagerie
```bash
# Créer des messages de test pour vérifier le système
php artisan message:test
```

### Gestion des Données
```bash
# Créer les encadrants automatiquement depuis les données élèves
php artisan encadrants:create-from-eleves

# Assigner les types d'établissement aux élèves
php artisan eleves:assign-types-etablissement
```

### Optimisation Production
```bash
# Optimiser le cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Base de Données

### Tables Principales
- `users` - Utilisateurs du système
- `eleves` - Données des élèves
- `classes` - Classes scolaires  
- `encadrants` - Encadrants/Éducateurs
- `notes` - Notes des élèves
- `parcours_scolaires` - Historique scolaire
- `messages` - Système de messagerie

### Données Conservées
✅ **Vos données sont préservées** :
- 1 Administrateur (admin@ecole.ma)
- Toutes les données élèves
- Classes et encadrants
- Notes et parcours scolaires
- Configuration système

## Structure du Projet (Nettoyé)

```
📁 app/
├── Console/Commands/     # Commandes Artisan
├── Http/Controllers/     # Contrôleurs
├── Models/              # Modèles Eloquent
└── Helpers/             # Classes d'aide

📁 database/
├── migrations/          # Migrations essentielles
└── seeders/            # Seeders de base

📁 resources/
├── views/              # Vues Blade
├── css/                # Styles
└── js/                 # JavaScript

📁 routes/              # Routes web et API
📁 config/              # Configuration
📁 storage/             # Stockage (logs nettoyés)
📁 public/              # Assets publics
```

## Support Technique

Pour tout problème ou question :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Testez avec : `php artisan serve`
3. Vérifiez la base de données : `php artisan db:show`

---

**Version Production** - Fichiers de développement supprimés, données préservées