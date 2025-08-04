<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Classe;

class DiagnoseElevesClasses extends Command
{
    protected $signature = 'eleves:diagnose-classes';
    protected $description = 'Diagnostiquer l\'affichage des classes des élèves';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC DES CLASSES DES ÉLÈVES ===');
        
        // Vérifier quelques élèves avec leurs classes
        $eleves = Eleve::with('classe')->take(10)->get();
        
        $this->info("\n--- ÉCHANTILLON D'ÉLÈVES ---");
        foreach($eleves as $eleve) {
            $classeNom = $eleve->classe ? $eleve->classe->nom : 'Non assigné';
            $this->line("• {$eleve->nom} {$eleve->prenom} (Mat: {$eleve->numero_matricule}) - Classe ID: {$eleve->classe_id} - Classe: {$classeNom}");
        }
        
        // Compter les élèves avec et sans classe
        $avecClasse = Eleve::whereNotNull('classe_id')->count();
        $sansClasse = Eleve::whereNull('classe_id')->count();
        $total = Eleve::count();
        
        $this->info("\n--- STATISTIQUES GÉNÉRALES ---");
        $this->line("Total élèves: {$total}");
        $this->line("Avec classe assignée: {$avecClasse}");
        $this->line("Sans classe: {$sansClasse}");
        
        // Vérifier les classes existantes
        $classes = Classe::all();
        $this->info("\n--- CLASSES DISPONIBLES ---");
        foreach($classes as $classe) {
            $nombreEleves = Eleve::where('classe_id', $classe->id)->count();
            $this->line("• {$classe->nom} (ID: {$classe->id}) - {$nombreEleves} élève(s)");
        }
        
        // Vérifier si la relation fonctionne
        $this->info("\n--- TEST DE RELATION ---");
        $eleveAvecClasse = Eleve::whereNotNull('classe_id')->with('classe')->first();
        if ($eleveAvecClasse) {
            $this->line("Test avec {$eleveAvecClasse->nom} {$eleveAvecClasse->prenom}:");
            $this->line("  - classe_id: {$eleveAvecClasse->classe_id}");
            $this->line("  - classe loaded: " . ($eleveAvecClasse->relationLoaded('classe') ? 'OUI' : 'NON'));
            $this->line("  - classe object: " . ($eleveAvecClasse->classe ? 'OUI' : 'NON'));
            if ($eleveAvecClasse->classe) {
                $this->line("  - nom classe: {$eleveAvecClasse->classe->nom}");
            }
        }
        
        return 0;
    }
}
