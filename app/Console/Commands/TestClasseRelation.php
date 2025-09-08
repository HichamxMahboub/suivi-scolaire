<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;

class TestClasseRelation extends Command
{
    protected $signature = 'test:classe-relation';
    protected $description = 'Tester la relation classe corrigée';

    public function handle()
    {
        $this->info('=== TEST DE LA RELATION CLASSE CORRIGÉE ===');

        // Test avec un élève spécifique
        $eleve = Eleve::with('classeInfo')->where('numero_matricule', '333')->first();

        if ($eleve) {
            $this->line("Élève: {$eleve->nom} {$eleve->prenom}");
            $this->line("classe_id: {$eleve->classe_id}");
            $this->line("Colonne classe (ancienne): " . ($eleve->getAttributes()['classe'] ?? 'NULL'));

            // Test relation classeInfo
            if ($eleve->classeInfo) {
                $this->info("✓ Relation classeInfo fonctionne: {$eleve->classeInfo->nom}");
            } else {
                $this->error("✗ Relation classeInfo ne fonctionne pas");
            }

            // Test alias classe()
            if ($eleve->classe()) {
                $classeAlias = $eleve->classe()->first();
                if ($classeAlias) {
                    $this->info("✓ Alias classe() fonctionne: {$classeAlias->nom}");
                } else {
                    $this->error("✗ Alias classe() ne retourne rien");
                }
            }
        }

        // Test avec plusieurs élèves
        $this->info("\n--- TEST AVEC PLUSIEURS ÉLÈVES ---");
        $eleves = Eleve::with('classeInfo')->take(5)->get();

        foreach ($eleves as $eleve) {
            $classeNom = $eleve->classeInfo ? $eleve->classeInfo->nom : 'Non assigné';
            $this->line("• {$eleve->nom} {$eleve->prenom} -> {$classeNom}");
        }

        return 0;
    }
}
