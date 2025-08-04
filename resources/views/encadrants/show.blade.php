@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $encadrant->prenom }} {{ $encadrant->nom }}</h1>
                        <p class="text-gray-600">Gestion de l'encadrant</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('encadrants.edit', $encadrant) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            ✏️ Modifier
                        </a>
                        <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Messages de succès/erreur -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Photo/Avatar -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-lg text-center border">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                            <span class="text-4xl font-bold text-white">
                                {{ strtoupper(substr($encadrant->prenom, 0, 1) . substr($encadrant->nom, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $encadrant->prenom }} {{ $encadrant->nom }}</h3>
                        <p class="text-blue-600 font-medium">{{ $encadrant->matricule }}</p>
                    </div>

                    <!-- Coordonnées -->
                    <div class="bg-gray-50 p-6 rounded-lg border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">📞 Coordonnées</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-700 w-24">📧 Email:</span>
                                <span class="text-gray-900">{{ $encadrant->email ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-700 w-24">📱 Tél:</span>
                                <span class="text-gray-900">{{ $encadrant->telephone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-start">
                                <span class="font-medium text-gray-700 w-24">📍 Adresse:</span>
                                <span class="text-gray-900">{{ $encadrant->adresse ?? 'Non renseignée' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-lg border">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">📊 Statistiques</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">👥 Élèves encadrés:</span>
                                <span class="text-3xl font-bold text-green-600">{{ $encadrant->eleves->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">🏫 Classes concernées:</span>
                                <span class="text-xl font-semibold text-blue-600">
                                    {{ $encadrant->eleves->whereNotNull('classe_id')->pluck('classeInfo.nom')->unique()->count() }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">📅 Depuis:</span>
                                <span class="text-sm text-gray-600">{{ $encadrant->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remarques -->
                @if($encadrant->remarque)
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-amber-800">💬 Remarques</h4>
                            <p class="text-amber-700 mt-2">{{ $encadrant->remarque }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section de gestion des élèves -->
                <div class="bg-white border rounded-lg shadow-sm">
                    <!-- Header de la section -->
                    <div class="bg-gray-50 px-6 py-4 border-b flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            👥 Gestion des élèves encadrés 
                            <span class="ml-3 bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                {{ $encadrant->eleves->count() }} élève(s)
                            </span>
                        </h2>
                        
                        <!-- Bouton pour ajouter un élève -->
                        <button onclick="toggleAddStudent()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center">
                            ➕ Ajouter un élève
                        </button>
                    </div>

                    <!-- Formulaire d'ajout d'élève (masqué par défaut) -->
                    <div id="addStudentForm" class="hidden bg-green-50 border-b px-6 py-4">
                        <h3 class="text-lg font-semibold mb-3 text-green-800">Ajouter un élève à cet encadrant</h3>
                        <form action="{{ route('encadrants.add-student', $encadrant) }}" method="POST" class="flex items-end gap-4">
                            @csrf
                            <div class="flex-1">
                                <label for="eleve_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner un élève</label>
                                <select name="eleve_id" id="eleve_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">-- Choisir un élève --</option>
                                    @foreach($elevesDisponibles as $eleve)
                                        <option value="{{ $eleve->id }}">
                                            {{ $eleve->nom }} {{ $eleve->prenom }} 
                                            @if($eleve->classeInfo)
                                                ({{ $eleve->classeInfo->nom }})
                                            @endif
                                            - {{ $eleve->numero_matricule_type_ecole }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition-colors">
                                    ✅ Ajouter
                                </button>
                                <button type="button" onclick="toggleAddStudent()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors">
                                    ❌ Annuler
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des élèves encadrés -->
                    <div class="p-6">
                        @if($encadrant->eleves->count() > 0)
                            <!-- Répartition par classe -->
                            @php
                                $elevesParClasse = $encadrant->eleves->groupBy(function($eleve) {
                                    return $eleve->classeInfo ? $eleve->classeInfo->nom : 'Sans classe';
                                });
                            @endphp
                            
                            <div class="mb-6">
                                <h4 class="font-semibold mb-3 text-gray-900">📋 Répartition par classe:</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($elevesParClasse as $classe => $eleves)
                                        <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg text-center">
                                            <div class="font-bold text-blue-800">{{ $classe }}</div>
                                            <div class="text-2xl font-bold text-blue-600">{{ $eleves->count() }}</div>
                                            <div class="text-sm text-blue-600">élève(s)</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tableau des élèves -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">Nom</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">Prénom</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">Classe</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">Matricule</th>
                                            <th class="px-4 py-3 text-left font-semibold text-gray-700 border-b">Sexe</th>
                                            <th class="px-4 py-3 text-center font-semibold text-gray-700 border-b">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($encadrant->eleves as $eleve)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 border-b font-medium">{{ $eleve->nom }}</td>
                                                <td class="px-4 py-3 border-b">{{ $eleve->prenom }}</td>
                                                <td class="px-4 py-3 border-b">
                                                    @if($eleve->classeInfo)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $eleve->classeInfo->nom }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">Non assigné</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 border-b text-sm">{{ $eleve->numero_matricule_type_ecole }}</td>
                                                <td class="px-4 py-3 border-b">
                                                    @if($eleve->sexe == 'M')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            👦 Masculin
                                                        </span>
                                                    @elseif($eleve->sexe == 'F')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                            👧 Féminin
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 border-b text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        <!-- Bouton Voir -->
                                                        <a href="{{ route('eleves.show', $eleve) }}" 
                                                           class="text-blue-600 hover:text-blue-800 font-medium text-sm bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded transition-colors"
                                                           title="Voir la fiche de l'élève">
                                                            👁️ Voir
                                                        </a>
                                                        
                                                        <!-- Bouton Retirer -->
                                                        <form action="{{ route('encadrants.remove-student', $encadrant) }}" method="POST" class="inline" 
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir retirer {{ $eleve->prenom }} {{ $eleve->nom }} de cet encadrant ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-800 font-medium text-sm bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition-colors"
                                                                    title="Retirer l'élève de cet encadrant">
                                                                🗑️ Retirer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">👥</div>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun élève encadré</h3>
                                <p class="text-gray-600 mb-4">Cet encadrant n'a pas encore d'élèves assignés.</p>
                                <button onclick="toggleAddStudent()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                    ➕ Ajouter le premier élève
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions supplémentaires -->
                <div class="mt-8 flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <div>
                        <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            ← Retour à la liste
                        </a>
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('encadrants.edit', $encadrant) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            ✏️ Modifier les infos
                        </a>
                        <form method="POST" action="{{ route('encadrants.destroy', $encadrant) }}" class="inline" 
                              onsubmit="return confirm('⚠️ Attention ! Supprimer cet encadrant supprimera aussi tous les liens avec les élèves. Êtes-vous sûr ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                                🗑️ Supprimer l'encadrant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAddStudent() {
    const form = document.getElementById('addStudentForm');
    form.classList.toggle('hidden');
    
    // Focus sur le select si on ouvre le formulaire
    if (!form.classList.contains('hidden')) {
        document.getElementById('eleve_id').focus();
    }
}

// Fermer le formulaire avec Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('addStudentForm').classList.add('hidden');
    }
});
</script>
@endsection
