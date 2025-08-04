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
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Photo/Avatar -->
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="relative w-32 h-32 mx-auto mb-4">
                            <x-eleve-photo :eleve="$eleve" size="xxl" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                        <p class="text-gray-600 mb-3">{{ $eleve->numero_matricule ?? 'N/A' }}</p>
                        
                        <!-- Bouton modifier photo repositionné -->
                        <button onclick="document.getElementById('photo-input').click()" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                            📷 Modifier photo
                        </button>
                        
                        <!-- Input file caché -->
                        <form id="photo-form" method="POST" action="{{ route('eleves.update.photo', $eleve) }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="file" id="photo-input" name="photo" accept="image/*" onchange="uploadPhoto()" class="hidden">
                        </form>
                    </div>

                    <!-- Informations de base -->
                    <div class="md:col-span-2 bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg border border-blue-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-blue-800 flex items-center">
                                ℹ️ Informations de base
                            </h3>
                            <button onclick="openModal('info-base-modal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                                ✏️ Modifier
                            </button>
                        </div>
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
                            <div>
                                <p class="text-sm font-medium text-gray-700">Type d'établissement</p>
                                <p class="text-gray-900">{{ ucfirst($eleve->type_etablissement ?? 'Non défini') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Numéro matricule</p>
                                <p class="text-gray-900">{{ $eleve->numero_matricule ?? 'Non attribué' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section avec bouton Afficher plus -->
                <div class="mb-6 text-center">
                    <a href="{{ route('eleves.profile.complete', $eleve) }}" class="inline-block bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg text-base shadow-lg transition-all duration-300 transform hover:scale-105">
                        📋 Afficher toutes les informations
                    </a>
                    <p class="text-sm text-gray-500 mt-2">Cliquez pour voir le profil complet de l'élève</p>
                </div>

                <!-- Informations détaillées -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-lg border border-green-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-green-800 flex items-center">
                                📞 Informations de contact
                            </h3>
                            <button onclick="openModal('contact-modal')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                                ✏️ Modifier
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white p-3 rounded-lg border border-green-100">
                                <p class="text-sm font-medium text-green-700 flex items-center mb-1">
                                    📧 Email
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->email ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-green-100">
                                <p class="text-sm font-medium text-green-700 flex items-center mb-1">
                                    📱 Téléphone
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-green-100">
                                <p class="text-sm font-medium text-green-700 flex items-center mb-1">
                                    🏠 Adresse
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->adresse ?? 'Non renseignée' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-green-100">
                                <p class="text-sm font-medium text-green-700 flex items-center mb-1">
                                    🆘 Contact d'urgence
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->contact_urgence ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-green-100">
                                <p class="text-sm font-medium text-green-700 flex items-center mb-1">
                                    👨‍👩‍👧‍👦 Nom tuteur/parent
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->nom_tuteur ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>

                    @can('viewMedical', $eleve)
                    <!-- Informations médicales -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl shadow-lg border border-red-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-red-800 flex items-center">
                                🏥 Informations médicales
                            </h3>
                            @can('updateMedical', $eleve)
                            <button onclick="openModal('medical-modal')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                                ✏️ Modifier
                            </button>
                            @endcan
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white p-3 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-700 flex items-center mb-1">
                                    🩸 Groupe sanguin
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-700 flex items-center mb-1">
                                    ⚠️ Allergies
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->allergies ?? 'Aucune' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-700 flex items-center mb-1">
                                    📋 Remarques médicales
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->remarques_medicales ?? 'Aucune' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-700 flex items-center mb-1">
                                    👨‍⚕️ Médecin traitant
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->medecin_traitant ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-700 flex items-center mb-1">
                                    🏥 Numéro assurance
                                </p>
                                <p class="text-gray-900 font-medium">{{ $eleve->numero_assurance ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                    @endcan

                    @cannot('viewMedical', $eleve)
                    <!-- Section médicale restreinte -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-lg border border-gray-200">
                        <div class="text-center">
                            <div class="text-6xl mb-4">🔒</div>
                            <h3 class="text-xl font-bold text-gray-600 mb-2">Informations médicales</h3>
                            <p class="text-gray-500 mb-4">Accès restreint au personnel médical et administrateur</p>
                            <p class="text-sm text-gray-400">Votre rôle actuel : <span class="font-semibold">{{ ucfirst(auth()->user()->role) }}</span></p>
                        </div>
                    </div>
                    @endcannot
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

<!-- Modal modification informations de base -->
<div id="info-base-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-8 relative">
        <button onclick="closeModal('info-base-modal')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-blue-700">✏️ Modifier les informations de base</h2>
        <form method="POST" action="{{ route('eleves.update.basic', $eleve) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="nom" class="block text-gray-700 font-semibold mb-1">Nom</label>
                    <input type="text" name="nom" id="nom" value="{{ $eleve->nom }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="prenom" class="block text-gray-700 font-semibold mb-1">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{{ $eleve->prenom }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="date_naissance" class="block text-gray-700 font-semibold mb-1">Date de naissance</label>
                    <input type="date" name="date_naissance" id="date_naissance" value="{{ $eleve->date_naissance }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="lieu_naissance" class="block text-gray-700 font-semibold mb-1">Lieu de naissance</label>
                    <input type="text" name="lieu_naissance" id="lieu_naissance" value="{{ $eleve->lieu_naissance }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="sexe" class="block text-gray-700 font-semibold mb-1">Sexe</label>
                    <select name="sexe" id="sexe" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner</option>
                        <option value="M" {{ $eleve->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ $eleve->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="numero_matricule" class="block text-gray-700 font-semibold mb-1">Numéro matricule</label>
                    <input type="text" name="numero_matricule" id="numero_matricule" value="{{ $eleve->numero_matricule }}" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="type_etablissement" class="block text-gray-700 font-semibold mb-1">Type d'établissement</label>
                    <select name="type_etablissement" id="type_etablissement" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner</option>
                        <option value="primaire" {{ $eleve->type_etablissement == 'primaire' ? 'selected' : '' }}>Primaire</option>
                        <option value="college" {{ $eleve->type_etablissement == 'college' ? 'selected' : '' }}>Collège</option>
                        <option value="lycee" {{ $eleve->type_etablissement == 'lycee' ? 'selected' : '' }}>Lycée</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="classe_id" class="block text-gray-700 font-semibold mb-1">Classe</label>
                    <select name="classe_id" id="classe_id" class="w-full border rounded px-3 py-2">
                        <option value="">Aucune classe</option>
                        @foreach(App\Models\Classe::all() as $classe)
                            <option value="{{ $classe->id }}" {{ $eleve->classe_id == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('info-base-modal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal modification photo -->
<div id="photo-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8 relative">
        <button onclick="closeModal('photo-modal')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-blue-700">📷 Modifier la photo</h2>
        <form method="POST" action="{{ route('eleves.update.photo', $eleve) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <!-- Aperçu photo actuelle -->
            <div class="text-center mb-6">
                <div class="w-32 h-32 mx-auto mb-4">
                    @if($eleve->photo)
                        <img id="current-photo" src="{{ asset('storage/' . $eleve->photo) }}" alt="Photo actuelle" class="w-32 h-32 rounded-full object-cover border-4 border-gray-300">
                    @else
                        <div id="current-photo" class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center border-4 border-gray-300">
                            <span class="text-4xl font-bold text-gray-600">
                                {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <p class="text-sm text-gray-600">Photo actuelle</p>
            </div>

            <!-- Upload nouvelle photo -->
            <div class="mb-6">
                <label for="photo" class="block text-gray-700 font-semibold mb-2">Nouvelle photo</label>
                <input type="file" name="photo" id="photo" accept="image/*" class="w-full border rounded px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" onchange="previewPhoto(event)">
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
            </div>

            <!-- Aperçu nouvelle photo -->
            <div id="photo-preview" class="text-center mb-6 hidden">
                <img id="preview-image" class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-blue-300">
                <p class="text-sm text-blue-600 mt-2">Aperçu de la nouvelle photo</p>
            </div>

            <!-- Option pour supprimer la photo -->
            @if($eleve->photo)
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remove_photo" value="1" class="mr-2">
                    <span class="text-red-600">Supprimer la photo actuelle</span>
                </label>
            </div>
            @endif

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('photo-modal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal modification informations de contact -->
<div id="contact-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-8 relative">
        <button onclick="closeModal('contact-modal')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-green-700">📞 Modifier les informations de contact</h2>
        <form method="POST" action="{{ route('eleves.update.contact', $eleve) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-1">📧 Email</label>
                    <input type="email" name="email" id="email" value="{{ $eleve->email }}" class="w-full border rounded px-3 py-2" placeholder="exemple@email.com">
                </div>
                <div class="mb-4">
                    <label for="telephone" class="block text-gray-700 font-semibold mb-1">📱 Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" value="{{ $eleve->telephone }}" class="w-full border rounded px-3 py-2" placeholder="+212 6XX-XXXXXX">
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="adresse" class="block text-gray-700 font-semibold mb-1">🏠 Adresse complète</label>
                    <textarea name="adresse" id="adresse" rows="3" class="w-full border rounded px-3 py-2" placeholder="Adresse complète...">{{ $eleve->adresse }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="contact_urgence" class="block text-gray-700 font-semibold mb-1">🆘 Contact d'urgence</label>
                    <input type="tel" name="contact_urgence" id="contact_urgence" value="{{ $eleve->contact_urgence }}" class="w-full border rounded px-3 py-2" placeholder="+212 6XX-XXXXXX">
                </div>
                <div class="mb-4">
                    <label for="nom_tuteur" class="block text-gray-700 font-semibold mb-1">👨‍👩‍👧‍👦 Nom tuteur/parent</label>
                    <input type="text" name="nom_tuteur" id="nom_tuteur" value="{{ $eleve->nom_tuteur }}" class="w-full border rounded px-3 py-2" placeholder="Nom complet du tuteur">
                </div>
                <div class="mb-4">
                    <label for="profession_pere" class="block text-gray-700 font-semibold mb-1">👨‍💼 Profession du père</label>
                    <input type="text" name="profession_pere" id="profession_pere" value="{{ $eleve->profession_pere }}" class="w-full border rounded px-3 py-2" placeholder="Profession du père">
                </div>
                <div class="mb-4">
                    <label for="profession_mere" class="block text-gray-700 font-semibold mb-1">👩‍💼 Profession de la mère</label>
                    <input type="text" name="profession_mere" id="profession_mere" value="{{ $eleve->profession_mere }}" class="w-full border rounded px-3 py-2" placeholder="Profession de la mère">
                </div>
                <div class="mb-4">
                    <label for="nationalite" class="block text-gray-700 font-semibold mb-1">🌍 Nationalité</label>
                    <input type="text" name="nationalite" id="nationalite" value="{{ $eleve->nationalite }}" class="w-full border rounded px-3 py-2" placeholder="Nationalité">
                </div>
                <div class="mb-4">
                    <label for="situation_familiale" class="block text-gray-700 font-semibold mb-1">👪 Situation familiale</label>
                    <select name="situation_familiale" id="situation_familiale" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner</option>
                        <option value="Les deux parents" {{ $eleve->situation_familiale == 'Les deux parents' ? 'selected' : '' }}>Les deux parents</option>
                        <option value="Mère seule" {{ $eleve->situation_familiale == 'Mère seule' ? 'selected' : '' }}>Mère seule</option>
                        <option value="Père seul" {{ $eleve->situation_familiale == 'Père seul' ? 'selected' : '' }}>Père seul</option>
                        <option value="Tuteur" {{ $eleve->situation_familiale == 'Tuteur' ? 'selected' : '' }}>Tuteur</option>
                        <option value="Autre" {{ $eleve->situation_familiale == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nombre_freres_soeurs" class="block text-gray-700 font-semibold mb-1">👫 Nombre de frères et sœurs</label>
                    <input type="number" name="nombre_freres_soeurs" id="nombre_freres_soeurs" value="{{ $eleve->nombre_freres_soeurs }}" class="w-full border rounded px-3 py-2" min="0">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('contact-modal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal modification informations médicales -->
@can('updateMedical', $eleve)
<div id="medical-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-8 relative">
        <button onclick="closeModal('medical-modal')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h2 class="text-xl font-bold mb-6 text-red-700">🏥 Modifier les informations médicales</h2>
        <form method="POST" action="{{ route('eleves.update.medical', $eleve) }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="groupe_sanguin" class="block text-gray-700 font-semibold mb-1">🩸 Groupe sanguin</label>
                    <select name="groupe_sanguin" id="groupe_sanguin" class="w-full border rounded px-3 py-2">
                        <option value="">Sélectionner le groupe sanguin</option>
                        <option value="A+" {{ $eleve->groupe_sanguin == 'A+' ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ $eleve->groupe_sanguin == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ $eleve->groupe_sanguin == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ $eleve->groupe_sanguin == 'B-' ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ $eleve->groupe_sanguin == 'AB+' ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ $eleve->groupe_sanguin == 'AB-' ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ $eleve->groupe_sanguin == 'O+' ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ $eleve->groupe_sanguin == 'O-' ? 'selected' : '' }}>O-</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="numero_assurance" class="block text-gray-700 font-semibold mb-1">🏥 Numéro assurance maladie</label>
                    <input type="text" name="numero_assurance" id="numero_assurance" value="{{ $eleve->numero_assurance }}" class="w-full border rounded px-3 py-2" placeholder="Numéro de l'assurance maladie">
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="allergies" class="block text-gray-700 font-semibold mb-1">⚠️ Allergies connues</label>
                    <textarea name="allergies" id="allergies" rows="3" class="w-full border rounded px-3 py-2" placeholder="Décrire toutes les allergies connues (alimentaires, médicamenteuses, etc.)...">{{ $eleve->allergies }}</textarea>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="remarques_medicales" class="block text-gray-700 font-semibold mb-1">📋 Remarques médicales importantes</label>
                    <textarea name="remarques_medicales" id="remarques_medicales" rows="3" class="w-full border rounded px-3 py-2" placeholder="Maladies chroniques, traitements en cours, handicaps, etc.">{{ $eleve->remarques_medicales }}</textarea>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="medecin_traitant" class="block text-gray-700 font-semibold mb-1">👨‍⚕️ Médecin traitant</label>
                    <input type="text" name="medecin_traitant" id="medecin_traitant" value="{{ $eleve->medecin_traitant }}" class="w-full border rounded px-3 py-2" placeholder="Dr. Nom - Téléphone - Adresse">
                </div>
                <div class="mb-4">
                    <label for="transport_scolaire" class="block text-gray-700 font-semibold mb-1">🚌 Transport scolaire</label>
                    <select name="transport_scolaire" id="transport_scolaire" class="w-full border rounded px-3 py-2">
                        <option value="0" {{ $eleve->transport_scolaire == 0 ? 'selected' : '' }}>Non</option>
                        <option value="1" {{ $eleve->transport_scolaire == 1 ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="bourse_etudes" class="block text-gray-700 font-semibold mb-1">💰 Bourse d'études</label>
                    <select name="bourse_etudes" id="bourse_etudes" class="w-full border rounded px-3 py-2">
                        <option value="0" {{ $eleve->bourse_etudes == 0 ? 'selected' : '' }}>Non</option>
                        <option value="1" {{ $eleve->bourse_etudes == 1 ? 'selected' : '' }}>Oui</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('medical-modal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">💾 Enregistrer</button>
            </div>
        </form>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal('medical-modal')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Annuler</button>
                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endcan

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
    // Fonction pour ouvrir un modal
    function openModal(modalId) {
        console.log('Tentative d\'ouverture du modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            console.log('Modal ouvert:', modalId);
        } else {
            console.error('Modal non trouvé:', modalId);
        }
    }
    
    // Fonction pour fermer un modal
    function closeModal(modalId) {
        console.log('Tentative de fermeture du modal:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            console.log('Modal fermé:', modalId);
        } else {
            console.error('Modal non trouvé:', modalId);
        }
    }
    
    // Fonction pour éditer un parcours scolaire
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
    
    // Fermer les modaux en cliquant à l'extérieur
    document.addEventListener('click', function(event) {
        const modals = ['info-base-modal', 'contact-modal', 'medical-modal', 'add-parcours-modal', 'edit-parcours-modal', 'photo-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    });

    // Fonction pour afficher/masquer les informations détaillées
    function toggleDetailedInfo() {
        console.log('Tentative de toggle des informations détaillées');
        const detailedInfo = document.getElementById('detailed-info');
        const button = document.getElementById('show-more-btn');
        
        if (!detailedInfo) {
            console.error('Élément detailed-info non trouvé');
            return;
        }
        
        if (!button) {
            console.error('Bouton show-more-btn non trouvé');
            return;
        }
        
        console.log('Éléments trouvés, état actuel:', detailedInfo.classList.contains('hidden') ? 'masqué' : 'visible');
        
        if (detailedInfo.classList.contains('hidden')) {
            // Afficher les informations détaillées
            detailedInfo.classList.remove('hidden');
            button.innerHTML = '📋 Masquer les informations détaillées';
            button.classList.remove('bg-orange-500', 'hover:bg-orange-700');
            button.classList.add('bg-red-500', 'hover:bg-red-700');
            console.log('Informations détaillées affichées');
            
            // Animation d'apparition
            detailedInfo.style.opacity = '0';
            detailedInfo.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                detailedInfo.style.transition = 'all 0.5s ease-in-out';
                detailedInfo.style.opacity = '1';
                detailedInfo.style.transform = 'translateY(0)';
            }, 10);
        } else {
            // Masquer les informations détaillées
            detailedInfo.style.transition = 'all 0.3s ease-in-out';
            detailedInfo.style.opacity = '0';
            detailedInfo.style.transform = 'translateY(-20px)';
            console.log('Informations détaillées masquées');
            
            setTimeout(() => {
                detailedInfo.classList.add('hidden');
                button.innerHTML = '📋 Afficher toutes les informations';
                button.classList.remove('bg-red-500', 'hover:bg-red-700');
                button.classList.add('bg-orange-500', 'hover:bg-orange-700');
            }, 300);
        }
    }

    // Fonction pour uploader la photo directement
    function uploadPhoto() {
        const fileInput = document.getElementById('photo-input');
        const form = document.getElementById('photo-form');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            
            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner un fichier image.');
                return;
            }
            
            // Vérifier la taille (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximum : 2MB');
                return;
            }
            
            // Confirmer l'upload
            if (confirm('Voulez-vous vraiment mettre à jour la photo de cet élève ?')) {
                // Afficher un indicateur de chargement
                const button = document.querySelector('button[onclick*="photo-input"]');
                const originalText = button.innerHTML;
                button.innerHTML = '⏳ Upload en cours...';
                button.disabled = true;
                
                // Soumettre le formulaire
                form.submit();
            }
        }
    }

    // Fonction pour prévisualiser la photo
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photo-preview');
                const previewImage = document.getElementById('preview-image');
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
    });
    
    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM chargé - Initialisation des fonctions');
        
        // Test des éléments
        const showMoreBtn = document.getElementById('show-more-btn');
        const detailedInfo = document.getElementById('detailed-info');
        
        if (showMoreBtn) {
            console.log('Bouton "Afficher plus" trouvé');
        } else {
            console.error('Bouton "Afficher plus" non trouvé');
        }
        
        if (detailedInfo) {
            console.log('Section détaillée trouvée');
        } else {
            console.error('Section détaillée non trouvée');
        }
    });
</script>
@endsection