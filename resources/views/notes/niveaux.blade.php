@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('notes.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                🏠 Accueil Notes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-500">
                                    @if($typeEtablissement === 'primaire') 🏫 Primaire
                                    @elseif($typeEtablissement === 'college') 🏛️ Collège
                                    @elseif($typeEtablissement === 'lycee') 🎓 Lycée
                                    @endif
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            @if($typeEtablissement === 'primaire') 🏫 Notes - Primaire
                            @elseif($typeEtablissement === 'college') 🏛️ Notes - Collège  
                            @elseif($typeEtablissement === 'lycee') 🎓 Notes - Lycée
                            @endif
                        </h1>
                        <p class="text-gray-600">Sélectionnez une classe pour gérer les notes</p>
                    </div>
                </div>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👥</span>
                            <div>
                                <p class="text-sm text-blue-600">Total élèves</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $stats['total_eleves'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">🏫</span>
                            <div>
                                <p class="text-sm text-green-600">Classes actives</p>
                                <p class="text-2xl font-bold text-green-800">{{ $stats['total_classes'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📝</span>
                            <div>
                                <p class="text-sm text-purple-600">Notes enregistrées</p>
                                <p class="text-2xl font-bold text-purple-800">{{ $stats['total_notes'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des classes -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($classes as $classe)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300 group">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $classe->nom }}</h3>
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <span class="mr-2">👥</span>
                                    <span>{{ $classe->eleves->count() }} élève(s)</span>
                                </div>
                            </div>

                            <!-- Liste des élèves (aperçu) -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Élèves :</h4>
                                <div class="space-y-1 max-h-20 overflow-y-auto">
                                    @foreach($classe->eleves->take(3) as $eleve)
                                        <div class="text-xs text-gray-600">• {{ $eleve->nom }} {{ $eleve->prenom }}</div>
                                    @endforeach
                                    @if($classe->eleves->count() > 3)
                                        <div class="text-xs text-gray-500">... et {{ $classe->eleves->count() - 3 }} autre(s)</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('notes.eleves', ['type_etablissement' => $typeEtablissement, 'classe' => $classe->id]) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                    📝 Gérer notes
                                </a>
                                <a href="{{ route('notes.index', ['type_etablissement' => $typeEtablissement, 'classe_id' => $classe->id]) }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded-lg text-sm transition-colors duration-200">
                                    👁️ Voir
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-6xl mb-4">📚</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune classe trouvée</h3>
                            <p class="text-gray-600">Il n'y a pas encore de classes pour ce type d'établissement.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Actions rapides -->
                <div class="mt-8 text-center">
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('notes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                            ⬅️ Retour à l'accueil
                        </a>
                        <a href="{{ route('notes.create', ['type_etablissement' => $typeEtablissement]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                            ➕ Ajouter une note
                        </a>
                        <a href="{{ route('notes.statistiques', ['type_etablissement' => $typeEtablissement]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg">
                            📊 Statistiques
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
