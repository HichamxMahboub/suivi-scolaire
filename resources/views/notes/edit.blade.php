@extends('layouts.app')

@section('title', 'Modifier la note')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        <!-- En-tête -->
        <div class="border-b px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Modifier la note
                </h1>
                <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="p-6">
            <form action="{{ route('notes.update', $note) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Élève -->
                    <div>
                        <label for="eleve_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Élève <span class="text-red-500">*</span>
                        </label>
                        <select name="eleve_id" id="eleve_id" class="form-select" required>
                            <option value="">Sélectionner un élève</option>
                            @foreach($eleves as $eleve)
                                <option value="{{ $eleve->id }}" {{ old('eleve_id', $note->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                    {{ $eleve->prenom }} {{ $eleve->nom }}
                                    @if($eleve->classe)
                                        - {{ $eleve->classe->nom }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('eleve_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Matière -->
                    <div>
                        <label for="matiere" class="block text-sm font-medium text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select name="matiere" id="matiere" class="form-select" required>
                            <option value="">Sélectionner une matière</option>
                            <option value="Mathématiques" {{ old('matiere', $note->matiere) == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                            <option value="Français" {{ old('matiere', $note->matiere) == 'Français' ? 'selected' : '' }}>Français</option>
                            <option value="Arabe" {{ old('matiere', $note->matiere) == 'Arabe' ? 'selected' : '' }}>Arabe</option>
                            <option value="Anglais" {{ old('matiere', $note->matiere) == 'Anglais' ? 'selected' : '' }}>Anglais</option>
                            <option value="Sciences Physiques" {{ old('matiere', $note->matiere) == 'Sciences Physiques' ? 'selected' : '' }}>Sciences Physiques</option>
                            <option value="Sciences de la Vie et de la Terre" {{ old('matiere', $note->matiere) == 'Sciences de la Vie et de la Terre' ? 'selected' : '' }}>Sciences de la Vie et de la Terre</option>
                            <option value="Histoire-Géographie" {{ old('matiere', $note->matiere) == 'Histoire-Géographie' ? 'selected' : '' }}>Histoire-Géographie</option>
                            <option value="Éducation Islamique" {{ old('matiere', $note->matiere) == 'Éducation Islamique' ? 'selected' : '' }}>Éducation Islamique</option>
                        </select>
                        @error('matiere')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type d'évaluation -->
                    <div>
                        <label for="type_evaluation" class="block text-sm font-medium text-gray-700 mb-2">
                            Type d'évaluation <span class="text-red-500">*</span>
                        </label>
                        <select name="type_evaluation" id="type_evaluation" class="form-select" required>
                            <option value="">Sélectionner un type</option>
                            <option value="Contrôle Continu" {{ old('type_evaluation', $note->type_evaluation) == 'Contrôle Continu' ? 'selected' : '' }}>Contrôle Continu</option>
                            <option value="Devoir Surveillé" {{ old('type_evaluation', $note->type_evaluation) == 'Devoir Surveillé' ? 'selected' : '' }}>Devoir Surveillé</option>
                            <option value="Composition" {{ old('type_evaluation', $note->type_evaluation) == 'Composition' ? 'selected' : '' }}>Composition</option>
                            <option value="Examen" {{ old('type_evaluation', $note->type_evaluation) == 'Examen' ? 'selected' : '' }}>Examen</option>
                            <option value="Oral" {{ old('type_evaluation', $note->type_evaluation) == 'Oral' ? 'selected' : '' }}>Oral</option>
                        </select>
                        @error('type_evaluation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Note -->
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                            Note <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="number" name="note" id="note" step="0.01" min="0" max="20" 
                                   value="{{ old('note', $note->note) }}" class="form-input flex-1" required
                                   onchange="updatePreview()">
                            <span class="text-gray-500">/</span>
                            <input type="number" name="note_sur" id="note_sur" min="1" max="100" 
                                   value="{{ old('note_sur', $note->note_sur) }}" class="form-input w-20" required
                                   onchange="updatePreview()">
                        </div>
                        @error('note')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('note_sur')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date d'évaluation -->
                    <div>
                        <label for="date_evaluation" class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'évaluation <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_evaluation" id="date_evaluation" 
                               value="{{ old('date_evaluation', $note->date_evaluation) }}" class="form-input" required>
                        @error('date_evaluation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semestre -->
                    <div>
                        <label for="semestre" class="block text-sm font-medium text-gray-700 mb-2">
                            Semestre <span class="text-red-500">*</span>
                        </label>
                        <select name="semestre" id="semestre" class="form-select" required>
                            <option value="">Sélectionner un semestre</option>
                            <option value="S1" {{ old('semestre', $note->semestre) == 'S1' ? 'selected' : '' }}>Semestre 1</option>
                            <option value="S2" {{ old('semestre', $note->semestre) == 'S2' ? 'selected' : '' }}>Semestre 2</option>
                        </select>
                        @error('semestre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Commentaire -->
                <div>
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3" class="form-textarea" 
                              placeholder="Commentaire optionnel sur la performance...">{{ old('commentaire', $note->commentaire) }}</textarea>
                    @error('commentaire')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aperçu de la note -->
                <div id="notePreview" class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Aperçu de la note</h3>
                    <div class="flex items-center space-x-4">
                        <div class="text-lg font-bold" id="previewNote">{{ $note->note_vingt }}/20</div>
                        <div class="px-3 py-1 rounded-full text-sm font-medium" id="previewMention">
                            {{ $note->mention }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Modifier la note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const note = parseFloat(document.getElementById('note').value) || 0;
    const noteSur = parseFloat(document.getElementById('note_sur').value) || 20;
    
    // Calculer la note sur 20
    const noteSur20 = (note / noteSur * 20).toFixed(2);
    
    // Déterminer la mention et la couleur
    let mention = '';
    let couleur = '';
    
    if (noteSur20 >= 16) {
        mention = 'Très Bien';
        couleur = 'bg-green-100 text-green-800';
    } else if (noteSur20 >= 14) {
        mention = 'Bien';
        couleur = 'bg-blue-100 text-blue-800';
    } else if (noteSur20 >= 12) {
        mention = 'Assez Bien';
        couleur = 'bg-yellow-100 text-yellow-800';
    } else if (noteSur20 >= 10) {
        mention = 'Passable';
        couleur = 'bg-orange-100 text-orange-800';
    } else {
        mention = 'Insuffisant';
        couleur = 'bg-red-100 text-red-800';
    }
    
    // Mettre à jour l'aperçu
    document.getElementById('previewNote').textContent = noteSur20 + '/20';
    const mentionElement = document.getElementById('previewMention');
    mentionElement.textContent = mention;
    mentionElement.className = 'px-3 py-1 rounded-full text-sm font-medium ' + couleur;
}

// Initialiser l'aperçu au chargement
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection
