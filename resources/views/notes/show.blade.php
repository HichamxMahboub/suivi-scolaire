@extends('layouts.app')

@section('title', 'Détails de la note')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        <!-- En-tête -->
        <div class="border-b px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-eye text-blue-600 mr-2"></i>
                    Détails de la note
                </h1>
                <div class="flex space-x-2">
                    <a href="{{ route('notes.edit', $note) }}" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i>
                        Modifier
                    </a>
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations de l'élève -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            Informations de l'élève
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nom complet</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $note->eleve->prenom }} {{ $note->eleve->nom }}
                                </p>
                            </div>
                            @if($note->eleve->classe)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Classe</label>
                                <p class="text-lg text-gray-900">{{ $note->eleve->classe->nom }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($note->eleve->date_naissance)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Sexe</label>
                                <p class="text-gray-900">{{ $note->eleve->sexe }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Détails de l'évaluation -->
                    <div class="bg-white border rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                            Détails de l'évaluation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Matière</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $note->matiere }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Type d'évaluation</label>
                                <p class="text-gray-900">{{ $note->type_evaluation }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date d'évaluation</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($note->date_evaluation)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Semestre</label>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $note->semestre }}
                                    </span>
                                </p>
                            </div>
                            @if($note->enseignant)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Enseignant</label>
                                <p class="text-gray-900">{{ $note->enseignant->name }}</p>
                            </div>
                            @endif
                        </div>

                        @if($note->commentaire)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-500">Commentaire</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <p class="text-gray-900">{{ $note->commentaire }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Carte de la note -->
                <div class="lg:col-span-1">
                    <div class="bg-white border rounded-lg p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Note obtenue
                        </h2>

                        <!-- Note principale -->
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-gray-900 mb-2">
                                {{ $note->note }}/{{ $note->note_sur }}
                            </div>
                            <div class="text-2xl font-semibold text-gray-600 mb-3">
                                {{ $note->note_vingt }}/20
                            </div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $note->couleur }}">
                                {{ $note->mention }}
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progression</span>
                                <span>{{ number_format(($note->note / $note->note_sur) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300 
                                    @if($note->note_vingt >= 16) bg-green-500
                                    @elseif($note->note_vingt >= 14) bg-blue-500
                                    @elseif($note->note_vingt >= 12) bg-yellow-500
                                    @elseif($note->note_vingt >= 10) bg-orange-500
                                    @else bg-red-500
                                    @endif"
                                    style="width: {{ min(($note->note / $note->note_sur) * 100, 100) }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques rapides -->
                        <div class="border-t pt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Contexte</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Coefficient :</span>
                                    <span class="font-medium">
                                        @if($note->type_evaluation === 'Examen') 3
                                        @elseif($note->type_evaluation === 'Composition') 2
                                        @else 1
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Créée le :</span>
                                    <span class="font-medium">{{ $note->created_at->format('d/m/Y') }}</span>
                                </div>
                                @if($note->updated_at != $note->created_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Modifiée le :</span>
                                    <span class="font-medium">{{ $note->updated_at->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('notes.edit', $note) }}" class="btn btn-warning w-full">
                                    <i class="fas fa-edit mr-1"></i>
                                    Modifier cette note
                                </a>
                                <button onclick="confirmDelete()" class="btn btn-danger w-full">
                                    <i class="fas fa-trash mr-1"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
        </div>
        <p class="text-gray-600 mb-6">
            Êtes-vous sûr de vouloir supprimer cette note ? Cette action est irréversible.
        </p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="btn btn-secondary">
                Annuler
            </button>
            <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
