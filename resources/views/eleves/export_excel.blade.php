@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Exporter les élèves</h1>
                        <p class="text-gray-600">Exportez la liste des élèves au format Excel</p>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>

                <!-- Options d'export -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Options d'export</h3>
                    
                    <form method="POST" action="{{ route('eleves.export.excel') }}" class="space-y-4">
                        @csrf

                        <!-- Filtres -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">Filtrer par classe</label>
                                <select name="classe_id" id="classe_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Toutes les classes</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">Filtrer par sexe</label>
                                <select name="sexe" id="sexe" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Tous</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>
                        </div>

                        <!-- Colonnes à inclure -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Colonnes à inclure</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="nom" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Nom</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="prenom" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Prénom</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="numero_matricule" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Numéro matricule</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="date_naissance" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Date de naissance</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="lieu_naissance" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Lieu de naissance</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="sexe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Sexe</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="email" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="telephone" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Téléphone</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="adresse" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Adresse</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="classe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Classe</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="annee_entree" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Année d'entrée</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="groupe_sanguin" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Groupe sanguin</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="allergies" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Allergies</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="columns[]" value="remarques_medicales" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Remarques médicales</span>
                                </label>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex space-x-4">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Exporter en Excel
                            </button>
                            <button type="submit" name="format" value="csv" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Exporter en CSV
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Statistiques d'export -->
                <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2 text-blue-900">Informations</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>• L'export inclura tous les élèves correspondant aux filtres sélectionnés</li>
                        <li>• Seules les colonnes cochées seront incluses dans le fichier</li>
                        <li>• Le fichier sera téléchargé automatiquement</li>
                        <li>• Format Excel : .xlsx (recommandé pour les données complexes)</li>
                        <li>• Format CSV : .csv (compatible avec tous les tableurs)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 