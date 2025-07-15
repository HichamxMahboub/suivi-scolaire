@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $encadrant->nom }} {{ $encadrant->prenom }}</h1>
                        <p class="text-gray-600">Détails de l'encadrant</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('encadrants.edit', $encadrant) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                                {{ strtoupper(substr($encadrant->nom, 0, 1) . substr($encadrant->prenom, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $encadrant->nom }} {{ $encadrant->prenom }}</h3>
                        <p class="text-gray-600">{{ $encadrant->specialite ?? 'Enseignant' }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2 {{ $encadrant->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($encadrant->statut ?? 'actif') }}
                        </span>
                    </div>

                    <!-- Informations de base -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations de base</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Matricule</p>
                                <p class="text-gray-900">{{ $encadrant->matricule ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email</p>
                                <p class="text-gray-900">{{ $encadrant->email ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Téléphone</p>
                                <p class="text-gray-900">{{ $encadrant->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Adresse</p>
                                <p class="text-gray-900">{{ $encadrant->adresse ?? 'Non renseignée' }}</p>
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
                                <p class="text-gray-900">{{ $encadrant->email ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Téléphone</p>
                                <p class="text-gray-900">{{ $encadrant->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Adresse</p>
                                <p class="text-gray-900">{{ $encadrant->adresse ?? 'Non renseignée' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations professionnelles</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Date d'embauche</p>
                                <p class="text-gray-900">{{ $encadrant->date_embauche ? \Carbon\Carbon::parse($encadrant->date_embauche)->format('d/m/Y') : 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Numéro de sécurité sociale</p>
                                <p class="text-gray-900">{{ $encadrant->numero_ss ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Salaire</p>
                                <p class="text-gray-900">{{ $encadrant->salaire ? number_format($encadrant->salaire, 2) . ' DH' : 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classes enseignées -->
                @if($encadrant->classes->count() > 0)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Classes enseignées</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($encadrant->classes as $classe)
                        <div class="bg-white p-3 rounded border">
                            <h4 class="font-semibold text-gray-900">{{ $classe->nom }}</h4>
                            <p class="text-sm text-gray-600">{{ $classe->niveau_scolaire ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $classe->eleves->count() }} élève(s)</p>
                            <a href="{{ route('classes.show', $classe) }}" class="text-blue-600 hover:text-blue-900 text-sm">Voir la classe</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Remarques -->
                @if($encadrant->remarque)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Remarque</h3>
                    <p class="text-gray-700">{{ $encadrant->remarque }}</p>
                </div>
                @endif

                <!-- Actions rapides -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Actions rapides</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('classes.create', ['enseignant_id' => $encadrant->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Créer une classe
                        </a>
                        <a href="{{ route('messages.create', ['enseignant_id' => $encadrant->id]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Envoyer un message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 