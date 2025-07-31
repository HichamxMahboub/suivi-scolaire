@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Notes</h1>
                    <p class="text-gray-600">Sélectionnez le type d'établissement pour commencer</p>
                </div>

                <!-- Cartes des types d'établissement -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Primaire -->
                    <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <a href="{{ route('notes.niveaux', 'primaire') }}" 
                           class="block bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="text-6xl mb-4">🏫</div>
                                <h3 class="text-2xl font-bold text-blue-700 mb-2">Primaire</h3>
                                <p class="text-blue-600 mb-4">Évaluations pour l'enseignement primaire</p>
                                <div class="bg-blue-200 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                                    {{ $stats['primaire'] }} élève(s)
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Collège -->
                    <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <a href="{{ route('notes.niveaux', 'college') }}" 
                           class="block bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="text-6xl mb-4">🏛️</div>
                                <h3 class="text-2xl font-bold text-green-700 mb-2">Collège</h3>
                                <p class="text-green-600 mb-4">Évaluations pour l'enseignement collégial</p>
                                <div class="bg-green-200 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                    {{ $stats['college'] }} élève(s)
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Lycée -->
                    <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <a href="{{ route('notes.niveaux', 'lycee') }}" 
                           class="block bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl p-6 hover:border-purple-400 hover:shadow-lg transition-all duration-300">
                            <div class="text-center">
                                <div class="text-6xl mb-4">🎓</div>
                                <h3 class="text-2xl font-bold text-purple-700 mb-2">Lycée</h3>
                                <p class="text-purple-600 mb-4">Évaluations pour l'enseignement secondaire</p>
                                <div class="bg-purple-200 text-purple-800 px-4 py-2 rounded-full text-sm font-medium">
                                    {{ $stats['lycee'] }} élève(s)
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Système de notation par établissement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-blue-200">
                            <h4 class="font-medium text-blue-700 mb-2">🏫 Primaire</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Évaluation continue</li>
                                <li>• Compétences de base</li>
                                <li>• Apprentissages fondamentaux</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-green-200">
                            <h4 class="font-medium text-green-700 mb-2">🏛️ Collège</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Évaluations par matière</li>
                                <li>• Contrôles continus</li>
                                <li>• Examens trimestriels</li>
                            </ul>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-purple-200">
                            <h4 class="font-medium text-purple-700 mb-2">🎓 Lycée</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Évaluations spécialisées</li>
                                <li>• Préparation aux examens</li>
                                <li>• Orientations professionnelles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="mt-8 text-center">
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                            📚 Voir les élèves
                        </a>
                        <a href="{{ route('eleves.stats') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg">
                            📊 Statistiques
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
