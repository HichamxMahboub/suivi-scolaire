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
                                <a href="{{ route('notes.niveaux', $typeEtablissement) }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                    @if($typeEtablissement === 'primaire') 🏫 Primaire
                                    @elseif($typeEtablissement === 'college') 🏛️ Collège
                                    @elseif($typeEtablissement === 'lycee') 🎓 Lycée
                                    @endif
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-500">{{ $classe->nom }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            📝 Notes - {{ $classe->nom }}
                        </h1>
                        <p class="text-gray-600">Sélectionnez un élève pour gérer ses notes</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('notes.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                            ➕ Nouvelle note
                        </a>
                    </div>
                </div>

                <!-- Information sur la classe -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $eleves->count() }}</div>
                            <div class="text-sm text-gray-600">Élèves</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ count($matieres) }}</div>
                            <div class="text-sm text-gray-600">Matières</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                @if($typeEtablissement === 'primaire') 🏫
                                @elseif($typeEtablissement === 'college') 🏛️
                                @elseif($typeEtablissement === 'lycee') 🎓
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">{{ ucfirst($typeEtablissement) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Liste des élèves -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($eleves as $eleve)
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-bold">
                                            {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                                    <p class="text-sm text-gray-600">{{ $eleve->numero_matricule ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Informations élève -->
                            <div class="mb-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="mr-2">📅</span>
                                    <span>{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}</span>
                                </div>
                                @if($eleve->encadrant)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">👨‍🏫</span>
                                        <span>{{ $eleve->encadrant->prenom }} {{ $eleve->encadrant->nom }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('notes.create.eleve', ['eleve_id' => $eleve->id, 'type_etablissement' => $typeEtablissement]) }}" 
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                    ➕ Ajouter note
                                </a>
                                <a href="{{ route('notes.index', ['eleve_id' => $eleve->id, 'type_etablissement' => $typeEtablissement]) }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded-lg text-sm transition-colors duration-200">
                                    👁️ Voir notes
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-6xl mb-4">👨‍🎓</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun élève trouvé</h3>
                            <p class="text-gray-600">Il n'y a pas d'élèves dans cette classe.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Matières disponibles -->
                @if(count($matieres) > 0)
                    <div class="mt-8 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Matières enseignées</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($matieres as $nomMatiere => $config)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    📚 {{ $nomMatiere }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="mt-8 text-center">
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('notes.niveaux', $typeEtablissement) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                            ⬅️ Retour aux classes
                        </a>
                        <a href="{{ route('notes.index', ['classe_id' => $classe->id, 'type_etablissement' => $typeEtablissement]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            📋 Toutes les notes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
