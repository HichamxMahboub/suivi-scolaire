<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateRootUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-root {email} {password} {--name=Root Administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un compte administrateur root avec privilèges complets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->option('name');

        // Validation des données
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'name' => $name,
        ], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Créer l'utilisateur root
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('✅ Compte administrateur root créé avec succès !');
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Mot de passe: {$password}");
        $this->info("👤 Nom: {$name}");
        $this->info("🔐 Rôle: Administrateur");
        $this->info("🆔 ID: {$user->id}");

        $this->warn('⚠️  IMPORTANT: Changez le mot de passe après la première connexion !');

        return 0;
    }
} 