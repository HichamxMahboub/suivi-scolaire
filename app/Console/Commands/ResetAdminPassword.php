<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialiser le mot de passe d\'un utilisateur administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Trouver l'utilisateur
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Aucun utilisateur trouvé avec l'email: {$email}");
            return 1;
        }

        // Vérifier que c'est un administrateur
        if ($user->role !== 'admin') {
            $this->warn("⚠️  L'utilisateur {$email} n'est pas un administrateur (rôle: {$user->role})");
            
            if (!$this->confirm('Voulez-vous continuer quand même ?')) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($password),
        ]);

        $this->info('✅ Mot de passe réinitialisé avec succès !');
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Nouveau mot de passe: {$password}");
        $this->info("👤 Nom: {$user->name}");
        $this->info("🔐 Rôle: {$user->role}");

        $this->warn('⚠️  IMPORTANT: L\'utilisateur doit changer son mot de passe lors de la prochaine connexion !');

        return 0;
    }
} 