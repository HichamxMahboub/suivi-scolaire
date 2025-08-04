<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ClasseController extends Controller
{
    /**
     * Afficher la liste des classes
     */
    public function index(Request $request)
    {
        $query = Classe::withCount('eleves');

        // Filtres
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }

        if ($request->filled('annee_scolaire')) {
            $query->where('annee_scolaire', $request->annee_scolaire);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->active);
        }

        // Tri
        $sortBy = $request->get('sort', 'nom');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $classes = $query->paginate(20)->withQueryString();

        // Données pour les filtres
        $niveaux = Classe::distinct()->pluck('niveau')->filter();
        $annees = Classe::distinct()->pluck('annee_scolaire')->filter()->sort()->reverse();

        return view('classes.index', compact('classes', 'niveaux', 'annees'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Cache les niveaux scolaires (1 heure)
        $niveaux = Cache::remember('niveaux_scolaires', 3600, function () {
            return \App\Helpers\NiveauScolaireHelper::getNiveauxParGroupe();
        });

        // Cache les années scolaires (1 heure)
        $annees = Cache::remember('annees_scolaires', 3600, function () {
            $annees = [];
            $anneeActuelle = date('Y');
            for ($i = 0; $i < 5; $i++) {
                $annee = $anneeActuelle - $i;
                $annees[$annee . '-' . ($annee + 1)] = $annee . '/' . ($annee + 1);
            }
            return $annees;
        });

        return view('classes.create', compact('niveaux', 'annees'));
    }

    /**
     * Enregistrer une nouvelle classe
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professeur_principal' => 'nullable|string|max:255',
            'effectif_max' => 'required|integer|min:1|max:100',
            'active' => 'boolean',
        ]);

        Classe::create($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    /**
     * Afficher une classe
     */
    public function show(Classe $classe)
    {
        $eleves = $classe->eleves()->orderBy('nom')->paginate(20);
        
        // Statistiques de la classe
        $stats = [
            'total_eleves' => $classe->eleves()->count(),
            'moyenne_generale' => $this->calculerMoyenneClasse($classe),
        ];

        return view('classes.show', compact('classe', 'eleves', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Classe $classe)
    {
        // Cache les niveaux scolaires (1 heure)
        $niveaux = Cache::remember('niveaux_scolaires_ordonnes', 3600, function () {
            return \App\Helpers\NiveauScolaireHelper::getNiveauxOrdonnes();
        });

        // Cache les années scolaires (1 heure)
        $annees = Cache::remember('annees_scolaires', 3600, function () {
            $annees = [];
            $anneeActuelle = date('Y');
            for ($i = 0; $i < 5; $i++) {
                $annee = $anneeActuelle - $i;
                $annees[$annee . '-' . ($annee + 1)] = $annee . '/' . ($annee + 1);
            }
            return $annees;
        });

        return view('classes.edit', compact('classe', 'niveaux', 'annees'));
    }

    /**
     * Mettre à jour une classe
     */
    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'annee_scolaire' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professeur_principal' => 'nullable|string|max:255',
            'effectif_max' => 'required|integer|min:1|max:100',
            'active' => 'boolean',
        ]);

        $classe->update($request->all());

        return redirect()->route('classes.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    /**
     * Supprimer une classe
     */
    public function destroy(Classe $classe)
    {
        if ($classe->eleves()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une classe qui contient des élèves.');
        }

        $classe->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Classe supprimée avec succès.');
    }

    /**
     * Calculer la moyenne générale d'une classe
     */
    private function calculerMoyenneClasse(Classe $classe)
    {
        $moyenne = $classe->notes()->avg('note_vingt');
        return $moyenne ? round($moyenne, 2) : 0;
    }

    /**
     * Assigner des élèves à une classe
     */
    public function assignerEleves(Request $request, Classe $classe)
    {
        $request->validate([
            'eleves' => 'required|array',
            'eleves.*' => 'exists:eleves,id',
        ]);

        $elevesIds = $request->eleves;
        
        // Vérifier la capacité de la classe
        if ($classe->eleves()->count() + count($elevesIds) > $classe->effectif_max) {
            return back()->with('error', 'La classe ne peut pas accueillir plus d\'élèves.');
        }

        // Assigner les élèves
        Eleve::whereIn('id', $elevesIds)->update(['classe_id' => $classe->id]);

        return back()->with('success', count($elevesIds) . ' élève(s) assigné(s) à la classe.');
    }

    /**
     * Retirer des élèves d'une classe
     */
    public function retirerEleves(Request $request, Classe $classe)
    {
        $request->validate([
            'eleves' => 'required|array',
            'eleves.*' => 'exists:eleves,id',
        ]);

        $elevesIds = $request->eleves;
        
        // Retirer les élèves
        Eleve::whereIn('id', $elevesIds)->update(['classe_id' => null]);

        return back()->with('success', count($elevesIds) . ' élève(s) retiré(s) de la classe.');
    }
} 