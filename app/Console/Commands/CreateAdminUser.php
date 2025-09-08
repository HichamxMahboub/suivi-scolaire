<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin {--email=admin@ecole.ma} {--password=admin123} {--name=Administrateur}';
    protected $description = 'Créer ou mettre à jour un utilisateur administrateur';

    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Vérifier si l'utilisateur existe déjà
        $user = User::where('email', $email)->first();

        if ($user) {
            // Mettre à jour le mot de passe
            $user->password = Hash::make($password);
            $user->role = 'admin';
            $user->save();

            $this->info("Utilisateur administrateur mis à jour :");
        } else {
            // Créer un nouvel utilisateur
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->info("Nouvel utilisateur administrateur créé :");
        }

        $this->line("Email: {$user->email}");
        $this->line("Mot de passe: {$password}");
        $this->line("Rôle: {$user->role}");

        return 0;
    }
}
