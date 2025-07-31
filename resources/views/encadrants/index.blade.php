@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec titre et boutons d'action -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des encadrants</h1>
                        <p class="text-gray-600">Gérez les encadrants de l'établissement</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('encadrants.create') }}" class="bg-[#8BC34A] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            <span class="text-lg mr-1">➕</span> Ajouter
                        </a>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">👨‍🏫</span>
                            <div>
                                <p class="text-sm text-gray-600">Total encadrants</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $totalEncadrants ?? ($encadrants->count() ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('encadrants.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Rechercher par nom, prénom, matricule..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Rechercher
                            </button>
                            <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tableau des encadrants -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Encadrant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($encadrants as $encadrant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ strtoupper(substr($encadrant->nom, 0, 1) . substr($encadrant->prenom, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $encadrant->nom }} {{ $encadrant->prenom }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $encadrant->email ?? 'Aucun email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $encadrant->matricule ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $encadrant->telephone ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('encadrants.show', $encadrant) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('encadrants.edit', $encadrant) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                                        <form method="POST" action="{{ route('encadrants.destroy', $encadrant) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet encadrant ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Aucun encadrant trouvé
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($encadrants->hasPages())
                <div class="mt-6">
                    {{ $encadrants->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 