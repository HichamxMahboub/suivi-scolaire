@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec navigation -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ \App\Helpers\NotesHelper::getEmojiForType($typeEtablissement) }} 
                            Ajouter une Note - {{ ucfirst($typeEtablissement) }}
                        </h1>
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Élève :</span> {{ $eleve->nom }} {{ $eleve->prenom }}
                            @if($eleve->classeInfo)
                                <span class="ml-4"><span class="font-medium">Classe :</span> {{ $eleve->classeInfo->nom }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('notes.eleves', ['type_etablissement' => $typeEtablissement, 'classe' => $eleve->classe_id]) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Retour à la classe
                        </a>
                        <a href="{{ route('notes.niveaux', ['type_etablissement' => $typeEtablissement]) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Vue d'ensemble
                        </a>
                    </div>
                </div>

                <!-- Breadcrumb -->
                <nav class="flex mb-6 text-sm">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                            <a href="{{ route('notes.index') }}" class="text-gray-700 hover:text-blue-600">Notes</a>
                        </li>
                        <li>
                            <span class="text-gray-500">/</span>
                            <a href="{{ route('notes.niveaux', ['type_etablissement' => $typeEtablissement]) }}" 
                               class="text-gray-700 hover:text-blue-600">{{ ucfirst($typeEtablissement) }}</a>
                        </li>
                        @if($eleve->classeInfo)
                        <li>
                            <span class="text-gray-500">/</span>
                            <a href="{{ route('notes.eleves', ['type_etablissement' => $typeEtablissement, 'classe' => $eleve->classe_id]) }}" 
                               class="text-gray-700 hover:text-blue-600">{{ $eleve->classeInfo->nom }}</a>
                        </li>
                        @endif
                        <li>
                            <span class="text-gray-500">/</span>
                            <span class="text-gray-500">{{ $eleve->nom }} {{ $eleve->prenom }}</span>
                        </li>
                    </ol>
                </nav>

                <!-- Informations de l'élève -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg mr-4">
                            {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                            <div class="text-sm text-gray-600">
                                <span>Matricule: {{ $eleve->numero_matricule ?? 'N/A' }}</span>
                                @if($eleve->classeInfo)
                                    <span class="ml-4">Classe: {{ $eleve->classeInfo->nom }}</span>
                                @endif
                                <span class="ml-4">Type: {{ ucfirst($typeEtablissement) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de création de note -->
                <form method="POST" action="{{ route('notes.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Champs cachés -->
                    <input type="hidden" name="eleve_id" value="{{ $eleve->id }}">
                    <input type="hidden" name="type_etablissement" value="{{ $typeEtablissement }}">
                    <input type="hidden" name="classe_id" value="{{ $eleve->classe_id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Matière -->
                        <div>
                            <label for="matiere" class="block text-sm font-medium text-gray-700 mb-2">
                                Matière <span class="text-red-500">*</span>
                            </label>
                            <select name="matiere" id="matiere" required 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $nom => $config)
                                    <option value="{{ $nom }}" data-note-sur="{{ $config['note_sur'] }}" data-couleur="{{ $config['couleur'] }}">
                                        {{ $nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semestre avec choix à cocher -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="semestre" value="S1" required
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span>S1</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="semestre" value="S2" required
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    <span>S2</span>
                                </label>
                            </div>
                            @error('semestre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                Note <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="note" id="note" step="0.25" min="0" 
                                       @if($typeEtablissement === 'primaire') max="10" @else max="20" @endif
                                       required class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="0.00">
                                <span class="text-gray-600 font-medium">
                                    sur <span id="note-sur">{{ $typeEtablissement === 'primaire' ? '10' : '20' }}</span>
                                </span>
                            </div>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enseignant -->
                        <div>
                            <label for="enseignant_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Enseignant
                            </label>
                            <select name="enseignant_id" id="enseignant_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionner un enseignant</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                @endforeach
                            </select>
                            @error('enseignant_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire
                        </label>
                        <textarea name="commentaire" id="commentaire" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Commentaire sur l'évaluation (optionnel)">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                        <!-- Note -->
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                Note <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="number" name="note" id="note" step="0.01" min="0" required
                                       placeholder="0.00"
                                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <span class="text-gray-500">sur</span>
                                <input type="number" name="note_sur" id="note_sur" step="0.01" min="1" max="100" required
                                       placeholder="20"
                                       class="w-20 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            @error('note')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Enseignant -->
                        <div>
                            <label for="enseignant_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Enseignant
                            </label>
                            <select name="enseignant_id" id="enseignant_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionner un enseignant</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}">{{ $enseignant->name }}</option>
                                @endforeach
                            </select>
                            @error('enseignant_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div>
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire
                        </label>
                        <textarea name="commentaire" id="commentaire" rows="3" 
                                  placeholder="Commentaire sur l'évaluation (optionnel)"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('notes.eleves', ['type_etablissement' => $typeEtablissement, 'classe' => $eleve->classe_id]) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Enregistrer la note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript simplifié -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation de la note selon le type d'établissement
    const noteInput = document.getElementById('note');
    const maxNote = {{ $typeEtablissement === 'primaire' ? '10' : '20' }};
    
    noteInput.addEventListener('input', function() {
        const note = parseFloat(this.value);
        
        if (note > maxNote) {
            this.setCustomValidity('La note ne peut pas être supérieure à ' + maxNote);
        } else if (note < 0) {
            this.setCustomValidity('La note ne peut pas être négative');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
