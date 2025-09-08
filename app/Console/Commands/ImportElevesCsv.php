<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;

class ImportElevesCsv extends Command
{
    protected $signature = 'eleves:import-csv {file}';
    protected $description = 'Importe les élèves depuis un fichier CSV (primaire)';

    public function handle()
    {
        $file = $this->argument('file');
        $filePath = storage_path('app/' . $file);

        if (!file_exists($filePath)) {
            $this->error("Le fichier {$file} n'existe pas dans storage/app/");
            return 1;
        }

        $this->info("Début de l'import depuis {$file}...");
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Impossible d'ouvrir le fichier {$file}");
            return 1;
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            $this->error("Le fichier est vide ou corrompu");
            fclose($handle);
            return 1;
        }

        $rowCount = 0;
        $importedCount = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;
            try {
                $data = array_combine($headers, $row);
                // Nettoyage des champs vides
                foreach ($data as $k => $v) {
                    if ($v === '') {
                        $data[$k] = null;
                    }
                }
                Eleve::create($data);
                $importedCount++;
                $this->line("✓ Importé : {$data['prenom']} {$data['nom']}");
            } catch (\Exception $e) {
                $errors[] = "Ligne {$rowCount}: " . $e->getMessage();
            }
        }
        fclose($handle);

        $this->info("\n=== RÉSUMÉ DE L'IMPORT ===");
        $this->info("Lignes traitées : {$rowCount}");
        $this->info("Élèves importés : {$importedCount}");
        if (!empty($errors)) {
            $this->warn("Erreurs rencontrées : " . count($errors));
            foreach ($errors as $error) {
                $this->error($error);
            }
        }
        $this->info("Import terminé !");
        return 0;
    }
}
