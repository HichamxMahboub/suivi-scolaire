@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec titre et boutons d'action -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des élèves</h1>
                        <p class="text-gray-600">Gérez les informations des élèves de l'établissement</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('eleves.create') }}" class="bg-[#8BC34A] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            <span class="text-lg mr-1">➕</span> Ajouter
                        </a>
                        <a href="{{ route('eleves.import.form') }}" class="bg-[#2196F3] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            <span class="text-lg mr-1">📁</span> Importer
                        </a>
                        <a href="{{ route('eleves.stats') }}" class="bg-[#FFD600] text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md">
                            <span class="text-lg mr-1">📊</span> Statistiques
                        </a>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('eleves.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Rechercher par nom, prénom, matricule..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-[#2196F3] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                                Rechercher
                            </button>
                            <a href="{{ route('eleves.index') }}" class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Tableau des élèves -->
                    <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($eleves as $eleve)
                            <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <x-eleve-photo :eleve="$eleve" size="md" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $eleve->nom }} {{ $eleve->prenom }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $eleve->email ?? 'Aucun email' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $eleve->numero_matricule ?? 'N/A' }}
                                        </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $eleve->classeInfo->nom ?? 'Non assigné' }}
                                        </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($eleve->type_etablissement)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($eleve->type_etablissement === 'primaire') bg-blue-100 text-blue-800
                                            @elseif($eleve->type_etablissement === 'college') bg-green-100 text-green-800
                                            @elseif($eleve->type_etablissement === 'lycee') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($eleve->type_etablissement === 'primaire') 🏫 Primaire
                                            @elseif($eleve->type_etablissement === 'college') 🏛️ Collège
                                            @elseif($eleve->type_etablissement === 'lycee') 🎓 Lycée
                                            @else ❓ {{ ucfirst($eleve->type_etablissement) }} @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            ❓ Non défini
                                        </span>
                                    @endif
                                        </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                        <a href="{{ route('eleves.show', $eleve) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('eleves.edit', $eleve) }}" class="text-green-600 hover:text-green-900">Modifier</a>
                                        <form method="POST" action="{{ route('eleves.destroy', $eleve) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')">
                                                    @csrf
                                                    @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Aucun élève trouvé
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                <!-- Pagination -->
                @if($eleves->hasPages())
                <div class="mt-6">
                        {{ $eleves->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 