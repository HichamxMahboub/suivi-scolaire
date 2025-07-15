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
        return view('encadrants.show', compact('encadrant'));
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
} 