<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Exports\ElevesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class EleveController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Afficher la liste des élèves
     */
    public function index(Request $request)
    {
        $query = Eleve::query();

        // Recherche générale (étendue)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Recherche spécifique par nom/prénom
        if ($request->filled('nom_search')) {
            $nomSearch = $request->nom_search;
            $query->where(function($q) use ($nomSearch) {
                $q->where('nom', 'like', "%{$nomSearch}%")
                  ->orWhere('prenom', 'like', "%{$nomSearch}%");
            });
        }

        // Recherche par téléphone
        if ($request->filled('phone_search')) {
            $phoneSearch = $request->phone_search;
            $query->where(function($q) use ($phoneSearch) {
                $q->where('telephones_parent_1', 'like', "%{$phoneSearch}%")
                  ->orWhere('telephones_parent_2', 'like', "%{$phoneSearch}%");
            });
        }

        // Filtre par cycle
        $cycle = $request->get('cycle');
        if ($cycle) {
            $niveauxCycle = [];
            if ($cycle === 'Primaire') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxPrimaire());
            } elseif ($cycle === 'Collège') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxCollege());
            } elseif ($cycle === 'Lycée') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxLycee());
            }
            $query->whereIn('niveau_scolaire', $niveauxCycle);
        }

        // Filtre par niveau
        if ($request->filled('niveau')) {
            $query->where('niveau_scolaire', $request->niveau);
        }

        // Filtre par filière (pour le lycée)
        if ($request->filled('filiere')) {
            $query->where('niveau_scolaire', $request->filiere);
        }

        // Filtre par classe
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        // Filtre par année d'entrée
        if ($request->filled('annee')) {
            $query->where('annee_entree', $request->annee);
        }

        // Filtre par statut de classe
        if ($request->filled('classe_status')) {
            if ($request->classe_status === 'with_class') {
                $query->whereNotNull('classe_id');
            } elseif ($request->classe_status === 'without_class') {
                $query->whereNull('classe_id');
            }
        }

        // Tri
        $sortBy = $request->get('sort', 'nom');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $eleves = $query->with('classeInfo')->paginate(20)->withQueryString();

        // Données pour les filtres dynamiques
        $cycles = ['Primaire', 'Collège', 'Lycée'];
        $niveaux = \App\Helpers\NiveauScolaireHelper::getNiveauxOrdonnes();
        $filiereBac = \App\Helpers\NiveauScolaireHelper::getFiliereBac();
        $classes = \App\Models\Classe::active()->orderBy('nom')->get();
        $annees = Eleve::distinct()->pluck('annee_entree')->filter()->sort()->reverse();

        // Affichage groupé
        $groupBy = $request->get('group_by'); // 'cycle', 'niveau', 'filiere', null
        $grouped = null;
        if ($groupBy === 'cycle') {
            $grouped = $eleves->groupBy(function($eleve) {
                return \App\Helpers\NiveauScolaireHelper::getCycleNiveau($eleve->niveau_scolaire);
            });
        } elseif ($groupBy === 'niveau') {
            $grouped = $eleves->groupBy('niveau_scolaire');
        } elseif ($groupBy === 'filiere') {
            $grouped = $eleves->groupBy(function($eleve) {
                return \App\Helpers\NiveauScolaireHelper::getNomNiveau($eleve->niveau_scolaire);
            });
        }

        $totalEleves = Eleve::count();
        $elevesActifs = 0; // Colonne statut n'existe pas
        $elevesInactifs = 0; // Colonne statut n'existe pas

        // Vérifier si c'est une requête pour la version temps réel
        if ($request->has('realtime')) {
            return view('eleves.index-realtime', compact(
                'eleves', 'cycles', 'niveaux', 'filiereBac', 'classes', 'annees', 'groupBy', 'grouped',
                'totalEleves', 'elevesActifs', 'elevesInactifs'
            ));
        }

        return view('eleves.index', compact(
            'eleves', 'cycles', 'niveaux', 'filiereBac', 'classes', 'annees', 'groupBy', 'grouped',
            'totalEleves', 'elevesActifs', 'elevesInactifs'
        ));
    }

    /**
     * API endpoint pour récupérer les données des élèves en temps réel
     */
    public function apiData(Request $request)
    {
        $query = Eleve::query();

        // Appliquer les mêmes filtres que la méthode index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('cne', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classe')) {
            $query->where('classe_id', $request->classe);
        }

        if ($request->filled('statut')) {
            // Simuler un statut basé sur la présence d'une classe
            if ($request->statut === 'actif') {
                $query->whereNotNull('classe_id');
            } elseif ($request->statut === 'inactif') {
                $query->whereNull('classe_id');
            }
        }

        $eleves = $query->with('classeInfo')->orderBy('nom', 'asc')->get();

        // Préparer les données pour le JSON
        $elevesData = $eleves->map(function($eleve) {
            return [
                'id' => $eleve->id,
                'nom' => $eleve->nom,
                'prenom' => $eleve->prenom,
                'email' => $eleve->email,
                'cne' => $eleve->cne,
                'date_naissance_formatted' => $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : 'Non renseignée',
                'classe' => $eleve->classeInfo ? [
                    'nom' => $eleve->classeInfo->nom
                ] : null,
                'statut' => $eleve->classe_id ? 'actif' : 'inactif',
            ];
        });

        // Calculer les statistiques
        $statistics = [
            'total' => $eleves->count(),
            'actifs' => $eleves->whereNotNull('classe_id')->count(),
            'inactifs' => $eleves->whereNull('classe_id')->count(),
            'classes' => \App\Models\Classe::count()
        ];

        // Créer un hash pour détecter les changements
        $hash = md5(json_encode($elevesData) . json_encode($statistics));

        return response()->json([
            'eleves' => $elevesData,
            'statistics' => $statistics,
            'hash' => $hash,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $classes = Classe::where('active', true)->orderBy('nom')->get();
        // Générer le prochain numéro matricule
        $maxMatricule = Eleve::max('numero_matricule');
        $nextMatricule = 1;
        if ($maxMatricule) {
            // On retire les zéros à gauche pour éviter les problèmes de string
            $nextMatricule = intval(ltrim($maxMatricule, '0')) + 1;
        }
        $numero_matricule = str_pad($nextMatricule, 4, '0', STR_PAD_LEFT);
        return view('eleves.create', compact('classes', 'numero_matricule'));
    }

    /**
     * Enregistrer un nouvel élève
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'niveau_scolaire' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'annee_entree' => 'nullable|integer',
            'adresse' => 'nullable|string',
            'nom_parent_1' => 'nullable|string|max:255',
            'lien_parent_1' => 'nullable|string|max:255',
            'telephones_parent_1' => 'nullable|string|max:255',
            'nom_parent_2' => 'nullable|string|max:255',
            'lien_parent_2' => 'nullable|string|max:255',
            'telephones_parent_2' => 'nullable|string|max:255',
            'etablissement_actuel' => 'nullable|string|max:255',
            'redoublant' => 'nullable|boolean',
            'niveaux_redoubles' => 'nullable|array',
            'niveaux_redoubles.*' => 'string',
            'annee_sortie' => 'nullable|integer',
            'cause_sortie' => 'nullable|string|max:255',
        ]);

        // Générer le prochain numéro matricule (sécurité contre la concurrence)
        $maxMatricule = \App\Models\Eleve::max('numero_matricule');
        $nextMatricule = 1;
        if ($maxMatricule) {
            $nextMatricule = intval(ltrim($maxMatricule, '0')) + 1;
        }
        $numero_matricule = str_pad($nextMatricule, 4, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['numero_matricule'] = $numero_matricule;
        // Stocker les niveaux redoublés comme JSON
        $data['niveaux_redoubles'] = $request->has('niveaux_redoubles') ? json_encode($request->niveaux_redoubles) : null;

        \App\Models\Eleve::create($data);

        return redirect()->route('eleves.index')
            ->with('success', 'Élève ajouté avec succès.');
    }

    /**
     * Afficher un élève
     */
    public function show(Eleve $eleve)
    {
        return view('eleves.show', compact('eleve'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Eleve $eleve)
    {
        $classes = Classe::where('active', true)->orderBy('nom')->get();
        return view('eleves.edit', compact('eleve', 'classes'));
    }

    /**
     * Mettre à jour un élève
     */
    public function update(Request $request, Eleve $eleve)
    {
        $request->validate([
            'numero_matricule' => 'nullable|string|max:255|unique:eleves,numero_matricule,' . $eleve->id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'niveau_scolaire' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'annee_entree' => 'nullable|integer',
            'adresse' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'telephone_parent' => 'nullable|string|max:255',
            'remarques' => 'nullable|string',
            'educateur_responsable' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'redoublant' => 'nullable|boolean',
            'niveaux_redoubles' => 'nullable|array',
            'niveaux_redoubles.*' => 'string',
            'annee_sortie' => 'nullable|integer',
            'cause_sortie' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        // Stocker les niveaux redoublés comme JSON
        $data['niveaux_redoubles'] = $request->has('niveaux_redoubles') ? json_encode($request->niveaux_redoubles) : null;

        // Gestion de l'upload de la photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('eleves', 'public');
            $data['photo'] = $photoPath;
        } else {
            unset($data['photo']); // Ne pas écraser si non modifié
        }

        $eleve->update($data);

        return redirect()->route('eleves.index')
            ->with('success', 'Élève mis à jour avec succès.');
    }

    /**
     * Supprimer un élève
     */
    public function destroy(Eleve $eleve)
    {
        $eleve->delete();

        // Si c'est une requête AJAX, retourner du JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Élève supprimé avec succès.'
            ]);
        }

        return redirect()->route('eleves.index')
            ->with('success', 'Élève supprimé avec succès.');
    }

    /**
     * Afficher le formulaire d'import
     */
    public function importForm()
    {
        return view('eleves.import');
    }

    /**
     * Importer des élèves depuis un fichier CSV avec aperçu
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:2048'
        ]);

        // Si l'utilisateur demande un aperçu
        if ($request->has('preview')) {
            $file = $request->file('file');
            [$headers, $rows] = $this->extractRowsFromFile($file);
            // On renvoie la vue avec l'aperçu
            return view('eleves.import', [
                'headers' => $headers,
                'rows' => $rows,
                'preview' => true,
                'file_tmp' => base64_encode(file_get_contents($file->getRealPath())),
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        // Si l'utilisateur valide l'import définitif
        if (($request->has('file_tmp') && $request->has('file_name') && $request->has('confirm_import')) ||
            ($request->has('file_tmp') && $request->has('file_name') && $request->has('confirm_import_and_user'))) {
            
            try {
                // On reconstitue le fichier temporaire
                $tmpPath = storage_path('app/tmp_' . uniqid() . '_' . $request->file_name);
                file_put_contents($tmpPath, base64_decode($request->file_tmp));

                // Si l'utilisateur a sélectionné des lignes spécifiques
                $selectedRows = $request->input('rows', []);
                if (!empty($selectedRows)) {
                    // On lit le CSV, on ne garde que les lignes sélectionnées
                    $handle = fopen($tmpPath, 'r');
                    $headers = fgetcsv($handle);
                    $allRows = [];
                    $rowIndex = 0;
                    while (($data = fgetcsv($handle)) !== false) {
                        if (in_array($rowIndex, $selectedRows)) {
                            $allRows[] = $data;
                        }
                        $rowIndex++;
                    }
                    fclose($handle);

                    // Importer directement les données sélectionnées
                    $imported = $this->importRowsDirectly($headers, $allRows);
                    
                    // Nettoyer le fichier temporaire
                    if (file_exists($tmpPath)) {
                        unlink($tmpPath);
                    }

                    if ($imported > 0) {
                        return redirect()->route('eleves.index')
                            ->with('success', "Import terminé avec succès. {$imported} élève(s) importé(s).");
                    } else {
                        return redirect()->route('eleves.index')
                            ->with('warning', "Aucun élève n'a été importé. Vérifiez les données ou les doublons.");
                    }
                } else {
                    // Si aucune ligne sélectionnée
                    if (file_exists($tmpPath)) {
                        unlink($tmpPath);
                    }
                    return back()->withErrors(['error' => 'Aucune ligne sélectionnée pour l\'import.']);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'import d\'élèves', ['error' => $e->getMessage()]);
                return back()->withErrors(['error' => 'Erreur lors de l\'import: ' . $e->getMessage()]);
            }
        }

        // Import direct si pas d'aperçu demandé
        if (!$request->has('preview') && $request->hasFile('file')) {
            try {
                $file = $request->file('file');
                [$headers, $rows] = $this->extractRowsFromFile($file);
                
                $imported = $this->importRowsDirectly($headers, $rows);
                
                if ($imported > 0) {
                    return redirect()->route('eleves.index')
                        ->with('success', "Import direct terminé avec succès. {$imported} élève(s) importé(s).");
                } else {
                    return redirect()->route('eleves.index')
                        ->with('warning', "Aucun élève n'a été importé. Vérifiez les données ou les doublons.");
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'import direct d\'élèves', ['error' => $e->getMessage()]);
                return back()->withErrors(['error' => 'Erreur lors de l\'import: ' . $e->getMessage()]);
            }
        }

        // Ajout d'un return par défaut pour éviter la page blanche
        return back()->withErrors(['error' => "Aucune action détectée lors de l'import. Veuillez suivre le processus : upload → aperçu → sélection → import."]);
    }

    /**
     * Importer les lignes directement sans utiliser exec
     */
    private function importRowsDirectly($headers, $rows)
    {
        $imported = 0;
        $errors = [];

        // DEBUGGING: Afficher les headers réels du fichier
        Log::info('Headers du fichier CSV:', ['headers' => $headers]);

        foreach ($rows as $rowIndex => $row) {
            try {
                // Mapping des colonnes
                $data = [];
                foreach ($headers as $index => $header) {
                    $value = isset($row[$index]) ? trim($row[$index]) : '';
                    // Traiter 'unknown' et valeurs vides comme NULL
                    if (empty($value) || strtolower($value) === 'unknown' || strtolower($value) === 'inconnu') {
                        $value = null;
                    }
                    $data[trim($header)] = $value;
                }

                // DEBUGGING: Afficher les clés disponibles dans $data
                if ($rowIndex === 0) {
                    Log::info('Clés disponibles dans data:', ['keys' => array_keys($data)]);
                }

                // Vérifier si l'élève existe déjà
                $existing = null;
                
                // Vérification par numero_matricule_type_ecole OU par une autre colonne matricule
                $matriculeKey = null;
                if (isset($data['numero_matricule_type_ecole'])) {
                    $matriculeKey = 'numero_matricule_type_ecole';
                } elseif (isset($data['numero_matricule'])) {
                    $matriculeKey = 'numero_matricule';
                } elseif (isset($data['matricule'])) {
                    $matriculeKey = 'matricule';
                }

                if ($matriculeKey && !empty($data[$matriculeKey])) {
                    $existing = Eleve::where('numero_matricule', $data[$matriculeKey])->first();
                }
                
                // Vérification par nom/prénom si pas de matricule
                if (!$existing && !empty($data['nom']) && !empty($data['prenom'])) {
                    $existing = Eleve::where('nom', $data['nom'])
                                   ->where('prenom', $data['prenom'])
                                   ->first();
                }

                if ($existing) {
                    continue; // Skip si déjà existant
                }

                // Créer le nouvel élève
                $eleve = new Eleve();
                
                // Champs principaux
                $eleve->nom = $data['nom'] ?: 'Nom_' . ($rowIndex + 1);
                $eleve->prenom = $data['prenom'] ?: 'Prénom_' . ($rowIndex + 1);
                
                // Codes d'identification (flexible)
                if ($matriculeKey) {
                    $eleve->numero_matricule = $data[$matriculeKey];
                }
                
                // Encadrement
                $eleve->educateur_responsable = $data['encadrant'] ?? null;
                
                // Date de naissance
                if (!empty($data['date_naissance'])) {
                    try {
                        // Format Excel: YYYY-MM-DD ou DD/MM/YYYY
                        if (strpos($data['date_naissance'], '-') !== false) {
                            $eleve->date_naissance = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_naissance']);
                        } else {
                            $eleve->date_naissance = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date_naissance']);
                        }
                    } catch (\Exception $e) {
                        $eleve->date_naissance = null;
                    }
                }
                
                // Année d'entrée
                $eleve->annee_entree = $data['annee_entree'] ?: date('Y');
                
                // Sexe (F = Féminin, G = Garçon/Masculin)
                $sexe = $data['sexe'];
                if ($sexe === 'G') {
                    $eleve->sexe = 'M'; // Convertir G en M pour Masculin
                } elseif ($sexe === 'F') {
                    $eleve->sexe = 'F'; // Garder F pour Féminin
                } else {
                    $eleve->sexe = null;
                }
                
                // Contact - garder vide car pas dans votre structure
                $eleve->email = null;
                $eleve->telephone_parent = null;
                
                // Assignation automatique à une classe basée sur le niveau
                $classeNom = $data['classe'];
                if (!empty($classeNom)) {
                    // Rechercher la classe directement par nom
                    $classe = \App\Models\Classe::where('nom', $classeNom)->first();
                    
                    if ($classe) {
                        $eleve->classe_id = $classe->id;
                        $eleve->niveau_scolaire = $classe->nom; // Utiliser le nom de la classe comme niveau
                    } else {
                        // Si la classe n'existe pas, utiliser comme niveau scolaire
                        $eleve->niveau_scolaire = $classeNom;
                    }
                }
                
                // Générer matricule si vide
                if (empty($eleve->numero_matricule)) {
                    $maxMatricule = Eleve::max('numero_matricule');
                    $nextMatricule = 1;
                    if ($maxMatricule && is_numeric($maxMatricule)) {
                        $nextMatricule = intval($maxMatricule) + 1;
                    }
                    $eleve->numero_matricule = str_pad($nextMatricule, 4, '0', STR_PAD_LEFT);
                }

                $eleve->save();
                $imported++;

            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($rowIndex + 1) . ": " . $e->getMessage();
                Log::error('Erreur import ligne ' . ($rowIndex + 1), [
                    'error' => $e->getMessage(), 
                    'data' => $data ?? [],
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        if (!empty($errors)) {
            Log::warning('Erreurs lors de l\'import', ['errors' => $errors]);
        }

        return $imported;
    }

    /**
     * Import direct sans aperçu
     */
    public function importDirect(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx|max:2048'
        ]);

        try {
            $file = $request->file('file');
            [$headers, $rows] = $this->extractRowsFromFile($file);
            
            $imported = $this->importRowsDirectly($headers, $rows);
            
            if ($imported > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Import terminé avec succès. {$imported} élève(s) importé(s).",
                    'imported' => $imported
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Aucun élève n'a été importé. Vérifiez les données ou les doublons.",
                    'imported' => 0
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'import direct d\'élèves', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage(),
                'imported' => 0
            ]);
        }
    }

    /**
     * Importer depuis un fichier spécifique (pour les tests)
     */
    public function importFromFile($filename = 'eleves_nettoye.csv')
    {
        try {
            $filePath = storage_path('app/' . $filename);
            
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Fichier non trouvé: ' . $filename], 404);
            }

            // Utiliser la commande Artisan pour l'import
            $output = [];
            $returnCode = 0;
            
            exec("php artisan eleves:import \"{$filePath}\" 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                return response()->json(['success' => 'Import terminé avec succès']);
            } else {
                $errorMessage = implode("\n", $output);
                return response()->json(['error' => 'Erreur lors de l\'import: ' . $errorMessage], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'import: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Crée un utilisateur pour chaque élève importé à partir du CSV
     */
    private function createUsersForImportedEleves($csvPath)
    {
        $handle = fopen($csvPath, 'r');
        $headers = fgetcsv($handle);
        $created = 0;
        
        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($headers, $data);
            
            // Validation des données requises
            if (empty($row['nom']) || empty($row['prenom'])) {
                continue;
            }
            
            // Nettoyage et validation des données
            $nom = trim($row['nom']);
            $prenom = trim($row['prenom']);
            
            if (strlen($nom) < 2 || strlen($prenom) < 2) {
                continue;
            }
            
            // Génération d'un email sécurisé
            $email = strtolower(preg_replace('/[^a-z0-9]/', '', $prenom)) . '.' . 
                     strtolower(preg_replace('/[^a-z0-9]/', '', $nom)) . '@ecole.local';
            
            // Vérification si l'utilisateur existe déjà
            if (!\App\Models\User::where('email', $email)->exists()) {
                try {
                    \App\Models\User::create([
                        'name' => ucfirst($prenom) . ' ' . ucfirst($nom),
                        'email' => $email,
                        'password' => Hash::make('changeme123'),
                        'role' => 'student', // Rôle par défaut
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    \Log::warning('Erreur création utilisateur pour élève', [
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'email' => $email,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
        fclose($handle);
        
        \Log::info("Création d'utilisateurs terminée", ['created' => $created]);
        return $created;
    }

    /**
     * Extraction universelle des données depuis un fichier CSV ou XLSX
     */
    private function extractRowsFromFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);
            $rows = [];
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
            return [$headers, $rows];
        } elseif ($extension === 'xlsx') {
            // Conversion simple Excel vers CSV temporaire
            try {
                // Si la bibliothèque Excel est disponible
                if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                    $collection = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $file)[0];
                    $headers = $collection->first() ? $collection->first()->toArray() : [];
                    $rows = $collection->slice(1)->map(function ($row) {
                        return $row->toArray();
                    })->toArray();
                    return [$headers, $rows];
                } else {
                    // Fallback: convertir en CSV
                    throw new \Exception('Bibliothèque Excel non disponible. Veuillez convertir votre fichier en CSV ou installer php-excel.');
                }
            } catch (\Exception $e) {
                throw new \Exception('Erreur lors de la lecture du fichier Excel: ' . $e->getMessage() . '. Veuillez utiliser un fichier CSV.');
            }
        } else {
            throw new \Exception('Format de fichier non supporté. Utilisez CSV ou Excel.');
        }
    }

    /**
     * Exporter les élèves filtrés en Excel
     */
    public function exportExcel(Request $request)
    {
        $eleves = $this->getFilteredEleves($request)->get();
        return Excel::download(new ElevesExport($eleves), 'eleves.xlsx');
    }

    /**
     * Exporter les élèves filtrés en PDF
     */
    public function exportPdf(Request $request)
    {
        $eleves = $this->getFilteredEleves($request)->get();
        $pdf = Pdf::loadView('eleves.export_pdf', compact('eleves'));
        return $pdf->download('eleves.pdf');
    }

    /**
     * Récupérer la query filtrée (pour export)
     */
    private function getFilteredEleves(Request $request)
    {
        $query = Eleve::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }
        $cycle = $request->get('cycle');
        if ($cycle) {
            $niveauxCycle = [];
            if ($cycle === 'Primaire') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxPrimaire());
            } elseif ($cycle === 'Collège') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxCollege());
            } elseif ($cycle === 'Lycée') {
                $niveauxCycle = array_keys(\App\Helpers\NiveauScolaireHelper::getNiveauxLycee());
            }
            $query->whereIn('niveau_scolaire', $niveauxCycle);
        }
        if ($request->filled('niveau')) {
            $query->where('niveau_scolaire', $request->niveau);
        }
        if ($request->filled('filiere')) {
            $query->where('niveau_scolaire', $request->filiere);
        }
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }
        if ($request->filled('annee')) {
            $query->where('annee_entree', $request->annee);
        }
        return $query->with('classeInfo');
    }

    /**
     * Afficher les statistiques des élèves par niveau
     */
    public function stats()
    {
        // Calculer la répartition par âge
        $elevesAvecAge = Eleve::whereNotNull('date_naissance')->get();
        $parAge = [];
        
        foreach($elevesAvecAge as $eleve) {
            try {
                $dateNaissance = \Carbon\Carbon::parse($eleve->date_naissance);
                $age = $dateNaissance->age;
                
                if ($age >= 3 && $age <= 25) { // Filtrer les âges réalistes pour l'école
                    if (!isset($parAge[$age])) {
                        $parAge[$age] = 0;
                    }
                    $parAge[$age]++;
                }
            } catch (\Exception $e) {
                // Ignorer les dates invalides
                continue;
            }
        }
        
        // Trier par âge
        ksort($parAge);

        $stats = [
            'total' => Eleve::count(),
            'garcons' => Eleve::where('sexe', 'M')->count(),
            'filles' => Eleve::where('sexe', 'F')->count(),
            'classes' => Classe::count(),
            'par_age' => $parAge,
            'par_niveau' => Eleve::selectRaw('niveau_scolaire, count(*) as count')
                ->groupBy('niveau_scolaire')
                ->orderBy('niveau_scolaire')
                ->pluck('count', 'niveau_scolaire'),
            'par_cycle' => [
                'Primaire' => Eleve::whereIn('niveau_scolaire', \App\Helpers\NiveauScolaireHelper::getNiveauxPrimaire())->count(),
                'Collège' => Eleve::whereIn('niveau_scolaire', \App\Helpers\NiveauScolaireHelper::getNiveauxCollege())->count(),
                'Lycée' => Eleve::whereIn('niveau_scolaire', \App\Helpers\NiveauScolaireHelper::getNiveauxLycee())->count(),
            ],
            'par_classe' => Eleve::join('classes', 'eleves.classe_id', '=', 'classes.id')
                ->selectRaw('classes.nom, count(*) as count')
                ->groupBy('classes.id', 'classes.nom')
                ->orderBy('classes.nom')
                ->pluck('count', 'nom'),
            'par_etablissement' => [
                'Primaire' => Eleve::where('type_etablissement', 'primaire')->count(),
                'Collège' => Eleve::where('type_etablissement', 'college')->count(),
                'Lycée' => Eleve::where('type_etablissement', 'lycee')->count(),
                'Non défini' => Eleve::whereNull('type_etablissement')->count(),
            ],
            'sans_classe' => Eleve::whereNull('classe_id')->count(),
        ];

        return view('eleves.stats', compact('stats'));
    }

    /**
     * Télécharger le modèle CSV d'import des élèves
     */
    public function downloadImportModele()
    {
        $headers = [
            'numero_matricule','nom','prenom','niveau_scolaire','classe','date_naissance','annee_entree',
            'educateur_responsable','sexe','email','telephone_parent','adresse','photo','remarques','redoublant','niveau_redouble','annee_sortie','cause_sortie'
        ];
        $rows = [
            [
                'E001','Alami','Mohammed','CP1','1A','2010-03-15','2023','Mme Martin','M','eleve1@email.com','0612345678','123 Rue Hassan II','photo1.jpg','Remarque sur l\'élève','0','','',''
            ],
            [
                'E002','Benjelloun','Amina','CP2','2B','2011-08-22','2022','M. Dubois','F','eleve2@email.com','0623456789','456 Avenue Mohammed V','photo2.jpg','','1','CP1','2023','Transfert'
            ]
        ];
        $filename = 'modele_import_eleves.csv';
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Télécharger un modèle vierge de fiche élève (CVC) au format CSV
     */
    public function downloadFicheCvcModele()
    {
        $headers = [
            'NUMERO_MATRICULE*','NOM*','PRENOM*','NIVEAU_SCOLAIRE*','CLASSE*','DATE_NAISSANCE*','ANNEE_ENTREE*',
            'educateur_responsable','sexe','email','telephone_parent','adresse','photo','remarques','redoublant','niveau_redouble','annee_sortie','cause_sortie'
        ];
        $filename = 'fiche_cvc_modele.csv';
        $handle = fopen('php://temp', 'r+');
        // Ligne de commentaire
        fwrite($handle, "# Les champs marqués d'un * sont obligatoires. Laissez les autres vides si besoin.\n");
        fputcsv($handle, $headers);
        for ($i = 0; $i < 3; $i++) {
            fputcsv($handle, array_fill(0, count($headers), ''));
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Mettre à jour les informations de base de l'élève
     */
    public function updateBasic(Request $request, Eleve $eleve)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'sexe' => 'nullable|in:M,F',
            'numero_matricule' => 'nullable|string|max:50',
            'type_etablissement' => 'nullable|in:primaire,college,lycee',
            'classe_id' => 'nullable|exists:classes,id'
        ]);

        $eleve->update($request->only([
            'nom', 'prenom', 'date_naissance', 'lieu_naissance', 
            'sexe', 'numero_matricule', 'type_etablissement', 'classe_id'
        ]));

        return redirect()->route('eleves.show', $eleve)
            ->with('success', 'Informations de base mises à jour avec succès.');
    }

    /**
     * Mettre à jour les informations de contact de l'élève
     */
    public function updateContact(Request $request, Eleve $eleve)
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'contact_urgence' => 'nullable|string|max:20',
            'nom_tuteur' => 'nullable|string|max:255',
            'profession_pere' => 'nullable|string|max:255',
            'profession_mere' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'situation_familiale' => 'nullable|string|max:100',
            'nombre_freres_soeurs' => 'nullable|integer|min:0'
        ]);

        $eleve->update($request->only([
            'email', 'telephone', 'adresse', 'contact_urgence', 'nom_tuteur',
            'profession_pere', 'profession_mere', 'nationalite', 
            'situation_familiale', 'nombre_freres_soeurs'
        ]));

        return redirect()->route('eleves.show', $eleve)
            ->with('success', 'Informations de contact mises à jour avec succès.');
    }

    /**
     * Mettre à jour les informations médicales de l'élève
     */
    public function updateMedical(Request $request, Eleve $eleve)
    {
        // Vérifier les permissions pour modifier les informations médicales
        $this->authorize('updateMedical', $eleve);

        $request->validate([
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string',
            'remarques_medicales' => 'nullable|string',
            'medecin_traitant' => 'nullable|string|max:255',
            'numero_assurance' => 'nullable|string|max:50',
            'transport_scolaire' => 'nullable|boolean',
            'bourse_etudes' => 'nullable|boolean'
        ]);

        $eleve->update($request->only([
            'groupe_sanguin', 'allergies', 'remarques_medicales', 
            'medecin_traitant', 'numero_assurance', 'transport_scolaire', 'bourse_etudes'
        ]));

        return redirect()->route('eleves.show', $eleve)
            ->with('success', 'Informations médicales mises à jour avec succès.');
    }

    /**
     * Mettre à jour la photo de l'élève
     */
    public function updatePhoto(Request $request, Eleve $eleve)
    {
        // Si on demande à supprimer la photo
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($eleve->photo) {
                $photoPath = public_path('storage/' . $eleve->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            $eleve->update(['photo' => null]);
            
            return redirect()->route('eleves.show', $eleve)
                ->with('success', 'Photo supprimée avec succès.');
        }

        // Validation du fichier photo
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        // Générer un nom unique pour la photo
        $photoFile = $request->file('photo');
        $fileName = time() . '_' . $eleve->id . '.' . $photoFile->getClientOriginalExtension();

        // Supprimer l'ancienne photo si elle existe
        if ($eleve->photo) {
            $oldPhotoPath = public_path('storage/' . $eleve->photo);
            if (file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        }
        
        // Définir le chemin de destination
        $destinationPath = public_path('storage/photos/eleves');
        
        // Déplacer le fichier
        $photoFile->move($destinationPath, $fileName);
        
        // Chemin relatif pour la base de données
        $photoPath = 'photos/eleves/' . $fileName;

        // Mettre à jour l'élève
        $eleve->update(['photo' => $photoPath]);

        return redirect()->route('eleves.show', $eleve)
            ->with('success', 'Photo mise à jour avec succès.');
    }

    /**
     * Afficher le profil complet de l'élève
     */
    public function profileComplete(Eleve $eleve)
    {
        return view('eleves.profile-complete', compact('eleve'));
    }

    /**
     * Exporter le profil de l'élève en PDF
     */
    public function exportToPdf(Eleve $eleve)
    {
        $pdf = Pdf::loadView('eleves.profile-pdf', compact('eleve'));
        
        return $pdf->download('profil_' . $eleve->nom . '_' . $eleve->prenom . '.pdf');
    }
} 