<?php
namespace App\Http\Controllers;

use App\Models\ParcoursScolaire;
use App\Models\Eleve;
use Illuminate\Http\Request;

class ParcoursScolaireController extends Controller
{
    public function store(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            'annee_scolaire' => 'required|string|max:9',
            'etablissement' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'resultat' => 'required|string|max:255',
            'moyenne' => 'nullable|numeric',
        ]);
        $validated['eleve_id'] = $eleve->id;
        ParcoursScolaire::create($validated);
        return redirect()->route('eleves.show', $eleve)->with('success', 'Année ajoutée au parcours scolaire.');
    }

    public function update(Request $request, ParcoursScolaire $parcours)
    {
        $validated = $request->validate([
            'annee_scolaire' => 'required|string|max:9',
            'etablissement' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
            'resultat' => 'required|string|max:255',
            'moyenne' => 'nullable|numeric',
        ]);
        $parcours->update($validated);
        return redirect()->route('eleves.show', $parcours->eleve_id)->with('success', 'Année du parcours scolaire modifiée.');
    }

    public function destroy(ParcoursScolaire $parcours)
    {
        $eleveId = $parcours->eleve_id;
        $parcours->delete();
        return redirect()->route('eleves.show', $eleveId)->with('success', 'Année supprimée du parcours scolaire.');
    }
} 