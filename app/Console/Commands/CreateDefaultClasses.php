<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Classe;

class CreateDefaultClasses extends Command
{
    protected $signature = 'classes:create-default';
    protected $description = 'Créer les classes par défaut (Primaire, Collège, Lycée)';

    public function handle()
    {
        $this->info('Création des classes par défaut...');

        $classes = [
            // Primaire (6 classes)
            ['nom' => '1ère Année Primaire', 'niveau' => 'Primaire', 'description' => '1ère Année - Primaire'],
            ['nom' => '2ème Année Primaire', 'niveau' => 'Primaire', 'description' => '2ème Année - Primaire'],
            ['nom' => '3ème Année Primaire', 'niveau' => 'Primaire', 'description' => '3ème Année - Primaire'],
            ['nom' => '4ème Année Primaire', 'niveau' => 'Primaire', 'description' => '4ème Année - Primaire'],
            ['nom' => '5ème Année Primaire', 'niveau' => 'Primaire', 'description' => '5ème Année - Primaire'],
            ['nom' => '6ème Année Primaire', 'niveau' => 'Primaire', 'description' => '6ème Année - Primaire'],

            // Collège (3 classes)
            ['nom' => '1ère Année Collège', 'niveau' => 'Collège', 'description' => '1ère Année - Collège'],
            ['nom' => '2ème Année Collège', 'niveau' => 'Collège', 'description' => '2ème Année - Collège'],
            ['nom' => '3ème Année Collège', 'niveau' => 'Collège', 'description' => '3ème Année - Collège'],

            // Lycée (3 classes)
            ['nom' => '1ère Année Lycée', 'niveau' => 'Lycée', 'description' => '1ère Année - Lycée'],
            ['nom' => '2ème Année Lycée', 'niveau' => 'Lycée', 'description' => '2ème Année - Lycée'],
            ['nom' => '3ème Année Lycée', 'niveau' => 'Lycée', 'description' => '3ème Année - Lycée'],
        ];

        $created = 0;
        $existing = 0;

        foreach ($classes as $classeData) {
            $existingClasse = Classe::where('nom', $classeData['nom'])->first();

            if (!$existingClasse) {
                // Ajouter l'année scolaire requise
                $classeData['annee_scolaire'] = date('Y') . '-' . (date('Y') + 1);
                $classeData['capacite_max'] = 30; // Capacité par défaut

                Classe::create($classeData);
                $this->line("✓ Classe '{$classeData['nom']}' créée ({$classeData['niveau']})");
                $created++;
            } else {
                $this->warn("⚠ Classe '{$classeData['nom']}' existe déjà");
                $existing++;
            }
        }

        $this->newLine();
        $this->info("Création terminée !");
        $this->info("✓ {$created} classes créées");
        $this->warn("⚠ {$existing} classes existaient déjà");

        return 0;
    }
}
