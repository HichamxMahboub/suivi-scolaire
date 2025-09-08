<?php

namespace App\Http\Controllers;

use App\Models\Encadrant;
use Illuminate\Http\Request;

class EncadrantController extends Controller
{
    /**
     * Afficher la liste des encadrants
     */
    public function index()
    {
        $encadrants = Encadrant::paginate(20);
        $totalEncadrants = Encadrant::count();
        return view('encadrants.index', compact('encadrants', 'totalEncadrants'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('encadrants.create');
    }

    /**
     * Enregistrer un nouvel encadrant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:50|unique:encadrants,matricule',
            'email' => 'required|email|unique:encadrants,email',
            'telephone' => 'nullable|string|max:50',
            'numero' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'remarque' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('encadrants', 'public');
        }
        Encadrant::create($validated);
        return redirect()->route('encadrants.index')->with('success', 'Encadrant ajouté avec succès.');
    }

    /**
     * Afficher un encadrant
     */
    public function show(Encadrant $encadrant)
    {
        // Charger les élèves avec leurs classes
        $encadrant->load(['eleves.classeInfo']);

        // Récupérer les élèves qui n'ont pas d'encadrant ou qui ont un autre encadrant
        $elevesDisponibles = \App\Models\Eleve::whereNull('encadrant_id')
                                            ->orWhere('encadrant_id', '!=', $encadrant->id)
                                            ->with('classeInfo')
                                            ->orderBy('nom')
                                            ->orderBy('prenom')
                                            ->get();

        return view('encadrants.show', compact('encadrant', 'elevesDisponibles'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Encadrant $encadrant)
    {
        return view('encadrants.edit', compact('encadrant'));
    }

    /**
     * Mettre à jour un encadrant
     */
    public function update(Request $request, Encadrant $encadrant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:50|unique:encadrants,matricule,' . $encadrant->id,
            'email' => 'required|email|unique:encadrants,email,' . $encadrant->id,
            'telephone' => 'nullable|string|max:50',
            'numero' => 'nullable|string|max:50',
            'adresse' => 'nullable|string|max:255',
            'remarque' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('encadrants', 'public');
        }
        $encadrant->update($validated);
        return redirect()->route('encadrants.index')->with('success', 'Encadrant modifié avec succès.');
    }

    /**
     * Supprimer un encadrant
     */
    public function destroy(Encadrant $encadrant)
    {
        $encadrant->delete();
        return redirect()->route('encadrants.index')->with('success', 'Encadrant supprimé avec succès.');
    }

    /**
     * Ajouter un élève à un encadrant
     */
    public function addStudent(Request $request, Encadrant $encadrant)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id'
        ]);

        $eleve = \App\Models\Eleve::findOrFail($request->eleve_id);

        // Vérifier si l'élève n'est pas déjà assigné à cet encadrant
        if ($eleve->encadrant_id == $encadrant->id) {
            return redirect()->back()->with('error', 'Cet élève est déjà assigné à cet encadrant.');
        }

        // Assigner l'élève à l'encadrant
        $eleve->encadrant_id = $encadrant->id;
        $eleve->save();

        return redirect()->back()->with('success', "L'élève {$eleve->prenom} {$eleve->nom} a été ajouté avec succès à l'encadrant {$encadrant->prenom} {$encadrant->nom}.");
    }

    /**
     * Retirer un élève d'un encadrant
     */
    public function removeStudent(Request $request, Encadrant $encadrant)
    {
        $request->validate([
            'eleve_id' => 'required|exists:eleves,id'
        ]);

        $eleve = \App\Models\Eleve::findOrFail($request->eleve_id);

        // Vérifier que l'élève appartient bien à cet encadrant
        if ($eleve->encadrant_id != $encadrant->id) {
            return redirect()->back()->with('error', 'Cet élève n\'est pas assigné à cet encadrant.');
        }

        // Retirer l'élève de l'encadrant
        $eleve->encadrant_id = null;
        $eleve->save();

        return redirect()->back()->with('success', "L'élève {$eleve->prenom} {$eleve->nom} a été retiré avec succès de l'encadrant {$encadrant->prenom} {$encadrant->nom}.");
    }
}
