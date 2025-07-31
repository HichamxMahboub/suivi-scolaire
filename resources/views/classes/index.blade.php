@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec titre et boutons d'action -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des classes</h1>
                        <p class="text-gray-600">Gérez les classes de l'établissement</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('classes.create') }}" class="bg-[#8BC34A] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            <span class="text-lg mr-1">➕</span> Créer
                        </a>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('classes.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Rechercher par nom de classe..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Rechercher
                            </button>
                            <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tableau des classes -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre d'élèves</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($classes as $classe)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $classe->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $classe->niveau_scolaire ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $classe->eleves_count ?? 0 }} élève(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('classes.show', $classe) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('classes.edit', $classe) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                                        <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
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
                                    Aucune classe trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($classes->hasPages())
                <div class="mt-6">
                    {{ $classes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 