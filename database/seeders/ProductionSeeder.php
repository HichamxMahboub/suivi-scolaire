<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeder for production.
     */
    public function run(): void
    {
        // Créer l'administrateur principal
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@ecole.ma',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer quelques enseignants
        User::create([
            'name' => 'Enseignant Mathématiques',
            'email' => 'math@ecole.ma',
            'password' => Hash::make('enseignant123'),
            'role' => 'enseignant',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Enseignant Français',
            'email' => 'francais@ecole.ma',
            'password' => Hash::make('enseignant123'),
            'role' => 'enseignant',
            'email_verified_at' => now(),
        ]);

        // Créer quelques classes de base
        $classes = [
            ['nom' => '1ère A', 'niveau' => '1ère', 'annee_scolaire' => '2024-2025', 'effectif_max' => 30],
            ['nom' => '1ère B', 'niveau' => '1ère', 'annee_scolaire' => '2024-2025', 'effectif_max' => 30],
            ['nom' => '2ème A', 'niveau' => '2ème', 'annee_scolaire' => '2024-2025', 'effectif_max' => 30],
            ['nom' => '3ème A', 'niveau' => '3ème', 'annee_scolaire' => '2024-2025', 'effectif_max' => 30],
        ];

        foreach ($classes as $classeData) {
            Classe::create($classeData);
        }

        $this->command->info('✅ Données de production créées avec succès !');
        $this->command->info('📧 Admin: admin@ecole.ma / admin123');
        $this->command->info('👨‍🏫 Enseignants: math@ecole.ma, francais@ecole.ma / enseignant123');
    }
}
