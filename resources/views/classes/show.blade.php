@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $classe->nom }}</h1>
                        <p class="text-gray-600">Détails de la classe</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('classes.edit', $classe) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Informations de base -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations de base</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Niveau scolaire</p>
                                <p class="text-gray-900">{{ $classe->niveau_scolaire ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Année scolaire</p>
                                <p class="text-gray-900">{{ $classe->annee_scolaire ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Capacité maximale</p>
                                <p class="text-gray-900">{{ $classe->capacite ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Salle de classe</p>
                                <p class="text-gray-900">{{ $classe->salle ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Statut</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classe->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $classe->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Enseignant principal</p>
                                <p class="text-gray-900">
                                    @if($classe->enseignant)
                                        {{ $classe->enseignant->nom }} {{ $classe->enseignant->prenom }}
                                    @else
                                        Non assigné
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Statistiques</h3>
                        <div class="space-y-3">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $classe->eleves->count() }}</p>
                                <p class="text-sm text-gray-600">Élèves inscrits</p>
                            </div>
                            @if($classe->capacite)
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $classe->capacite - $classe->eleves->count() }}</p>
                                <p class="text-sm text-gray-600">Places disponibles</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($classe->description)
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Description</h3>
                    <p class="text-gray-700">{{ $classe->description }}</p>
                </div>
                @endif

                <!-- Liste des élèves -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Élèves de la classe</h3>
                        <a href="{{ route('eleves.create', ['classe_id' => $classe->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter un élève
                        </a>
                    </div>

                    @if($classe->eleves->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($classe->eleves as $eleve)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $eleve->nom }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $eleve->prenom }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $eleve->numero_matricule ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('eleves.show', $eleve) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                            <a href="{{ route('eleves.edit', $eleve) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Aucun élève inscrit dans cette classe</p>
                            <a href="{{ route('eleves.create', ['classe_id' => $classe->id]) }}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ajouter le premier élève
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 