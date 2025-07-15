# Script PowerShell pour l'administration du Suivi Scolaire
param(
    [switch]$Start,
    [switch]$Stop,
    [switch]$Status,
    [switch]$CreateAdmin,
    [switch]$ListAdmins,
    [string]$Email,
    [string]$Password,
    [string]$Name
)

function Show-Banner {
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "    SUIVI SCOLAIRE - ADMINISTRATION" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
}

function Show-Accounts {
    Write-Host "🔐 COMPTES ADMINISTRATEURS DISPONIBLES:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "📧 Root Principal: root@admin.com" -ForegroundColor Green
    Write-Host "🔑 Mot de passe: root123456" -ForegroundColor Green
    Write-Host ""
    Write-Host "📧 Super Admin: superadmin@ecole.com" -ForegroundColor Green
    Write-Host "🔑 Mot de passe: superpass123" -ForegroundColor Green
    Write-Host ""
    Write-Host "📧 Admin Test: admin@test.com" -ForegroundColor Green
    Write-Host "🔑 Mot de passe: admin123" -ForegroundColor Green
    Write-Host ""
}

function Start-Server {
    Show-Banner
    Write-Host "🚀 Démarrage du serveur Laravel..." -ForegroundColor Yellow
    Write-Host ""
    
    # Vérifier si PHP est disponible
    try {
        $phpVersion = php -v 2>$null
        if ($LASTEXITCODE -ne 0) {
            Write-Host "❌ PHP n'est pas installé ou n'est pas dans le PATH" -ForegroundColor Red
            return
        }
    }
    catch {
        Write-Host "❌ Erreur lors de la vérification de PHP" -ForegroundColor Red
        return
    }
    
    # Démarrer le serveur
    Write-Host "⏳ Démarrage du serveur sur http://127.0.0.1:8000..." -ForegroundColor Yellow
    Start-Process -FilePath "php" -ArgumentList "artisan", "serve", "--host=127.0.0.1", "--port=8000" -WindowStyle Hidden
    
    # Attendre que le serveur démarre
    Start-Sleep -Seconds 5
    
    Write-Host "✅ Serveur démarré avec succès !" -ForegroundColor Green
    Write-Host ""
    Write-Host "🌐 URL d'accès: http://127.0.0.1:8000" -ForegroundColor Cyan
    Write-Host ""
    
    Show-Accounts
    
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    
    # Ouvrir le navigateur
    Start-Process "http://127.0.0.1:8000/login"
    
    Write-Host "🎯 Interface d'administration ouverte dans votre navigateur" -ForegroundColor Green
    Write-Host ""
    Write-Host "💡 Pour arrêter le serveur, utilisez: .\start_admin.ps1 -Stop" -ForegroundColor Yellow
}

function Stop-Server {
    Show-Banner
    Write-Host "🛑 Arrêt du serveur Laravel..." -ForegroundColor Yellow
    
    # Trouver et arrêter le processus PHP artisan serve
    $processes = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*artisan serve*" }
    
    if ($processes) {
        foreach ($process in $processes) {
            $process.Kill()
            Write-Host "✅ Processus PHP arrêté (PID: $($process.Id))" -ForegroundColor Green
        }
    } else {
        Write-Host "ℹ️  Aucun serveur Laravel en cours d'exécution" -ForegroundColor Yellow
    }
}

function Get-ServerStatus {
    Show-Banner
    Write-Host "📊 État du serveur Laravel..." -ForegroundColor Yellow
    
    # Vérifier si le serveur répond
    try {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000" -TimeoutSec 5 -ErrorAction Stop
        Write-Host "✅ Serveur en cours d'exécution" -ForegroundColor Green
        Write-Host "🌐 URL: http://127.0.0.1:8000" -ForegroundColor Cyan
    }
    catch {
        Write-Host "❌ Serveur non accessible" -ForegroundColor Red
    }
    
    # Vérifier les processus PHP
    $processes = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*artisan serve*" }
    if ($processes) {
        Write-Host "📋 Processus PHP en cours:" -ForegroundColor Yellow
        foreach ($process in $processes) {
            Write-Host "   PID: $($process.Id) - Démarrage: $($process.StartTime)" -ForegroundColor White
        }
    } else {
        Write-Host "ℹ️  Aucun processus PHP artisan serve trouvé" -ForegroundColor Yellow
    }
}

function New-AdminUser {
    Show-Banner
    Write-Host "👤 Création d'un nouvel administrateur..." -ForegroundColor Yellow
    
    if (-not $Email -or -not $Password) {
        Write-Host "❌ Veuillez spécifier l'email et le mot de passe:" -ForegroundColor Red
        Write-Host "   .\start_admin.ps1 -CreateAdmin -Email 'admin@example.com' -Password 'password123'" -ForegroundColor Yellow
        return
    }
    
    $name = if ($Name) { $Name } else { "Administrateur" }
    
    Write-Host "📧 Email: $Email" -ForegroundColor Cyan
    Write-Host "🔑 Mot de passe: $Password" -ForegroundColor Cyan
    Write-Host "👤 Nom: $name" -ForegroundColor Cyan
    Write-Host ""
    
    # Exécuter la commande Laravel
    $result = php artisan user:create-root $Email $Password --name="$name" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Administrateur créé avec succès !" -ForegroundColor Green
    } else {
        Write-Host "❌ Erreur lors de la création: $result" -ForegroundColor Red
    }
}

function Get-AdminUsers {
    Show-Banner
    Write-Host "📋 Liste des administrateurs..." -ForegroundColor Yellow
    Write-Host ""
    
    php artisan user:list-admins
}

# Logique principale
if ($Start) {
    Start-Server
}
elseif ($Stop) {
    Stop-Server
}
elseif ($Status) {
    Get-ServerStatus
}
elseif ($CreateAdmin) {
    New-AdminUser
}
elseif ($ListAdmins) {
    Get-AdminUsers
}
else {
    # Mode par défaut - afficher l'aide
    Show-Banner
    Write-Host "🛠️  Commandes disponibles:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "  -Start        : Démarrer le serveur et ouvrir l'interface" -ForegroundColor Cyan
    Write-Host "  -Stop         : Arrêter le serveur" -ForegroundColor Cyan
    Write-Host "  -Status       : Vérifier l'état du serveur" -ForegroundColor Cyan
    Write-Host "  -ListAdmins   : Lister tous les administrateurs" -ForegroundColor Cyan
    Write-Host "  -CreateAdmin  : Créer un nouvel administrateur" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "📝 Exemples:" -ForegroundColor Yellow
    Write-Host "  .\start_admin.ps1 -Start" -ForegroundColor White
    Write-Host "  .\start_admin.ps1 -CreateAdmin -Email 'admin@ecole.com' -Password 'pass123'" -ForegroundColor White
    Write-Host "  .\start_admin.ps1 -ListAdmins" -ForegroundColor White
    Write-Host ""
    Show-Accounts
} 