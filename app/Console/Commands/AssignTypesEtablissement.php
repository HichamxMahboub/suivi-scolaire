<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;

class AssignTypesEtablissement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eleves:assign-types-etablissement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner automatiquement les types d\'établissement (primaire, collège, lycée) aux élèves selon leur niveau';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== ASSIGNATION DES TYPES D\'ÉTABLISSEMENT ===');

        // Définir les mappings niveau -> type d'établissement
        $typesMapping = [
            // Primaire (1ère à 6ème année)
            '1ère Année Primaire' => 'primaire',
            '1ERE ANNEE PRIMAIRE' => 'primaire',
            '2ème Année Primaire' => 'primaire',
            '2EME ANNEE PRIMAIRE' => 'primaire',
            '3ème Année Primaire' => 'primaire',
            '3EME ANNEE PRIMAIRE' => 'primaire',
            '4ème Année Primaire' => 'primaire',
            '4EME ANNEE PRIMAIRE' => 'primaire',
            '5ème Année Primaire' => 'primaire',
            '5EME ANNEE PRIMAIRE' => 'primaire',
            '6ème Année Primaire' => 'primaire',
            '6EME ANNEE PRIMAIRE' => 'primaire',
            
            // Collège (1ère à 3ème année)
            '1ère Année Collège' => 'college',
            '1ERE ANNEE COLLEGE' => 'college',
            '2ème Année Collège' => 'college',
            '2EME ANNEE COLLEGE' => 'college',
            '3ème Année Collège' => 'college',
            '3EME ANNEE COLLEGE' => 'college',
            
            // Lycée (1ère à 4ème année)
            '1ère Année Lycée' => 'lycee',
            '1ERE ANNEE LYCEE' => 'lycee',
            '2ème Année Lycée' => 'lycee',
            '2EME ANNEE LYCEE' => 'lycee',
            '3ème Année Lycée' => 'lycee',
            '3EME ANNEE LYCEE' => 'lycee',
            '4ème Année Lycée' => 'lycee',
            '4EME ANNEE LYCEE' => 'lycee',
            
            // Anciens noms français (pour compatibilité)
            'CP' => 'primaire',
            'CE1' => 'primaire', 
            'CE2' => 'primaire',
            'CM1' => 'primaire',
            'CM2' => 'primaire',
            'CP/CE1' => 'primaire',
            'CE1/CE2' => 'primaire',
            'CE2/CM1' => 'primaire',
            'CM1/CM2' => 'primaire',
            
            '6ème' => 'college',
            '6EME' => 'college',
            '5ème' => 'college', 
            '5EME' => 'college',
            '4ème' => 'college',
            '4EME' => 'college',
            '3ème' => 'college',
            '3EME' => 'college',
            'Sixième' => 'college',
            'Cinquième' => 'college',
            'Quatrième' => 'college',
            'Troisième' => 'college',
            
            '2nde' => 'lycee',
            '2NDE' => 'lycee',
            'Seconde' => 'lycee',
            '1ère' => 'lycee',
            '1ERE' => 'lycee',
            'Première' => 'lycee',
            'Terminale' => 'lycee',
            'TERMINALE' => 'lycee',
            'Tale' => 'lycee',
            'TALE' => 'lycee',
        ];

        $updated = 0;
        $skipped = 0;
        $manual = 0;

        // Récupérer tous les élèves
        $eleves = Eleve::with('classeInfo')->get();
        
        $this->info("Traitement de {$eleves->count()} élèves...\n");

        foreach ($eleves as $eleve) {
            // Si l'élève n'a pas de classe assignée, passer
            if (!$eleve->classeInfo) {
                $this->warn("  ⚠️ {$eleve->prenom} {$eleve->nom} : Pas de classe assignée");
                $skipped++;
                continue;
            }

            $nomClasse = $eleve->classeInfo->nom;
            
            // Chercher une correspondance directe
            $typeDetecte = null;
            foreach ($typesMapping as $pattern => $type) {
                if (strcasecmp(trim($nomClasse), trim($pattern)) === 0) {
                    $typeDetecte = $type;
                    break;
                }
            }
            
            // Si pas trouvé, essayer la détection intelligente
            if (!$typeDetecte) {
                $typeDetecte = $this->detectTypeIntelligent($nomClasse);
            }

            if ($typeDetecte) {
                // Mettre à jour le type d'établissement
                $eleve->type_etablissement = $typeDetecte;
                $eleve->save();
                
                $emoji = $this->getEmojiForType($typeDetecte);
                $this->info("  ✅ {$eleve->nom} {$eleve->prenom} ({$nomClasse}) → {$emoji} " . ucfirst($typeDetecte));
                $updated++;
            } else {
                $this->warn("  ❓ {$eleve->nom} {$eleve->prenom} ({$nomClasse}) → Type non détecté automatiquement");
                $manual++;
            }
        }

        $this->info("\n=== RÉSUMÉ ===");
        $this->info("✅ Élèves mis à jour automatiquement : {$updated}");
        $this->warn("⚠️ Élèves sans classe : {$skipped}");
        $this->warn("❓ Élèves nécessitant une assignation manuelle : {$manual}");

        // Afficher les statistiques par type
        $this->info("\n=== RÉPARTITION PAR TYPE D'ÉTABLISSEMENT ===");
        $stats = Eleve::selectRaw('type_etablissement, COUNT(*) as count')
                     ->groupBy('type_etablissement')
                     ->get();

        foreach ($stats as $stat) {
            $type = $stat->type_etablissement ?: 'Non défini';
            $emoji = $this->getEmojiForType($stat->type_etablissement);
            $this->line("  {$emoji} " . ucfirst($type) . " : {$stat->count} élève(s)");
        }

        // Suggérer des actions pour les cas non détectés
        if ($manual > 0) {
            $this->info("\n=== CLASSES NON DÉTECTÉES ===");
            $classesNonDetectees = Eleve::whereNull('type_etablissement')
                                      ->whereNotNull('classe_id')
                                      ->with('classeInfo')
                                      ->get()
                                      ->pluck('classeInfo.nom')
                                      ->unique()
                                      ->filter();

            foreach ($classesNonDetectees as $classe) {
                $this->warn("  • {$classe}");
            }

            $this->info("\n💡 Suggestion : Vous pouvez mettre à jour manuellement ces types via l'interface web ou ajouter ces niveaux dans la commande.");
        }

        return 0;
    }

    /**
     * Détection intelligente du type d'établissement
     */
    private function detectTypeIntelligent($nomClasse)
    {
        $nomClasse = strtolower($nomClasse);
        
        // Détecter "primaire" dans le nom
        if (preg_match('/primaire/', $nomClasse)) {
            return 'primaire';
        }
        
        // Détecter "collège" dans le nom  
        if (preg_match('/coll[èe]ge/', $nomClasse)) {
            return 'college';
        }
        
        // Détecter "lycée" dans le nom
        if (preg_match('/lyc[ée]e/', $nomClasse)) {
            return 'lycee';
        }
        
        // Détecter par numéro d'année pour primaire (1-6)
        if (preg_match('/[1-6].*ann[ée]e.*primaire/', $nomClasse)) {
            return 'primaire';
        }
        
        // Détecter par numéro d'année pour collège (1-3)
        if (preg_match('/[1-3].*ann[ée]e.*coll[èe]ge/', $nomClasse)) {
            return 'college';
        }
        
        // Détecter par numéro d'année pour lycée (1+)
        if (preg_match('/[1-4].*ann[ée]e.*lyc[ée]e/', $nomClasse)) {
            return 'lycee';
        }
        
        return null;
    }

    /**
     * Obtenir l'emoji correspondant au type d'établissement
     */
    private function getEmojiForType($type)
    {
        return match($type) {
            'primaire' => '🏫',
            'college' => '🏛️',
            'lycee' => '🎓',
            default => '❓'
        };
    }
}
