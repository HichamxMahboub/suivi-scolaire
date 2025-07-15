<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Exports\ElevesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class EleveController extends Controller
{
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

        $eleves = $query->with('classe')->paginate(20)->withQueryString();

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

        return view('eleves.index', compact(
            'eleves', 'cycles', 'niveaux', 'filiereBac', 'classes', 'annees', 'groupBy', 'grouped',
            'totalEleves', 'elevesActifs', 'elevesInactifs'
        ));
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
            // On reconstitue le fichier temporaire
            $tmpPath = storage_path('app/tmp_' . uniqid() . '_' . $request->file_name);
            file_put_contents($tmpPath, base64_decode($request->file_tmp));

            // Si l'utilisateur a sélectionné des lignes spécifiques
            $selectedRows = $request->input('rows', []);
            if (!empty($selectedRows)) {
                // On lit le CSV, on ne garde que les lignes sélectionnées, puis on écrit un nouveau CSV temporaire
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
                // On réécrit le CSV avec uniquement les lignes sélectionnées
                $filteredPath = storage_path('app/tmp_filtered_' . uniqid() . '_' . $request->file_name);
                $fw = fopen($filteredPath, 'w');
                fputcsv($fw, $headers);
                foreach ($allRows as $row) {
                    fputcsv($fw, $row);
                }
                fclose($fw);
                unlink($tmpPath);
                $filePath = $filteredPath;
            } else {
                // Si aucune ligne sélectionnée, on ne fait rien
                unlink($tmpPath);
                return back()->withErrors(['error' => 'Aucune ligne sélectionnée pour l\'import.']);
            }

            try {
                $output = [];
                $returnCode = 0;
                exec("php artisan eleves:import \"{$filePath}\" 2>&1", $output, $returnCode);
                \Log::info('Résultat import élèves', ['output' => $output, 'returnCode' => $returnCode]);
                if (isset($filteredPath) && file_exists($filteredPath)) {
                    unlink($filteredPath);
                }
                // Création des profils utilisateurs si demandé
                if ($request->has('confirm_import_and_user')) {
                    $this->createUsersForImportedEleves($filePath);
                }
                if ($returnCode === 0) {
                    // Chercher le nombre d'élèves importés dans la sortie
                    $imported = 0;
                    $messages = [
                        'success' => [],
                        'warning' => [],
                        'error' => []
                    ];
                    foreach ($output as $line) {
                        if (preg_match('/^✓ (\d+) élèves importés/', $line, $matches)) {
                            $imported = (int)$matches[1];
                        }
                        if (str_starts_with($line, '✓')) {
                            $messages['success'][] = $line;
                        } elseif (str_starts_with($line, '⚠') || str_starts_with($line, 'Ligne')) {
                            $messages['warning'][] = $line;
                        } elseif (str_starts_with($line, '✗') || str_starts_with($line, 'Erreur')) {
                            $messages['error'][] = $line;
                        }
                    }
                    if ($imported > 0) {
                        return redirect()->route('eleves.index')
                            ->with('success', "Import des élèves terminé avec succès. {$imported} élève(s) importé(s)." . ($request->has('confirm_import_and_user') ? ' Profils créés.' : ''))
                            ->with('import_messages', $messages);
                    } else {
                        return redirect()->route('eleves.index')
                            ->with('warning', "Aucun élève n'a été importé. Vérifiez votre fichier CSV ou les doublons.")
                            ->with('import_messages', $messages);
                    }
                } else {
                    $errorMessage = implode("\n", $output);
                    return back()->withErrors(['error' => 'Erreur lors de l\'import: ' . $errorMessage]);
                }
            } catch (\Exception $e) {
                if (isset($filteredPath) && file_exists($filteredPath)) {
                    unlink($filteredPath);
                }
                return back()->withErrors(['error' => 'Erreur lors de l\'import: ' . $e->getMessage()]);
            }
        }
        // Ajout d'un return par défaut pour éviter la page blanche
        return back()->withErrors(['error' => "Aucune action détectée lors de l'import. Veuillez suivre le processus : upload → aperçu → sélection → import."]);
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
                        'password' => \Hash::make('changeme123'),
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
            $collection = Excel::toCollection(null, $file)[0];
            $headers = $collection->first() ? $collection->first()->toArray() : [];
            $rows = $collection->slice(1)->map(function ($row) {
                return $row->toArray();
            })->toArray();
            return [$headers, $rows];
        } else {
            throw new \Exception('Format de fichier non supporté');
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
        return $query->with('classe');
    }

    /**
     * Afficher les statistiques des élèves par niveau
     */
    public function stats()
    {
        $stats = [
            'total' => Eleve::count(),
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
} 