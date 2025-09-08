<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserWithRole extends Command
{
    protected $signature = 'user:create-role {email} {password} {name} {role}';
    protected $description = 'Créer un utilisateur avec un rôle spécifique (admin, encadrant, medical, teacher, user)';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');
        $role = $this->argument('role');

        // Valider les données
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'role' => $role,
        ], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,encadrant,medical,teacher,user,student',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Vérifier si l'utilisateur existe déjà
        if (User::where('email', $email)->exists()) {
            $this->error("Un utilisateur avec l'email {$email} existe déjà.");
            return 1;
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
            'email_verified_at' => now(),
        ]);

        $roleNames = [
            'admin' => 'Administrateur',
            'encadrant' => 'Encadrant pédagogique',
            'medical' => 'Personnel médical',
            'teacher' => 'Enseignant',
            'user' => 'Utilisateur',
            'student' => 'Étudiant'
        ];

        $this->info('✅ Utilisateur créé avec succès !');
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Mot de passe: {$password}");
        $this->info("👤 Nom: {$name}");
        $this->info("🔐 Rôle: {$roleNames[$role]}");
        $this->info("🆔 ID: {$user->id}");

        // Afficher les permissions du rôle
        $this->newLine();
        $this->info("📋 Permissions accordées :");

        switch ($role) {
            case 'admin':
                $this->line("  • ✅ Accès complet au système");
                $this->line("  • ✅ Gestion des utilisateurs");
                $this->line("  • ✅ Accès aux informations médicales");
                $this->line("  • ✅ Modification de toutes les données");
                break;

            case 'encadrant':
                $this->line("  • ✅ Gestion des élèves (informations de base et contact)");
                $this->line("  • ✅ Gestion des notes");
                $this->line("  • ❌ Pas d'accès aux informations médicales");
                $this->line("  • ✅ Envoi et réception de messages");
                break;

            case 'medical':
                $this->line("  • ✅ Accès et modification des informations médicales");
                $this->line("  • ✅ Consultation des profils élèves");
                $this->line("  • ❌ Pas de gestion des notes");
                $this->line("  • ✅ Envoi et réception de messages");
                break;

            case 'teacher':
                $this->line("  • ✅ Consultation des profils élèves");
                $this->line("  • ✅ Gestion des notes");
                $this->line("  • ❌ Pas d'accès aux informations médicales");
                $this->line("  • ✅ Envoi et réception de messages");
                break;

            default:
                $this->line("  • ✅ Consultation limitée");
                $this->line("  • ✅ Envoi et réception de messages");
        }

        return 0;
    }
}
