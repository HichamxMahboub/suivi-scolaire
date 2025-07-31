<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ajouter une Note') }}
            </h2>
            <x-secondary-button onclick="window.location.href='{{ route('notes.index') }}'">
                {{ __('Retour à la liste') }}
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('notes.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Élève -->
                            <div>
                                <label for="eleve_id" class="block text-sm font-medium text-gray-700">
                                    {{ __('Élève') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="eleve_id" id="eleve_id" required 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('eleve_id') border-red-300 @enderror">
                                    <option value="">Sélectionner un élève</option>
                                    @foreach($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                            {{ $eleve->nom }} {{ $eleve->prenom }} 
                                            @if($eleve->classe)
                                                ({{ $eleve->classe->nom }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Matière -->
                            <div>
                                <label for="matiere" class="block text-sm font-medium text-gray-700">
                                    {{ __('Matière') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="matiere" id="matiere" required 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('matiere') border-red-300 @enderror">
                                    <option value="">Sélectionner une matière</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere }}" {{ old('matiere') == $matiere ? 'selected' : '' }}>
                                            {{ $matiere }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('matiere')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type d'évaluation -->
                            <div>
                                <label for="type_evaluation" class="block text-sm font-medium text-gray-700">
                                    {{ __('Type d\'évaluation') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="type_evaluation" id="type_evaluation" required 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('type_evaluation') border-red-300 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($typesEvaluation as $type)
                                        <option value="{{ $type }}" {{ old('type_evaluation') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_evaluation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Note -->
                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700">
                                    {{ __('Note obtenue') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="note" id="note" step="0.01" min="0" required
                                       value="{{ old('note') }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('note') border-red-300 @enderror">
                                @error('note')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Note sur -->
                            <div>
                                <label for="note_sur" class="block text-sm font-medium text-gray-700">
                                    {{ __('Barème (note sur)') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="note_sur" id="note_sur" step="0.01" min="0.01" required
                                       value="{{ old('note_sur', '20') }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('note_sur') border-red-300 @enderror">
                                @error('note_sur')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date d'évaluation -->
                            <div>
                                <label for="date_evaluation" class="block text-sm font-medium text-gray-700">
                                    {{ __('Date d\'évaluation') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date_evaluation" id="date_evaluation" required
                                       value="{{ old('date_evaluation', date('Y-m-d')) }}"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('date_evaluation') border-red-300 @enderror">
                                @error('date_evaluation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semestre -->
                            <div>
                                <label for="semestre" class="block text-sm font-medium text-gray-700">
                                    {{ __('Semestre') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="semestre" id="semestre" required 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('semestre') border-red-300 @enderror">
                                    <option value="">Sélectionner un semestre</option>
                                    <option value="S1" {{ old('semestre') == 'S1' ? 'selected' : '' }}>Semestre 1</option>
                                    <option value="S2" {{ old('semestre') == 'S2' ? 'selected' : '' }}>Semestre 2</option>
                                </select>
                                @error('semestre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Enseignant -->
                            <div>
                                <label for="enseignant_id" class="block text-sm font-medium text-gray-700">
                                    {{ __('Enseignant') }}
                                </label>
                                <select name="enseignant_id" id="enseignant_id" 
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('enseignant_id') border-red-300 @enderror">
                                    <option value="">Sélectionner un enseignant</option>
                                    @foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                            {{ $enseignant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Commentaire -->
                        <div class="mt-6">
                            <label for="commentaire" class="block text-sm font-medium text-gray-700">
                                {{ __('Commentaire') }}
                            </label>
                            <textarea name="commentaire" id="commentaire" rows="3"
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('commentaire') border-red-300 @enderror"
                                      placeholder="Commentaire sur la note (optionnel)">{{ old('commentaire') }}</textarea>
                            @error('commentaire')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aperçu de la note -->
                        <div id="note-preview" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Aperçu de la note :</h4>
                            <div class="text-lg font-semibold" id="preview-text"></div>
                        </div>

                        <!-- Boutons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <x-secondary-button onclick="window.location.href='{{ route('notes.index') }}'">
                                {{ __('Annuler') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('Ajouter la Note') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculer l'aperçu de la note en temps réel
        function updatePreview() {
            const note = document.getElementById('note').value;
            const noteSur = document.getElementById('note_sur').value;
            const preview = document.getElementById('note-preview');
            const previewText = document.getElementById('preview-text');

            if (note && noteSur && noteSur > 0) {
                const noteVingt = (note * 20) / noteSur;
                let mention = '';
                let couleur = '';

                if (noteVingt >= 16) {
                    mention = 'Très Bien';
                    couleur = 'text-green-600';
                } else if (noteVingt >= 14) {
                    mention = 'Bien';
                    couleur = 'text-blue-600';
                } else if (noteVingt >= 12) {
                    mention = 'Assez Bien';
                    couleur = 'text-yellow-600';
                } else if (noteVingt >= 10) {
                    mention = 'Passable';
                    couleur = 'text-orange-600';
                } else {
                    mention = 'Insuffisant';
                    couleur = 'text-red-600';
                }

                previewText.innerHTML = `<span class="${couleur}">${note}/${noteSur} (${noteVingt.toFixed(2)}/20) - ${mention}</span>`;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Écouter les changements sur les champs note et note_sur
        document.getElementById('note').addEventListener('input', updatePreview);
        document.getElementById('note_sur').addEventListener('input', updatePreview);

        // Mise à jour initiale
        updatePreview();
    </script>
</x-app-layout>
