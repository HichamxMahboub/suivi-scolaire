<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Encadrant;

class CreateEncadrantsFromEleves extends Command
{
    protected $signature = 'encadrants:create-from-eleves';
    protected $description = 'Créer automatiquement les encadrants à partir des données des élèves et créer les relations';

    public function handle()
    {
        $this->info('=== CRÉATION AUTOMATIQUE DES ENCADRANTS ===');
        
        // Récupérer tous les noms d'encadrants uniques depuis les élèves
        $encadrantsNoms = Eleve::whereNotNull('educateur_responsable')
                             ->where('educateur_responsable', '!=', '')
                             ->distinct()
                             ->pluck('educateur_responsable')
                             ->filter()
                             ->unique();
        
        $this->info("Encadrants trouvés dans les données élèves : " . $encadrantsNoms->count());
        
        $created = 0;
        $skipped = 0;
        
        foreach($encadrantsNoms as $nomComplet) {
            $nomComplet = trim($nomComplet);
            
            // Vérifier si l'encadrant existe déjà
            $existing = Encadrant::where('nom', $nomComplet)
                                ->orWhere(function($query) use ($nomComplet) {
                                    $parts = explode(' ', $nomComplet);
                                    if (count($parts) >= 2) {
                                        $query->where('prenom', $parts[0])
                                              ->where('nom', implode(' ', array_slice($parts, 1)));
                                    }
                                })
                                ->first();
            
            if ($existing) {
                $skipped++;
                $this->line("  ◦ {$nomComplet} existe déjà (ID: {$existing->id}), ignoré");
                continue;
            }
            
            // Analyser le nom pour séparer nom/prénom
            $parts = explode(' ', $nomComplet);
            
            if (count($parts) >= 2) {
                // Si plusieurs mots, prendre le premier comme prénom et le reste comme nom
                $prenom = $parts[0];
                $nom = implode(' ', array_slice($parts, 1));
            } else {
                // Si un seul mot, l'utiliser comme prénom ET nom pour éviter les contraintes
                $prenom = $nomComplet;
                $nom = $nomComplet;
            }
            
            try {
                // Créer l'encadrant
                $encadrant = Encadrant::create([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $this->generateUniqueEmail($nom, $prenom),
                    'telephone' => null,
                    'adresse' => null,
                    'remarque' => 'Créé automatiquement depuis les données élèves',
                    'numero' => $this->generateNumero(),
                    'matricule' => $this->generateUniqueMatricule(),
                ]);
                
                $created++;
                $this->info("  ✓ Encadrant créé : {$encadrant->prenom} {$encadrant->nom} (ID: {$encadrant->id})");
                
                // Compter combien d'élèves sont associés à cet encadrant
                $nombreEleves = Eleve::where('educateur_responsable', $nomComplet)->count();
                $this->line("    → {$nombreEleves} élève(s) associé(s)");
                
            } catch (\Exception $e) {
                $this->error("  ✗ Erreur création encadrant {$nomComplet}: " . $e->getMessage());
                continue;
            }
        }
        
        $this->info("\n=== CRÉATION DES RELATIONS ÉLÈVES-ENCADRANTS ===");
        
        // Maintenant créer les relations entre élèves et encadrants
        $linked = 0;
        $notFound = 0;
        
        $eleves = Eleve::whereNotNull('educateur_responsable')
                      ->where('educateur_responsable', '!=', '')
                      ->get();
        
        foreach($eleves as $eleve) {
            $nomEncadrant = trim($eleve->educateur_responsable);
            
            // Rechercher l'encadrant correspondant
            $encadrant = Encadrant::where('nom', $nomEncadrant)
                                 ->orWhere(function($query) use ($nomEncadrant) {
                                     $parts = explode(' ', $nomEncadrant);
                                     if (count($parts) >= 2) {
                                         $prenom = $parts[0];
                                         $nom = implode(' ', array_slice($parts, 1));
                                         $query->where('prenom', $prenom)->where('nom', $nom);
                                     } else {
                                         // Pour les noms simples, chercher par prénom ou nom
                                         $query->where('prenom', $nomEncadrant)->orWhere('nom', $nomEncadrant);
                                     }
                                 })
                                 ->first();
            
            if ($encadrant) {
                $eleve->encadrant_id = $encadrant->id;
                $eleve->save();
                $linked++;
                $this->info("  ✓ {$eleve->nom} {$eleve->prenom} → {$encadrant->prenom} {$encadrant->nom}");
            } else {
                $notFound++;
                $this->warn("  ✗ Encadrant non trouvé pour : {$nomEncadrant}");
            }
        }
        
        $this->info("Relations créées : {$linked}");
        if ($notFound > 0) {
            $this->warn("Encadrants non trouvés : {$notFound}");
        }
        
        $this->info("\n=== RÉSUMÉ ===");
        $this->info("Encadrants créés : {$created}");
        $this->info("Encadrants ignorés (déjà existants) : {$skipped}");
        $this->info("Relations élèves-encadrants créées : {$linked}");
        
        // Afficher la liste des encadrants avec le nombre d'élèves
        $this->info("\n=== LISTE DES ENCADRANTS ET LEURS ÉLÈVES ===");
        $encadrants = Encadrant::with('eleves')->get();
        foreach($encadrants as $encadrant) {
            $nombreEleves = $encadrant->eleves->count();
            $this->line("• {$encadrant->prenom} {$encadrant->nom} : {$nombreEleves} élève(s)");
        }
        
        return 0;
    }
    
    /**
     * Générer un email unique pour l'encadrant
     */
    private function generateUniqueEmail($nom, $prenom)
    {
        $nomClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nom));
        $prenomClean = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $prenom));
        
        $baseEmail = "{$prenomClean}.{$nomClean}@ecole.local";
        $email = $baseEmail;
        $counter = 1;
        
        // Vérifier l'unicité
        while (Encadrant::where('email', $email)->exists()) {
            $email = "{$prenomClean}.{$nomClean}{$counter}@ecole.local";
            $counter++;
        }
        
        return $email;
    }
    
    /**
     * Générer un numéro unique pour l'encadrant
     */
    private function generateNumero()
    {
        $maxNumero = Encadrant::max('numero');
        return $maxNumero ? $maxNumero + 1 : 1;
    }
    
    /**
     * Générer un matricule unique pour l'encadrant
     */
    private function generateUniqueMatricule()
    {
        do {
            $maxMatricule = Encadrant::max('matricule');
            if ($maxMatricule) {
                // Extraire le numéro du matricule (ex: ENC001 -> 1)
                $numero = intval(substr($maxMatricule, 3));
                $nextNumero = $numero + 1;
            } else {
                $nextNumero = 1;
            }
            
            $matricule = 'ENC' . str_pad($nextNumero, 3, '0', STR_PAD_LEFT);
            
            // Vérifier que ce matricule n'existe pas déjà
            $exists = Encadrant::where('matricule', $matricule)->exists();
            
        } while ($exists);
        
        return $matricule;
    }
}