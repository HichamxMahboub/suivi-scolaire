<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Classe;

class FixClasseRelation extends Command
{
    protected $signature = 'eleves:fix-classe-relation';
    protected $description = 'Corriger la relation entre élèves et classes';

    public function handle()
    {
        $this->info('=== CORRECTION DE LA RELATION ÉLÈVES-CLASSES ===');
        
        // Vérifier les IDs des classes vs le mapping
        $this->info("\n--- VÉRIFICATION DES IDS ---");
        $classes = Classe::all();
        foreach($classes as $classe) {
            $this->line("Classe: {$classe->nom} - ID dans DB: {$classe->id}");
        }
        
        // Tester la relation avec un élève spécifique
        $this->info("\n--- TEST RELATION AVEC ÉLÈVE SPÉCIFIQUE ---");
        $eleve = Eleve::where('numero_matricule', '333')->first();
        if ($eleve) {
            $this->line("Élève: {$eleve->nom} {$eleve->prenom}");
            $this->line("classe_id: {$eleve->classe_id}");
            
            // Chercher la classe manuellement
            $classe = Classe::find($eleve->classe_id);
            if ($classe) {
                $this->line("Classe trouvée manuellement: {$classe->nom}");
            } else {
                $this->error("Classe ID {$eleve->classe_id} n'existe pas !");
            }
            
            // Tester la relation Eloquent
            $classeRelation = $eleve->classe;
            if ($classeRelation) {
                $this->line("Classe via relation: {$classeRelation->nom}");
            } else {
                $this->error("Relation ne fonctionne pas !");
            }
        }
        
        // Vérifier s'il y a des classe_id qui ne correspondent à aucune classe
        $this->info("\n--- VÉRIFICATION DES CLASSE_ID ORPHELINS ---");
        $elevesAvecClasse = Eleve::whereNotNull('classe_id')->get();
        foreach($elevesAvecClasse as $eleve) {
            $classe = Classe::find($eleve->classe_id);
            if (!$classe) {
                $this->error("Élève {$eleve->nom} {$eleve->prenom} a classe_id {$eleve->classe_id} qui n'existe pas!");
            }
        }
        
        return 0;
    }
}
