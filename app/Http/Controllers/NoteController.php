<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NoteController extends Controller
{
    /**
     * Afficher les niveaux pour un type d'établissement
     */
    public function niveaux(Request $request, $typeEtablissement)
    {
        // Vérifier que le type d'établissement est valide
        if (!in_array($typeEtablissement, ['primaire', 'college', 'lycee'])) {
            abort(404);
        }

        // Récupérer les classes pour ce type d'établissement
        $classes = Classe::whereHas('eleves', function($q) use ($typeEtablissement) {
            $q->where('type_etablissement', $typeEtablissement);
        })->with(['eleves' => function($q) use ($typeEtablissement) {
            $q->where('type_etablissement', $typeEtablissement);
        }])->orderBy('nom')->get();

        $stats = [
            'total_eleves' => Eleve::where('type_etablissement', $typeEtablissement)->count(),
            'total_classes' => $classes->count(),
            'total_notes' => Note::whereHas('eleve', function($q) use ($typeEtablissement) {
                $q->where('type_etablissement', $typeEtablissement);
            })->count()
        ];

        return view('notes.niveaux', compact('typeEtablissement', 'classes', 'stats'));
    }

    /**
     * Afficher les élèves d'une classe pour la gestion des notes
     */
    public function eleves(Request $request, $typeEtablissement, $classeId)
    {
        // Vérifier que le type d'établissement est valide
        if (!in_array($typeEtablissement, ['primaire', 'college', 'lycee'])) {
            abort(404);
        }

        $classe = Classe::findOrFail($classeId);
        $eleves = $classe->eleves()->where('type_etablissement', $typeEtablissement)->orderBy('nom')->get();

        // Récupérer les matières pour ce type d'établissement
        $matieres = \App\Helpers\NotesHelper::getMatieresByType($typeEtablissement);

        return view('notes.eleves', compact('typeEtablissement', 'classe', 'eleves', 'matieres'));
    }

    /**
     * Afficher le formulaire de création d'une note pour un élève spécifique
     */
    public function createForEleve(Request $request, $typeEtablissement, $eleveId)
    {
        // Vérifier que le type d'établissement est valide
        if (!in_array($typeEtablissement, ['primaire', 'college', 'lycee'])) {
            abort(404);
        }

        $eleve = Eleve::findOrFail($eleveId);
        
        // Vérifier que l'élève correspond au type d'établissement
        if ($eleve->type_etablissement !== $typeEtablissement) {
            abort(404, 'Élève non trouvé pour ce type d\'établissement');
        }

        // Récupérer la configuration des matières pour ce type d'établissement
        $matieres = \App\Helpers\NotesHelper::getMatieresByType($typeEtablissement);
        $semestres = \App\Helpers\NotesHelper::getSemestresByType($typeEtablissement);

        // Récupérer les enseignants
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();

        return view('notes.create-for-eleve', compact('eleve', 'typeEtablissement', 'matieres', 'semestres', 'enseignants'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si aucun type d'établissement n'est sélectionné, afficher la page de sélection
        if (!$request->filled('type_etablissement')) {
            // Calculer les statistiques par type d'établissement
            $stats = [
                'primaire' => Eleve::where('type_etablissement', 'primaire')->count(),
                'college' => Eleve::where('type_etablissement', 'college')->count(),
                'lycee' => Eleve::where('type_etablissement', 'lycee')->count(),
            ];
            
            return view('notes.home', compact('stats'));
        }

        // Si un type d'établissement est sélectionné, continuer avec la logique existante
        $typeEtablissement = $request->get('type_etablissement');
        
        $query = Note::with(['eleve.classeInfo', 'enseignant'])
                     ->whereHas('eleve', function($q) use ($typeEtablissement) {
                         $q->where('type_etablissement', $typeEtablissement);
                     });

        // Filtres
        if ($request->filled('eleve_id')) {
            $query->where('eleve_id', $request->eleve_id);
        }

        if ($request->filled('matiere')) {
            $query->where('matiere', $request->matiere);
        }

        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        if ($request->filled('classe_id')) {
            $query->whereHas('eleve', function($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('matiere', 'like', "%$search%")
                  ->orWhere('type_evaluation', 'like', "%$search%")
                  ->orWhereHas('eleve', function($eleveQuery) use ($search) {
                      $eleveQuery->where('nom', 'like', "%$search%")
                                 ->orWhere('prenom', 'like', "%$search%");
                  });
            });
        }

        $notes = $query->orderBy('date_evaluation', 'desc')->paginate(20);

        // Données pour les filtres - filtrées par type d'établissement
        $eleves = Eleve::where('type_etablissement', $typeEtablissement)->orderBy('nom')->get();
        $classes = Classe::whereHas('eleves', function($q) use ($typeEtablissement) {
            $q->where('type_etablissement', $typeEtablissement);
        })->orderBy('nom')->get();
        $matieres = Note::whereHas('eleve', function($q) use ($typeEtablissement) {
            $q->where('type_etablissement', $typeEtablissement);
        })->distinct('matiere')->orderBy('matiere')->pluck('matiere');
        $semestres = ['S1', 'S2'];

        // Vérifier si c'est une requête pour la version temps réel
        if ($request->has('realtime')) {
            return view('notes.index-realtime', compact('notes', 'eleves', 'classes', 'matieres', 'semestres', 'typeEtablissement'));
        }

        return view('notes.index', compact('notes', 'eleves', 'classes', 'matieres', 'semestres', 'typeEtablissement'));
    }

    /**
     * API endpoint pour récupérer les données en temps réel
     */
    public function apiData(Request $request)
    {
        $query = Note::with(['eleve.classe', 'enseignant']);

        // Appliquer les mêmes filtres que la méthode index
        if ($request->filled('eleve_id')) {
            $query->where('eleve_id', $request->eleve_id);
        }

        if ($request->filled('matiere')) {
            $query->where('matiere', $request->matiere);
        }

        if ($request->filled('semestre')) {
            $query->where('semestre', $request->semestre);
        }

        if ($request->filled('classe_id')) {
            $query->whereHas('eleve', function($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('matiere', 'like', "%$search%")
                  ->orWhere('type_evaluation', 'like', "%$search%")
                  ->orWhereHas('eleve', function($eleveQuery) use ($search) {
                      $eleveQuery->where('nom', 'like', "%$search%")
                                 ->orWhere('prenom', 'like', "%$search%");
                  });
            });
        }

        $notes = $query->orderBy('date_evaluation', 'desc')->get();

        // Préparer les données pour le JSON
        $notesData = $notes->map(function($note) {
            return [
                'id' => $note->id,
                'eleve' => [
                    'nom' => $note->eleve->nom,
                    'prenom' => $note->eleve->prenom,
                    'classe' => $note->eleve->classe ? [
                        'nom' => $note->eleve->classe->nom
                    ] : null
                ],
                'matiere' => $note->matiere,
                'type_evaluation' => $note->type_evaluation,
                'note' => $note->note,
                'note_sur' => $note->note_sur,
                'note_vingt' => $note->note_vingt,
                'mention' => $note->mention,
                'couleur' => $note->couleur,
                'date_evaluation_formatted' => $note->date_evaluation ? $note->date_evaluation->format('d/m/Y') : now()->format('d/m/Y'),
                'semestre' => $note->semestre,
            ];
        });

        // Calculer les statistiques
        $statistics = [
            'total' => $notes->count(),
            'admis' => $notes->where('note_vingt', '>=', 10)->count(),
            'insuffisant' => $notes->where('note_vingt', '<', 10)->count(),
            'moyenne' => $notes->count() > 0 ? number_format($notes->avg('note_vingt'), 2) : '0.00'
        ];

        // Créer un hash pour détecter les changements
        $hash = md5(json_encode($notesData) . json_encode($statistics));

        return response()->json([
            'notes' => $notesData,
            'statistics' => $statistics,
            'hash' => $hash,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();
        
        // Matières prédéfinies
        $matieres = [
            'Mathématiques',
            'Français', 
            'Arabe',
            'Anglais',
            'Sciences Physiques',
            'Sciences de la Vie et de la Terre',
            'Histoire-Géographie',
            'Éducation Islamique',
        ];

        $typesEvaluation = [
            'Contrôle Continu',
            'Devoir Surveillé',
            'Composition',
            'Examen',
            'Oral',
        ];

        return view('notes.create', compact('eleves', 'enseignants', 'matieres', 'typesEvaluation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation simplifiée
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'matiere' => 'required|string|max:255',
            'note' => 'required|numeric|min:0',
            'semestre' => 'required|string|in:S1,S2',
            'commentaire' => 'nullable|string|max:500',
            'enseignant_id' => 'nullable|exists:users,id',
        ]);

        // Récupérer l'élève pour valider le type d'établissement et déterminer note_sur
        $eleve = Eleve::findOrFail($request->eleve_id);
        $typeEtablissement = $eleve->type_etablissement;
        
        // Déterminer la note sur selon le type d'établissement
        $noteSur = ($typeEtablissement === 'primaire') ? 10 : 20;
        
        // Valider que la note ne dépasse pas le maximum
        if ($request->note > $noteSur) {
            return back()->withErrors(['note' => "La note ne peut pas dépasser {$noteSur} pour le {$typeEtablissement}."])->withInput();
        }

        // Créer la note avec les données complètes
        Note::create([
            'eleve_id' => $request->eleve_id,
            'matiere' => $request->matiere,
            'type_evaluation' => 'Évaluation', // Valeur par défaut
            'note' => $request->note,
            'note_sur' => $noteSur,
            'date_evaluation' => now()->format('Y-m-d'), // Date actuelle
            'semestre' => $request->semestre,
            'commentaire' => $request->commentaire,
            'enseignant_id' => $request->enseignant_id,
        ]);

        // Redirection selon le contexte
        if ($request->has('type_etablissement')) {
            $typeEtab = $request->type_etablissement;
            if ($request->has('classe_id')) {
                return redirect()->route('notes.eleves', ['type_etablissement' => $typeEtab, 'classe' => $request->classe_id])
                    ->with('success', 'Note créée avec succès.');
            } else {
                return redirect()->route('notes.niveaux', ['type_etablissement' => $typeEtab])
                    ->with('success', 'Note créée avec succès.');
            }
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $note->load(['eleve.classe', 'enseignant']);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $eleves = Eleve::with('classe')->orderBy('nom')->get();
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();
        
        return view('notes.edit', compact('note', 'eleves', 'enseignants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id',
            'matiere' => 'required|string|max:255',
            'type_evaluation' => 'required|string|max:255',
            'note' => 'required|numeric|min:0',
            'note_sur' => 'required|numeric|min:1|max:100',
            'date_evaluation' => 'required|date',
            'semestre' => 'required|in:S1,S2',
            'commentaire' => 'nullable|string|max:500',
            'enseignant_id' => 'nullable|exists:users,id',
        ]);

        $note->update($request->all());

        return redirect()->route('notes.index')
            ->with('success', 'Note modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();

        // Si c'est une requête AJAX, retourner du JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Note supprimée avec succès.'
            ]);
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note supprimée avec succès.');
    }

    /**
     * Afficher les statistiques des notes
     */
    public function statistiques(Request $request)
    {
        $semestre = $request->get('semestre', 'S2');
        $matiere = $request->get('matiere');

        $query = Note::where('semestre', $semestre);
        
        if ($matiere) {
            $query->where('matiere', $matiere);
        }

        $notes = $query->get();

        $stats = [
            'total_notes' => $notes->count(),
            'moyenne_generale' => $notes->avg('note_vingt'),
            'note_max' => $notes->max('note_vingt'),
            'note_min' => $notes->min('note_vingt'),
            'nb_eleves' => $notes->unique('eleve_id')->count(),
        ];

        // Répartition des mentions
        $mentions = [
            'Très Bien' => $notes->where('note_vingt', '>=', 16)->count(),
            'Bien' => $notes->where('note_vingt', '>=', 14)->where('note_vingt', '<', 16)->count(),
            'Assez Bien' => $notes->where('note_vingt', '>=', 12)->where('note_vingt', '<', 14)->count(),
            'Passable' => $notes->where('note_vingt', '>=', 10)->where('note_vingt', '<', 12)->count(),
            'Insuffisant' => $notes->where('note_vingt', '<', 10)->count(),
        ];

        // Données pour les filtres
        $matieres = Note::distinct('matiere')->orderBy('matiere')->pluck('matiere');

        return view('notes.statistiques', compact('stats', 'mentions', 'semestre', 'matiere', 'matieres'));
    }

    /**
     * Afficher le bulletin d'un élève
     */
    public function bulletin(Eleve $eleve, Request $request)
    {
        $semestre = $request->get('semestre', 'S2');
        
        $notes = Note::where('eleve_id', $eleve->id)
                    ->where('semestre', $semestre)
                    ->orderBy('matiere')
                    ->get();

        $moyennes = $notes->groupBy('matiere')->map(function($notesMatiere) {
            return $notesMatiere->avg('note_vingt');
        });

        $moyenneGenerale = $moyennes->avg();

        return view('notes.bulletin', compact('eleve', 'notes', 'moyennes', 'moyenneGenerale', 'semestre'));
    }
}
