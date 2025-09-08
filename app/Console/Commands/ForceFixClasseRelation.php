<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Support\Facades\DB;

class ForceFixClasseRelation extends Command
{
    protected $signature = 'eleves:force-fix-relation';
    protected $description = 'Forcer la correction de la relation élèves-classes';

    public function handle()
    {
        $this->info('=== CORRECTION FORCÉE DE LA RELATION ===');

        // Première étape : Vérifier les contraintes de base de données
        $this->info("\n--- VÉRIFICATION DES CONTRAINTES ---");

        try {
            // Test direct avec une requête SQL
            $result = DB::select("
                SELECT e.id, e.nom, e.prenom, e.classe_id, c.nom as classe_nom 
                FROM eleves e 
                LEFT JOIN classes c ON e.classe_id = c.id 
                WHERE e.classe_id IS NOT NULL 
                LIMIT 5
            ");

            $this->info("Résultat requête SQL directe:");
            foreach ($result as $row) {
                $this->line("  • ID: {$row->id} - {$row->nom} {$row->prenom} - Classe ID: {$row->classe_id} - Classe: " . ($row->classe_nom ?? 'NULL'));
            }

        } catch (\Exception $e) {
            $this->error("Erreur SQL: " . $e->getMessage());
        }

        // Deuxième étape : Vérifier le modèle Eleve
        $this->info("\n--- TEST MODÈLE ELEVE ---");
        $eleve = Eleve::whereNotNull('classe_id')->first();
        if ($eleve) {
            $this->line("Élève testé: {$eleve->nom} {$eleve->prenom}");
            $this->line("classe_id: {$eleve->classe_id}");
            $this->line("getAttributes(): " . json_encode($eleve->getAttributes()));
            $this->line("Relation loaded: " . ($eleve->relationLoaded('classe') ? 'OUI' : 'NON'));

            // Forcer le chargement de la relation
            $eleve->load('classe');
            $this->line("Après load('classe'): " . ($eleve->classe ? 'TROUVÉ' : 'NULL'));

            // Test manuel de la relation
            $classeManuelle = Classe::find($eleve->classe_id);
            $this->line("Classe trouvée manuellement: " . ($classeManuelle ? $classeManuelle->nom : 'NULL'));
        }

        // Troisième étape : Récréer les relations manuellement
        $this->info("\n--- RECRÉATION MANUELLE DES RELATIONS ---");

        if ($this->confirm('Voulez-vous recréer toutes les relations élèves-classes ?')) {
            $corrected = 0;
            $errors = 0;

            $elevesAvecClasse = Eleve::whereNotNull('classe_id')->get();

            foreach ($elevesAvecClasse as $eleve) {
                try {
                    // Vérifier que la classe existe
                    $classe = Classe::find($eleve->classe_id);

                    if ($classe) {
                        // Sauvegarder à nouveau pour forcer la relation
                        $eleve->touch();
                        $corrected++;

                        if ($corrected <= 5) {
                            $this->line("  ✓ {$eleve->nom} {$eleve->prenom} -> {$classe->nom}");
                        }
                    } else {
                        $this->warn("  ✗ {$eleve->nom} {$eleve->prenom} - classe_id {$eleve->classe_id} n'existe pas");
                        $errors++;
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ Erreur pour {$eleve->nom}: " . $e->getMessage());
                    $errors++;
                }
            }

            $this->info("\nCorrected: {$corrected}, Errors: {$errors}");
        }

        // Test final
        $this->info("\n--- TEST FINAL ---");
        $eleve = Eleve::with('classe')->whereNotNull('classe_id')->first();
        if ($eleve) {
            $this->line("Test final avec {$eleve->nom}:");
            $this->line("  - classe_id: {$eleve->classe_id}");
            $this->line("  - classe relation: " . ($eleve->classe ? $eleve->classe->nom : 'NULL'));
        }

        return 0;
    }
}
