@echo off
echo ========================================
echo    SUIVI SCOLAIRE - ACCES ADMINISTRATEUR
echo ========================================
echo.

echo 🚀 Démarrage du serveur Laravel...
echo.

REM Démarrer le serveur en arrière-plan
start /B php artisan serve --host=127.0.0.1 --port=8000

echo ⏳ Attente du démarrage du serveur...
timeout /t 3 /nobreak > nul

echo.
echo ✅ Serveur démarré avec succès !
echo.
echo 🌐 URL d'accès: http://127.0.0.1:8000
echo.
echo 🔐 COMPTES ADMINISTRATEURS DISPONIBLES:
echo.
echo 📧 Root Principal: root@admin.com
echo 🔑 Mot de passe: root123456
echo.
echo 📧 Super Admin: superadmin@ecole.com
echo 🔑 Mot de passe: superpass123
echo.
echo 📧 Admin Test: admin@test.com
echo 🔑 Mot de passe: admin123
echo.
echo ========================================
echo.

REM Ouvrir le navigateur automatiquement
start http://127.0.0.1:8000/login

echo 🎯 Interface d'administration ouverte dans votre navigateur
echo.
echo 💡 Pour arrêter le serveur, fermez cette fenêtre ou appuyez sur Ctrl+C
echo.
pause 