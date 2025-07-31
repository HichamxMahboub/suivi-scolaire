<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveaux = config('niveaux_scolaires.niveaux');
        $annee = date('Y') . '-' . (date('Y') + 1); // ex: 2024-2025
        $effectifMax = 30;
        $nbClassesParNiveau = 1; // Modifier ici pour générer plusieurs classes par niveau

        foreach ($niveaux as $code => $nomNiveau) {
            for ($i = 1; $i <= $nbClassesParNiveau; $i++) {
                $nomClasse = $nomNiveau;
                if ($nbClassesParNiveau > 1) {
                    $nomClasse .= ' ' . chr(64 + $i); // A, B, C...
                }
                Classe::create([
                    'nom' => $nomClasse,
                    'niveau' => $code,
                    'annee_scolaire' => $annee,
                    'description' => 'Classe de ' . $nomNiveau,
                    'professeur_principal' => null,
                    'effectif_max' => $effectifMax,
                    'active' => true,
                ]);
            }
        }
    }
} 