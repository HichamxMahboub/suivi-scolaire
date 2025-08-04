<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EleveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = Classe::all();
        if ($classes->isEmpty()) {
            $this->command->warn('Aucune classe trouvée. Créez d\'abord des classes.');
            return;
        }

        $niveaux = [
            '1AP', '2AP', '3AP', '4AP', '5AP', '6AP',
            '1AC', '2AC', '3AC', 'TC Sciences', '1ère Bac Sciences Mathématiques A', '2ème Bac Sciences Mathématiques A'
        ];

        for ($i = 1; $i <= 30; $i++) {
            $classe = $classes->random();
            $niveau = $niveaux[array_rand($niveaux)];
            Eleve::create([
                'numero_matricule' => 'MAT' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nom' => 'Élève' . $i,
                'prenom' => 'Test' . $i,
                'niveau_scolaire' => $niveau,
                'date_naissance' => Carbon::now()->subYears(rand(6, 18))->subDays(rand(0, 365)),
                'annee_entree' => rand(2018, 2024),
                'adresse' => 'Adresse ' . $i,
                'educateur_responsable' => 'Educateur' . rand(1, 5),
                'classe_id' => $classe->id,
            ]);
        }
        $this->command->info('✅ 30 élèves de test créés avec succès !');
    }
}
