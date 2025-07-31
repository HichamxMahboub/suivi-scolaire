<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateNiveauxScolairesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping des anciens niveaux vers les nouveaux niveaux marocains
        $mapping = [
            'CP1' => '1AP',
            'CP2' => '2AP',
            'CE1' => '3AP',
            'CE2' => '4AP',
            'CM1' => '5AP',
            'CM2' => '6AP',
            '6ème' => '1AC',
            '5ème' => '2AC',
            '4ème' => '3AC',
            '3ème' => '3AC', // 3ème collège
            '2nde' => 'TC Sciences', // Par défaut
            '1ère' => '1ère Bac Sciences Mathématiques A', // Par défaut
            'Terminale' => '2ème Bac Sciences Mathématiques A', // Par défaut
        ];

        // Mettre à jour les élèves
        foreach ($mapping as $ancienNiveau => $nouveauNiveau) {
            DB::table('eleves')
                ->where('niveau_scolaire', $ancienNiveau)
                ->update(['niveau_scolaire' => $nouveauNiveau]);
        }

        // Mettre à jour les classes
        foreach ($mapping as $ancienNiveau => $nouveauNiveau) {
            DB::table('classes')
                ->where('niveau', $ancienNiveau)
                ->update(['niveau' => $nouveauNiveau]);
        }

        $this->command->info('Niveaux scolaires mis à jour vers le système marocain !');
    }
} 