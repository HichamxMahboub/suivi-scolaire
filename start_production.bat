@echo off
echo Starting Laravel Suivi Scolaire - Production Mode
echo ================================================

REM Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

REM Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo System optimized for production!
echo Starting server...
echo.
php artisan serve --host=0.0.0.0 --port=8000

pause
