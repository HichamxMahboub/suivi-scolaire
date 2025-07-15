@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Exporter en PDF</h1>
                        <p class="text-gray-600">Générez un rapport PDF des élèves</p>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>

                <!-- Options d'export PDF -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Configuration du rapport</h3>
                    
                    <form method="POST" action="{{ route('eleves.export.pdf') }}" class="space-y-4">
                        @csrf

                        <!-- Type de rapport -->
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Type de rapport</label>
                            <select name="report_type" id="report_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="liste">Liste complète des élèves</option>
                                <option value="par_classe">Rapport par classe</option>
                                <option value="statistiques">Rapport statistiques</option>
                                <option value="medical">Rapport médical</option>
                            </select>
                        </div>

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

                        <!-- Options de mise en page -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options de mise en page</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_photo" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Inclure les photos (si disponibles)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_medical" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Inclure les informations médicales</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_contact" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Inclure les informations de contact</span>
                                </label>
                            </div>
                        </div>

                        <!-- Orientation -->
                        <div>
                            <label for="orientation" class="block text-sm font-medium text-gray-700 mb-2">Orientation du papier</label>
                            <select name="orientation" id="orientation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="portrait">Portrait</option>
                                <option value="landscape">Paysage</option>
                            </select>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex space-x-4">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Générer le PDF
                            </button>
                            <button type="submit" name="preview" value="1" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Aperçu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Types de rapports disponibles -->
                <div class="mt-6 bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2 text-yellow-900">Types de rapports disponibles</h3>
                    <div class="space-y-2 text-yellow-800">
                        <div>
                            <strong>Liste complète :</strong> Tous les élèves avec leurs informations de base
                        </div>
                        <div>
                            <strong>Rapport par classe :</strong> Élèves groupés par classe avec statistiques
                        </div>
                        <div>
                            <strong>Rapport statistiques :</strong> Graphiques et analyses des données
                        </div>
                        <div>
                            <strong>Rapport médical :</strong> Informations médicales et allergies
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 