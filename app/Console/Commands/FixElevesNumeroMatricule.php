<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;

class FixElevesNumeroMatricule extends Command
{
    protected $signature = 'eleves:fix-numero-matricule';
    protected $description = 'Corrige les numero_matricule vides ou en doublon dans la table eleves';

    public function handle()
    {
        $this->info('Correction des numero_matricule vides...');
        // 1. Remplir les numero_matricule vides
        Eleve::whereNull('numero_matricule')
            ->orWhere('numero_matricule', '')
            ->each(function ($eleve) {
                $eleve->numero_matricule = 'TMP-' . $eleve->id;
                $eleve->save();
            });

        $this->info('Correction des numero_matricule en doublon...');
        // 2. Corriger les doublons
        $doublons = Eleve::select('numero_matricule')
            ->groupBy('numero_matricule')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('numero_matricule');

        foreach ($doublons as $numero) {
            $eleves = Eleve::where('numero_matricule', $numero)->orderBy('id')->get();
            // On garde le premier, on modifie les suivants
            foreach ($eleves->skip(1) as $eleve) {
                $eleve->numero_matricule = $eleve->numero_matricule . '-DUP' . $eleve->id;
                $eleve->save();
            }
        }

        $this->info('Vérification finale...');
        $nbVides = Eleve::whereNull('numero_matricule')->orWhere('numero_matricule', '')->count();
        $nbDoublons = Eleve::select('numero_matricule')
            ->groupBy('numero_matricule')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        if ($nbVides === 0 && $nbDoublons === 0) {
            $this->info('Tous les numero_matricule sont maintenant uniques et non vides.');
        } else {
            $this->warn("Il reste $nbVides vides et $nbDoublons doublons à corriger manuellement.");
        }
    }
}
