# 🚀 ACCÈS RAPIDE - SUIVI SCOLAIRE

## ⚡ **DÉMARRAGE IMMÉDIAT**

### **Option 1 : Script Windows (Recommandé)**
```bash
# Double-cliquer sur le fichier
start_admin.bat
```

### **Option 2 : Script PowerShell**
```powershell
# Démarrer le serveur
.\start_admin.ps1 -Start

# Voir l'état du serveur
.\start_admin.ps1 -Status

# Arrêter le serveur
.\start_admin.ps1 -Stop
```

### **Option 3 : Commande manuelle**
```bash
# Démarrer le serveur
php artisan serve --host=127.0.0.1 --port=8000

# Ouvrir dans le navigateur
http://127.0.0.1:8000/login
```

---

## 🔐 **COMPTES ADMINISTRATEURS**

| Email | Mot de passe | Rôle |
|-------|-------------|------|
| `root@admin.com` | `root123456` | Root Principal |
| `superadmin@ecole.com` | `superpass123` | Super Admin |
| `admin@test.com` | `admin123` | Admin Test |

---

## 🎯 **PREMIÈRES ACTIONS**

### **1. Connexion**
1. Ouvrir http://127.0.0.1:8000/login
2. Saisir : `root@admin.com` / `root123456`
3. Cliquer sur "Se connecter"

### **2. Changement de mot de passe**
1. Aller dans "Profil" (en haut à droite)
2. Cliquer sur "Modifier le profil"
3. Changer le mot de passe
4. Sauvegarder

### **3. Configuration initiale**
1. **Classes** : Créer les premières classes
2. **Enseignants** : Ajouter les enseignants
3. **Élèves** : Importer ou créer les élèves
4. **Notes** : Commencer la saisie des notes

---

## 🛠️ **COMMANDES UTILES**

### **Gestion des utilisateurs**
```bash
# Lister les administrateurs
php artisan user:list-admins

# Créer un nouvel admin
php artisan user:create-root email@example.com password --name="Nom Admin"

# Réinitialiser un mot de passe
php artisan user:reset-password email@example.com nouveau_password
```

### **Maintenance**
```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimiser
php artisan optimize

# Vérifier l'état
php artisan about
```

---

## 📊 **FONCTIONNALITÉS PRINCIPALES**

### **✅ Dashboard**
- Statistiques en temps réel
- Vue d'ensemble du système
- Graphiques et rapports

### **✅ Gestion des Élèves**
- CRUD complet
- Import/Export Excel/CSV/PDF
- Recherche et filtres
- Statistiques par classe

### **✅ Gestion des Classes**
- Création et configuration
- Affectation d'élèves
- Contrôle des effectifs
- Statistiques de classe

### **✅ Système de Notes**
- Saisie des notes
- Calculs automatiques
- Coefficients
- Statistiques par matière

### **✅ Messagerie**
- Envoi de messages
- Pièces jointes
- Archivage
- Notifications

---

## 🔧 **DÉPANNAGE RAPIDE**

### **Serveur ne démarre pas**
```bash
# Vérifier PHP
php -v

# Vérifier les dépendances
composer install

# Vérifier la base de données
php artisan migrate:status
```

### **Erreur de connexion**
```bash
# Réinitialiser le cache
php artisan cache:clear

# Vérifier les logs
tail -f storage/logs/laravel.log
```

### **Problème de base de données**
```bash
# Vérifier la connexion
php artisan tinker
# DB::connection()->getPdo();

# Relancer les migrations
php artisan migrate:fresh --seed
```

---

## 📞 **SUPPORT**

### **Logs d'erreur**
- `storage/logs/laravel.log`

### **Configuration**
- `.env` - Variables d'environnement
- `config/` - Configuration Laravel

### **Base de données**
- `database/migrations/` - Structure
- `database/seeders/` - Données de test

---

## 🎉 **PRÊT À UTILISER !**

Votre système Suivi Scolaire est **100% fonctionnel** et prêt pour la production.

**Bonne utilisation ! 🚀** 