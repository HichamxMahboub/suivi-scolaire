<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Classe;

class ImportElevesParClasse extends Command
{
    protected $signature = 'eleves:import-par-classe';
    protected $description = 'Importer les élèves avec leurs classes respectives';

    public function handle()
    {
        $this->info('Début de l\'import des élèves par classe...');

        $filePath = storage_path('app/eleves_par_classe.csv');

        if (!file_exists($filePath)) {
            $this->error('Fichier CSV non trouvé : ' . $filePath);
            return 1;
        }

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        $imported = 0;
        $errors = 0;

        while (($data = fgetcsv($handle)) !== false) {
            try {
                $row = array_combine($headers, $data);

                // Vérifier si l'élève existe déjà par matricule
                $existing = Eleve::where('numero_matricule', $row['numero_matricule'])->first();
                if ($existing) {
                    $this->info("Élève {$row['nom']} {$row['prenom']} (matricule: {$row['numero_matricule']}) existe déjà");
                    continue;
                }

                // Trouver la classe
                $classe = Classe::where('nom', $row['classe'])->first();
                if (!$classe) {
                    $this->warn("Classe '{$row['classe']}' non trouvée pour l'élève {$row['nom']} {$row['prenom']}");
                    continue;
                }

                // Créer l'élève
                $eleve = new Eleve();
                $eleve->nom = $row['nom'];
                $eleve->prenom = $row['prenom'];
                $eleve->numero_matricule = $row['numero_matricule'];
                $eleve->educateur_responsable = $row['encadrant'];
                $eleve->sexe = $row['sexe'];
                $eleve->date_naissance = $row['date_naissance'];
                $eleve->annee_entree = $row['annee_entree'];
                $eleve->classe_id = $classe->id;
                $eleve->niveau_scolaire = $classe->nom;

                $eleve->save();
                $imported++;

                $this->info("✓ Élève {$row['nom']} {$row['prenom']} ajouté dans la classe {$classe->nom}");

            } catch (\Exception $e) {
                $errors++;
                $this->error("Erreur pour l'élève ligne " . ($imported + $errors + 1) . ": " . $e->getMessage());
            }
        }

        fclose($handle);

        $this->info("\n=== RÉSUMÉ ===");
        $this->info("Élèves importés avec succès: {$imported}");
        if ($errors > 0) {
            $this->error("Erreurs rencontrées: {$errors}");
        }

        return 0;
    }
}
