@echo off
echo 🚀 Démarrage optimisé de Suivi Scolaire...
echo.

REM Optimiser le système
echo ⚡ Optimisation du système...
php artisan system:optimize

echo.
echo 🔐 Comptes administrateurs disponibles:
echo    Email: root@admin.com
echo    Mot de passe: root123456
echo.

REM Démarrer le serveur
echo 🌐 Démarrage du serveur...
echo 📱 Accès: http://127.0.0.1:8000
echo.
php artisan serve --host=127.0.0.1 --port=8000

pause 