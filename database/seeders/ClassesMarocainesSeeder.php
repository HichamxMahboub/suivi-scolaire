<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClassesMarocainesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            // Primaire
            ['nom' => '1AP A', 'niveau' => '1AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1AP B', 'niveau' => '1AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2AP A', 'niveau' => '2AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2AP B', 'niveau' => '2AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '3AP A', 'niveau' => '3AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '3AP B', 'niveau' => '3AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '4AP A', 'niveau' => '4AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '4AP B', 'niveau' => '4AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '5AP A', 'niveau' => '5AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '5AP B', 'niveau' => '5AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '6AP A', 'niveau' => '6AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],
            ['nom' => '6AP B', 'niveau' => '6AP', 'effectif_max' => 30, 'annee_scolaire' => '2024-2025'],

            // Collège
            ['nom' => '1AC A', 'niveau' => '1AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1AC B', 'niveau' => '1AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2AC A', 'niveau' => '2AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2AC B', 'niveau' => '2AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '3AC A', 'niveau' => '3AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '3AC B', 'niveau' => '3AC', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],

            // Lycée - Tronc Commun
            ['nom' => 'TC Sciences A', 'niveau' => 'TC Sciences', 'effectif_max' => 40, 'annee_scolaire' => '2024-2025'],
            ['nom' => 'TC Sciences B', 'niveau' => 'TC Sciences', 'effectif_max' => 40, 'annee_scolaire' => '2024-2025'],
            ['nom' => 'TC Lettres A', 'niveau' => 'TC Lettres', 'effectif_max' => 40, 'annee_scolaire' => '2024-2025'],
            ['nom' => 'TC Lettres B', 'niveau' => 'TC Lettres', 'effectif_max' => 40, 'annee_scolaire' => '2024-2025'],
            ['nom' => 'TC Techniques A', 'niveau' => 'TC Techniques', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => 'TC Économie A', 'niveau' => 'TC Économie', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],

            // 1ère année Bac
            ['nom' => '1ère Bac Sciences Mathématiques A', 'niveau' => '1ère Bac Sciences Mathématiques A', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Sciences Mathématiques B', 'niveau' => '1ère Bac Sciences Mathématiques B', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Sciences Physiques', 'niveau' => '1ère Bac Sciences Physiques', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac SVT', 'niveau' => '1ère Bac Sciences de la Vie et de la Terre', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Sciences Économiques', 'niveau' => '1ère Bac Sciences Économiques', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Techniques de gestion', 'niveau' => '1ère Bac Techniques de gestion', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Lettres modernes', 'niveau' => '1ère Bac Lettres modernes', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '1ère Bac Lettres originelles', 'niveau' => '1ère Bac Lettres originelles', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],

            // 2ème année Bac
            ['nom' => '2ème Bac Sciences Mathématiques A', 'niveau' => '2ème Bac Sciences Mathématiques A', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Sciences Mathématiques B', 'niveau' => '2ème Bac Sciences Mathématiques B', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Sciences Physiques', 'niveau' => '2ème Bac Sciences Physiques', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac SVT', 'niveau' => '2ème Bac SVT', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Sciences Éco', 'niveau' => '2ème Bac Sciences Éco', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Gestion & Comptabilité', 'niveau' => '2ème Bac Gestion & Comptabilité', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Lettres modernes', 'niveau' => '2ème Bac Lettres modernes', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
            ['nom' => '2ème Bac Lettres originelles', 'niveau' => '2ème Bac Lettres originelles', 'effectif_max' => 35, 'annee_scolaire' => '2024-2025'],
        ];

        foreach ($classes as $classeData) {
            Classe::firstOrCreate(
                ['nom' => $classeData['nom']],
                $classeData
            );
        }

        $this->command->info('Classes marocaines créées avec succès !');
    }
}
