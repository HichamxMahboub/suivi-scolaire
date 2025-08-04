@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Statistiques des élèves</h1>
                        <p class="text-gray-600">Vue d'ensemble des données des élèves</p>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>

                <!-- Statistiques générales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👥</span>
                            <div>
                                <p class="text-sm text-gray-600">Total élèves</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👨</span>
                            <div>
                                <p class="text-sm text-gray-600">Garçons</p>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['garcons'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-pink-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👩</span>
                            <div>
                                <p class="text-sm text-gray-600">Filles</p>
                                <p class="text-2xl font-bold text-pink-600">{{ $stats['filles'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">🎓</span>
                            <div>
                                <p class="text-sm text-gray-600">Classes</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $stats['classes'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par type d'établissement -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Répartition par type d'établissement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if(isset($stats['par_etablissement']))
                            <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-4 rounded-lg border border-blue-300">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">🏫</span>
                                    <div>
                                        <p class="text-sm text-blue-600 font-medium">Primaire</p>
                                        <p class="text-2xl font-bold text-blue-800">{{ $stats['par_etablissement']['Primaire'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-100 to-green-200 p-4 rounded-lg border border-green-300">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">🏛️</span>
                                    <div>
                                        <p class="text-sm text-green-600 font-medium">Collège</p>
                                        <p class="text-2xl font-bold text-green-800">{{ $stats['par_etablissement']['Collège'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-100 to-purple-200 p-4 rounded-lg border border-purple-300">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">🎓</span>
                                    <div>
                                        <p class="text-sm text-purple-600 font-medium">Lycée</p>
                                        <p class="text-2xl font-bold text-purple-800">{{ $stats['par_etablissement']['Lycée'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-gray-100 to-gray-200 p-4 rounded-lg border border-gray-300">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">❓</span>
                                    <div>
                                        <p class="text-sm text-gray-600 font-medium">Non défini</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $stats['par_etablissement']['Non défini'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Répartition par classe -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Répartition par classe</h3>
                        @if(isset($stats['par_classe']) && count($stats['par_classe']) > 0)
                            <div class="space-y-3">
                                @foreach($stats['par_classe'] as $classe => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ $classe }}</span>
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-medium">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Aucune donnée disponible</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Répartition par âge</h3>
                        @if(isset($stats['par_age']) && count($stats['par_age']) > 0)
                            <div class="space-y-3">
                                @foreach($stats['par_age'] as $age => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ $age }} ans</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Aucune donnée disponible</p>
                        @endif
                    </div>
                </div>

                <!-- Graphiques ou tableaux supplémentaires -->
                @if(isset($stats['niveaux']) && count($stats['niveaux']) > 0)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Répartition par niveau</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($stats['niveaux'] as $niveau => $count)
                        <div class="bg-white p-3 rounded border">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">{{ $niveau }}</p>
                                <p class="text-xl font-bold text-gray-900">{{ $count }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 