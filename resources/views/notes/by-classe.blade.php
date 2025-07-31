@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="header-brand rounded-2xl shadow-2xl p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-3xl">🎓</span>
                <div>
                    <h2 class="text-2xl font-bold mb-1">Notes de la classe {{ $classe->nom }}</h2>
                    <p class="text-white italic text-sm">Performance collective de la classe</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('notes.create', ['classe_id' => $classe->id]) }}" class="btn-brand-green px-4 py-2 rounded-lg font-semibold">
                    ➕ Nouvelle note
                </a>
                <a href="{{ route('notes.index') }}" class="btn-brand-blue px-4 py-2 rounded-lg font-semibold">
                    ← Retour aux notes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-animated-gradient min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Informations de la classe -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Informations de la classe</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Nom :</span> {{ $classe->nom }}</p>
                            <p><span class="font-medium">Niveau :</span> {{ $classe->niveau }}</p>
                            <p><span class="font-medium">Année scolaire :</span> {{ $classe->annee_scolaire }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Statistiques</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Total des notes :</span> {{ $stats['total_notes'] }}</p>
                            <p><span class="font-medium">Moyenne générale :</span> {{ $stats['moyenne_generale'] }}/20</p>
                            <p><span class="font-medium">Élèves avec notes :</span> {{ $stats['eleves_avec_notes'] }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('classes.show', $classe) }}" class="block text-blue-600 hover:text-blue-800">👁️ Voir la classe</a>
                            <a href="{{ route('classes.edit', $classe) }}" class="block text-yellow-600 hover:text-yellow-800">✏️ Modifier la classe</a>
                            <a href="{{ route('eleves.index', ['classe_id' => $classe->id]) }}" class="block text-green-600 hover:text-green-800">👥 Voir les élèves</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Filtres</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Élève</label>
                        <select name="eleve_id" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Tous les élèves</option>
                            @foreach($eleves as $eleve)
                                <option value="{{ $eleve->id }}" {{ request('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select name="matiere" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere }}" {{ request('matiere') == $matiere ? 'selected' : '' }}>
                                    {{ $matiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type d'évaluation</label>
                        <select name="type_evaluation" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type_evaluation') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-brand-green px-4 py-2 rounded-lg font-semibold w-full">
                            🔍 Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Performance par matière -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Performance par matière</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stats_par_matiere as $matiere => $data)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $matiere }}</h4>
                            <div class="space-y-1">
                                <p class="text-sm"><span class="font-medium">Moyenne :</span> {{ $data['moyenne'] }}/20</p>
                                <p class="text-sm"><span class="font-medium">Notes :</span> {{ $data['nombre_notes'] }}</p>
                                <p class="text-sm"><span class="font-medium">Élèves :</span> {{ $data['nombre_eleves'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Liste des notes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Toutes les notes de la classe</h3>
                </div>
                
                @if($notes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Élève</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coefficient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($notes as $note)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $note->date_evaluation->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $note->eleve->nom }} {{ $note->eleve->prenom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $note->matiere }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($note->type_evaluation) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full 
                                                {{ $note->note >= 16 ? 'bg-green-100 text-green-800' : 
                                                   ($note->note >= 12 ? 'bg-blue-100 text-blue-800' : 
                                                   ($note->note >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ $note->note }}/20
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $note->coefficient }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('notes.show', $note) }}" 
                                                   class="text-blue-600 hover:text-blue-900">👁️</a>
                                                <a href="{{ route('notes.edit', $note) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900">✏️</a>
                                                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $notes->links() }}
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <span class="text-4xl mb-4 block">📝</span>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune note trouvée</h3>
                        <p class="text-gray-500 mb-4">Cette classe n'a pas encore de notes enregistrées.</p>
                        <a href="{{ route('notes.create', ['classe_id' => $classe->id]) }}" class="btn-brand-green px-6 py-3 rounded-lg font-semibold">
                            ➕ Ajouter la première note
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 