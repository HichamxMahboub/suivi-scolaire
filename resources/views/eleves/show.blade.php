@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $eleve->nom }} {{ $eleve->prenom }}</h1>
                        <p class="text-gray-600">Détails de l'élève</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('eleves.edit', $eleve) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Photo/Avatar -->
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="w-32 h-32 mx-auto bg-gray-300 rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl font-bold text-gray-600">
                                {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                        <p class="text-gray-600">{{ $eleve->numero_matricule ?? 'N/A' }}</p>
                    </div>

                    <!-- Informations de base -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations de base</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Date de naissance</p>
                                <p class="text-gray-900">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Lieu de naissance</p>
                                <p class="text-gray-900">{{ $eleve->lieu_naissance ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Sexe</p>
                                <p class="text-gray-900">{{ $eleve->sexe == 'M' ? 'Masculin' : ($eleve->sexe == 'F' ? 'Féminin' : 'Non renseigné') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Classe</p>
                                <p class="text-gray-900">{{ $eleve->classe->nom ?? 'Non assigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations de contact</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email</p>
                                <p class="text-gray-900">{{ $eleve->email ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Téléphone</p>
                                <p class="text-gray-900">{{ $eleve->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Adresse</p>
                                <p class="text-gray-900">{{ $eleve->adresse ?? 'Non renseignée' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations médicales -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations médicales</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Groupe sanguin</p>
                                <p class="text-gray-900">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Allergies</p>
                                <p class="text-gray-900">{{ $eleve->allergies ?? 'Aucune' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Remarques médicales</p>
                                <p class="text-gray-900">{{ $eleve->remarques_medicales ?? 'Aucune' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parcours scolaire -->
                <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Parcours scolaire</h3>
                        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.getElementById('add-parcours-modal').classList.remove('hidden'); return false;">
                            + Ajouter une année
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Année scolaire</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Établissement</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Niveau</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Résultat</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Moyenne</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eleve->parcoursScolaires as $parcours)
                                <tr>
                                    <td class="px-4 py-2">{{ $parcours->annee_scolaire }}</td>
                                    <td class="px-4 py-2">{{ $parcours->etablissement }}</td>
                                    <td class="px-4 py-2">{{ $parcours->niveau }}</td>
                                    <td class="px-4 py-2">{{ $parcours->resultat }}</td>
                                    <td class="px-4 py-2">{{ $parcours->moyenne ?? '-' }}</td>
                                    <td class="px-4 py-2 flex gap-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-900" onclick="editParcours({{ $parcours->id }})">Modifier</a>
                                        <form method="POST" action="{{ route('parcours-scolaires.destroy', $parcours) }}" onsubmit="return confirm('Supprimer cette ligne de parcours ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">Aucun parcours scolaire enregistré</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Actions rapides</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('messages.create', ['eleve_id' => $eleve->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Envoyer un message
                        </a>
                        <a href="{{ route('messages.by-eleve', $eleve) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Voir les messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajout parcours scolaire -->
<div id="add-parcours-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8 relative">
        <button onclick="document.getElementById('add-parcours-modal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-blue-700">Ajouter une année au parcours scolaire</h2>
        <form method="POST" action="{{ route('parcours-scolaires.store', $eleve) }}">
            @csrf
            <div class="mb-4">
                <label for="annee_scolaire" class="block text-gray-700 font-semibold mb-1">Année scolaire</label>
                <input type="text" name="annee_scolaire" id="annee_scolaire" class="w-full border rounded px-3 py-2" placeholder="2023/2024" required>
            </div>
            <div class="mb-4">
                <label for="etablissement" class="block text-gray-700 font-semibold mb-1">Établissement</label>
                <input type="text" name="etablissement" id="etablissement" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="niveau" class="block text-gray-700 font-semibold mb-1">Niveau</label>
                <input type="text" name="niveau" id="niveau" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="resultat" class="block text-gray-700 font-semibold mb-1">Résultat</label>
                <input type="text" name="resultat" id="resultat" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-6">
                <label for="moyenne" class="block text-gray-700 font-semibold mb-1">Moyenne (optionnel)</label>
                <input type="number" step="0.01" name="moyenne" id="moyenne" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('add-parcours-modal').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Ajouter</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal édition parcours scolaire -->
<div id="edit-parcours-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8 relative">
        <button onclick="document.getElementById('edit-parcours-modal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-blue-700">Modifier une année du parcours scolaire</h2>
        <form id="edit-parcours-form" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_annee_scolaire" class="block text-gray-700 font-semibold mb-1">Année scolaire</label>
                <input type="text" name="annee_scolaire" id="edit_annee_scolaire" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_etablissement" class="block text-gray-700 font-semibold mb-1">Établissement</label>
                <input type="text" name="etablissement" id="edit_etablissement" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_niveau" class="block text-gray-700 font-semibold mb-1">Niveau</label>
                <input type="text" name="niveau" id="edit_niveau" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_resultat" class="block text-gray-700 font-semibold mb-1">Résultat</label>
                <input type="text" name="resultat" id="edit_resultat" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-6">
                <label for="edit_moyenne" class="block text-gray-700 font-semibold mb-1">Moyenne (optionnel)</label>
                <input type="number" step="0.01" name="moyenne" id="edit_moyenne" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('edit-parcours-modal').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
<script>
    function editParcours(id) {
        // Récupérer les données de la ligne à éditer
        const parcours = @json($eleve->parcoursScolaires->keyBy('id'));
        const p = parcours[id];
        if (!p) return;
        document.getElementById('edit_annee_scolaire').value = p.annee_scolaire;
        document.getElementById('edit_etablissement').value = p.etablissement;
        document.getElementById('edit_niveau').value = p.niveau;
        document.getElementById('edit_resultat').value = p.resultat;
        document.getElementById('edit_moyenne').value = p.moyenne ?? '';
        // Définir l'action du formulaire
        document.getElementById('edit-parcours-form').action = '/parcours-scolaires/' + id;
        document.getElementById('edit-parcours-modal').classList.remove('hidden');
    }
</script>
@endsection