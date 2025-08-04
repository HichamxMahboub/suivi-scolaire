<?php

namespace App\Console\Commands;

use App\Models\Eleve;
use Illuminate\Console\Command;

class ImportEleves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eleves:import {file : Le chemin vers le fichier CSV}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importer des élèves depuis un fichier CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("Le fichier {$filePath} n'existe pas.");
            return 1;
        }

        $this->info("Début de l'import depuis {$filePath}...");

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Impossible d'ouvrir le fichier {$filePath}");
            return 1;
        }

        // Lire l'en-tête
        $headers = fgetcsv($handle);
        if (!$headers) {
            $this->error("Le fichier est vide ou mal formaté.");
            fclose($handle);
            return 1;
        }

        $this->info("En-têtes détectées : " . implode(', ', $headers));

        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $lineNumber = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            try {
                // Créer un tableau associatif avec les en-têtes
                $row = array_combine($headers, $data);
                
                // Validation des données requises
                if (empty($row['numero_matricule']) || empty($row['nom']) || empty($row['prenom']) || empty($row['niveau_scolaire']) || empty($row['classe']) || empty($row['date_naissance']) || empty($row['annee_entree'])) {
                    $this->warn("Ligne {$lineNumber}: Champs obligatoires manquants, ligne ignorée.");
                    $skipped++;
                    continue;
                }

                // Vérifier si l'élève existe déjà
                $existingEleve = Eleve::where('numero_matricule', $row['numero_matricule'])->first();
                if ($existingEleve) {
                    $this->warn("Ligne {$lineNumber}: Élève avec numéro matricule {$row['numero_matricule']} existe déjà, ignoré.");
                    $skipped++;
                    continue;
                }

                // Préparer les données
                $eleveData = [
                    'numero_matricule' => $row['numero_matricule'],
                    'nom' => trim($row['nom']),
                    'prenom' => trim($row['prenom']),
                    'niveau_scolaire' => $row['niveau_scolaire'],
                    'classe' => $row['classe'],
                    'date_naissance' => $this->parseDate($row['date_naissance']),
                    'annee_entree' => $this->parseInteger($row['annee_entree']),
                    'educateur_responsable' => $row['educateur_responsable'] ?? null,
                    'sexe' => $row['sexe'] ?? null,
                    'email' => $row['email'] ?? null,
                    'telephone_parent' => $row['telephone_parent'] ?? null,
                    'adresse' => $row['adresse'] ?? null,
                    'photo' => $row['photo'] ?? null,
                    'remarques' => $row['remarques'] ?? null,
                    'redoublant' => isset($row['redoublant']) ? (int)$row['redoublant'] : null,
                    'niveau_redouble' => $row['niveau_redouble'] ?? null,
                    'annee_sortie' => $this->parseInteger($row['annee_sortie'] ?? null),
                    'cause_sortie' => $row['cause_sortie'] ?? null,
                ];

                // Créer l'élève
                Eleve::create($eleveData);
                $imported++;
                
                $this->line("✓ Ligne {$lineNumber}: {$row['nom']} {$row['prenom']} importé.");

            } catch (\Exception $e) {
                $this->error("Ligne {$lineNumber}: Erreur - " . $e->getMessage());
                $errors++;
            }
        }

        fclose($handle);

        $this->newLine();
        $this->info("Import terminé !");
        $this->info("✓ {$imported} élèves importés");
        $this->warn("⚠ {$skipped} lignes ignorées");
        if ($errors > 0) {
            $this->error("✗ {$errors} erreurs");
        }

        return 0;
    }

    /**
     * Parser une date depuis différents formats
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Essayer différents formats de date
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Parser un entier
     */
    private function parseInteger($value)
    {
        if (empty($value)) {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }
} 