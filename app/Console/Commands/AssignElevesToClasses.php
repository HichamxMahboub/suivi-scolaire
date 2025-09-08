<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Classe;

class AssignElevesToClasses extends Command
{
    protected $signature = 'eleves:assign-to-classes';
    protected $description = 'Assigner automatiquement les élèves existants à leurs classes basées sur les matricules';

    public function handle()
    {
        $this->info('Début de l\'assignation automatique des élèves aux classes...');

        // Mapping des classes avec leurs matricules correspondants (noms exacts de la DB)
        $classesMatricules = [
            '1ère Année Lycée' => [337, 378, 379, 427, 508, 510, 538, 543, 575, 590, 600, 604, 660, 672, 674, 703],
            '1ère Année Primaire' => [708, 711, 714, 715, 719, 726, 731],
            '1ère Année Collège' => [430, 476, 506, 525, 532, 550, 574, 631, 634, 646, 654, 659, 669, 680, 700, 705, 712, 717, 718, 721],
            '2ème Année Lycée' => [333, 338, 366, 519, 531, 565],
            '2ème Année Collège' => [399, 400, 474, 485, 495, 497, 504, 569, 601, 615, 649, 656, 661, 664, 685, 688, 689, 696],
            '2ème Année Primaire' => [647, 657, 667, 683, 691, 697, 724, 727],
            '3ème Année Primaire' => [603, 639, 667, 670, 686, 687, 692, 699, 702, 720],
            '4ème Année Primaire' => [578, 582, 589, 632, 633, 636, 651, 675],
            '5ème Année Primaire' => [571, 572, 588, 629, 642, 650, 690, 695, 710],
            '6ème Année Primaire' => [576, 603, 605, 606, 609, 618, 666, 694, 698],
            '3ème Année Lycée' => [503, 539, 556, 599, 614, 616, 618, 619, 621, 641, 663, 679, 722, 730]  // Tronc Commun = 3ème Année Lycée
        ];

        $assigned = 0;
        $notFound = 0;
        $errors = 0;

        foreach ($classesMatricules as $nomClasse => $matricules) {
            $this->info("\n--- Traitement de la classe: {$nomClasse} ---");

            // Trouver la classe dans la base de données
            $classe = Classe::where('nom', $nomClasse)->first();
            if (!$classe) {
                $this->warn("Classe '{$nomClasse}' non trouvée dans la base de données");
                continue;
            }

            foreach ($matricules as $matricule) {
                try {
                    // Convertir le matricule en format string avec zéros si nécessaire
                    $matriculeStr = str_pad($matricule, 4, '0', STR_PAD_LEFT);

                    // Chercher l'élève par matricule
                    $eleve = Eleve::where('numero_matricule', $matriculeStr)
                                 ->orWhere('numero_matricule', (string)$matricule)
                                 ->first();

                    if ($eleve) {
                        // Vérifier si l'élève n'est pas déjà assigné à cette classe
                        if ($eleve->classe_id === $classe->id) {
                            $this->line("  ◦ {$eleve->nom} {$eleve->prenom} (mat: {$matricule}) déjà dans {$nomClasse}");
                            continue;
                        }

                        // Assigner l'élève à la classe
                        $eleve->classe_id = $classe->id;
                        $eleve->niveau_scolaire = $classe->nom;
                        $eleve->save();

                        $assigned++;
                        $this->info("  ✓ {$eleve->nom} {$eleve->prenom} (mat: {$matricule}) assigné à {$nomClasse}");
                    } else {
                        $notFound++;
                        $this->warn("  ✗ Élève avec matricule {$matricule} non trouvé");
                    }
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("  ✗ Erreur pour matricule {$matricule}: " . $e->getMessage());
                }
            }
        }

        $this->info("\n=== RÉSUMÉ DE L'ASSIGNATION ===");
        $this->info("Élèves assignés avec succès: {$assigned}");
        if ($notFound > 0) {
            $this->warn("Élèves non trouvés: {$notFound}");
        }
        if ($errors > 0) {
            $this->error("Erreurs rencontrées: {$errors}");
        }

        // Afficher les statistiques par classe
        $this->info("\n=== STATISTIQUES PAR CLASSE ===");
        foreach ($classesMatricules as $nomClasse => $matricules) {
            $classe = Classe::where('nom', $nomClasse)->first();
            if ($classe) {
                $nombreEleves = Eleve::where('classe_id', $classe->id)->count();
                $this->line("{$nomClasse}: {$nombreEleves} élève(s)");
            }
        }

        return 0;
    }
}
