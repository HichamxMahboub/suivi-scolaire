@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                                <div>
                        <h1 class="text-3xl font-bold text-brand-700 mb-2 font-sans flex items-center gap-2">
                            <span class="inline-block align-middle">👤</span> Modifier l'élève
                        </h1>
                        <p class="text-gray-600 font-sans">Modifiez les informations de {{ $eleve->nom }} {{ $eleve->prenom }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('eleves.show', $eleve) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Voir l'élève">
                            Voir l'élève
                        </a>
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                            Retour à la liste
                        </a>
                                </div>
                                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('eleves.update', $eleve) }}" class="space-y-8 font-sans" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Colonne 1 -->
                        <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                            <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>👨‍👩‍👧‍👦</span> Informations personnelles</h3>
                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom', $eleve->nom) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Nom de l'élève" autocomplete="off">
                                @error('nom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $eleve->prenom) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Prénom de l'élève" autocomplete="off">
                                @error('prenom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance', $eleve->date_naissance) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Date de naissance" autocomplete="off">
                                @error('date_naissance')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sexe *</label>
                                <div class="flex items-center gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="sexe" value="M" {{ old('sexe', $eleve->sexe) == 'M' ? 'checked' : '' }} required class="form-radio text-brand-600 focus:ring-brand-500">
                                        <span class="ml-2">Masculin</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="sexe" value="F" {{ old('sexe', $eleve->sexe) == 'F' ? 'checked' : '' }} required class="form-radio text-brand-600 focus:ring-brand-500">
                                        <span class="ml-2">Féminin</span>
                                    </label>
                                </div>
                                @error('sexe')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="numero_matricule" class="block text-sm font-medium text-gray-700 mb-1">Numéro matricule</label>
                                <input type="text" name="numero_matricule" id="numero_matricule" value="{{ old('numero_matricule', $eleve->numero_matricule) }}" readonly
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 text-gray-500 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all cursor-not-allowed" aria-readonly="true">
                                @error('numero_matricule')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $eleve->adresse) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Adresse de l'élève" autocomplete="off">
                                @error('adresse')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $eleve->email) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Email de l'élève ou du parent" autocomplete="off">
                                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="telephone_parent" class="block text-sm font-medium text-gray-700 mb-1">Téléphone du parent</label>
                                <input type="text" name="telephone_parent" id="telephone_parent" value="{{ old('telephone_parent', $eleve->telephone_parent) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Téléphone du parent" autocomplete="off">
                                @error('telephone_parent')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="remarques" class="block text-sm font-medium text-gray-700 mb-1">Remarques</label>
                                <textarea name="remarques" id="remarques" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Remarques sur l'élève">{{ old('remarques', $eleve->remarques) }}</textarea>
                                @error('remarques')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="educateur_responsable" class="block text-sm font-medium text-gray-700 mb-1">Éducateur responsable</label>
                                <input type="text" name="educateur_responsable" id="educateur_responsable" value="{{ old('educateur_responsable', $eleve->educateur_responsable) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Nom de l'éducateur" autocomplete="off">
                                @error('educateur_responsable')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all">
                                @if($eleve->photo)
                                    <div class="mt-2"><img src="{{ asset('storage/'.$eleve->photo) }}" alt="Photo de l'élève" class="h-16 rounded shadow"></div>
                                @endif
                                @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <!-- Colonne 2 -->
                        <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-600 flex flex-col gap-6">
                            <h3 class="text-lg font-semibold mb-2 text-brand-700 flex items-center gap-2"><span>🧑‍🎓</span> Informations scolaires</h3>
                            <div class="mb-4">
                                <label for="annee_entree" class="block text-sm font-medium text-gray-700 mb-1">Année d'entrée</label>
                                <input type="number" name="annee_entree" id="annee_entree" value="{{ old('annee_entree', $eleve->annee_entree) }}" min="2000" max="2030"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Année d'entrée" autocomplete="off">
                                @error('annee_entree')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>
                            <div class="mb-4">
                                <label for="niveau_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Niveau scolaire *</label>
                                <select name="niveau_scolaire" id="niveau_scolaire" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                    <option value="">Sélectionner un niveau</option>
                                    @php
                                        $groupLabels = [
                                            'primaire' => 'Primaire',
                                            'college' => 'Collège',
                                            'lycee' => 'Lycée',
                                        ];
                                        $niveauxParCycle = [
                                            'primaire' => [
                                                '1AP' => '1ère année primaire',
                                                '2AP' => '2ème année primaire',
                                                '3AP' => '3ème année primaire',
                                                '4AP' => '4ème année primaire',
                                                '5AP' => '5ème année primaire',
                                                '6AP' => '6ème année primaire',
                                            ],
                                            'college' => [
                                                '1AC' => '1ère année collège',
                                                '2AC' => '2ème année collège',
                                                '3AC' => '3ème année collège',
                                            ],
                                            'lycee' => [
                                                'TC' => 'Tronc commun',
                                                '1BAC' => '1ère Bac',
                                                '2BAC' => '2ème Bac',
                                            ],
                                        ];
                                    @endphp
                                    @foreach($niveauxParCycle as $cycle => $niveaux)
                                        <optgroup label="{{ $groupLabels[$cycle] }}">
                                            @foreach($niveaux as $code => $nom)
                                                <option value="{{ $code }}" {{ old('niveau_scolaire', $eleve->niveau_scolaire) == $code ? 'selected' : '' }}>{{ $nom }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                        </select>
                                @error('niveau_scolaire')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>
                            <!-- Champ redoublant (oui/non) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Redoublant *</label>
                                <div class="flex items-center gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="redoublant" id="redoublant_oui" value="1" {{ old('redoublant', $eleve->redoublant) == '1' ? 'checked' : '' }} class="form-radio text-brand-600 focus:ring-brand-500" required>
                                        <span class="ml-2">Oui</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="redoublant" id="redoublant_non" value="0" {{ old('redoublant', $eleve->redoublant) == '0' ? 'checked' : '' }} class="form-radio text-brand-600 focus:ring-brand-500" required>
                                        <span class="ml-2">Non</span>
                                    </label>
                                </div>
                                @error('redoublant')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <!-- Champ niveaux redoublés (affiché seulement si redoublant = oui) -->
                            <div class="mb-4" id="niveau_redouble_group" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Niveaux redoublés</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @php
                                        $oldNiveauxRedoubles = old('niveaux_redoubles');
                                        if (is_null($oldNiveauxRedoubles)) {
                                            $oldNiveauxRedoubles = json_decode($eleve->niveaux_redoubles ?? '[]', true);
                                        }
                                        if (!is_array($oldNiveauxRedoubles)) {
                                            $oldNiveauxRedoubles = [];
                                        }
                                    @endphp
                                    @foreach($niveauxParCycle as $cycle => $niveaux)
                            <div>
                                            <div class="font-semibold text-brand-700 mb-1">{{ $groupLabels[$cycle] }}</div>
                                            <div class="flex flex-col gap-1">
                                                @foreach($niveaux as $code => $nom)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="niveaux_redoubles[]" value="{{ $code }}" {{ in_array($code, $oldNiveauxRedoubles) ? 'checked' : '' }} class="form-checkbox text-brand-600 focus:ring-brand-500">
                                                        <span class="ml-2">{{ $nom }}</span>
                                                    </label>
                                                @endforeach
                                    </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('niveaux_redoubles')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="annee_sortie" class="block text-sm font-medium text-gray-700 mb-1">Année de sortie</label>
                                <input type="number" name="annee_sortie" id="annee_sortie" value="{{ old('annee_sortie', $eleve->annee_sortie) }}" min="2000" max="2100"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Année de sortie" autocomplete="off">
                                @error('annee_sortie')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="cause_sortie" class="block text-sm font-medium text-gray-700 mb-1">Cause de sortie</label>
                                <input type="text" name="cause_sortie" id="cause_sortie" value="{{ old('cause_sortie', $eleve->cause_sortie) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Cause de sortie" autocomplete="off">
                                @error('cause_sortie')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('eleves.show', $eleve) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Voir l'élève">
                            Voir l'élève
                        </a>
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Annuler">
                                    Annuler
                                </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                            Mettre à jour
                                    </button>
                            </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const redoublantOui = document.getElementById('redoublant_oui');
                            const redoublantNon = document.getElementById('redoublant_non');
                            const niveauRedoubleGroup = document.getElementById('niveau_redouble_group');
                            function toggleNiveauRedouble() {
                                if (redoublantOui && redoublantOui.checked) {
                                    niveauRedoubleGroup.style.display = '';
                                } else {
                                    niveauRedoubleGroup.style.display = 'none';
                                }
                            }
                            if (redoublantOui) redoublantOui.addEventListener('change', toggleNiveauRedouble);
                            if (redoublantNon) redoublantNon.addEventListener('change', toggleNiveauRedouble);
                            toggleNiveauRedouble();
                        });
                    </script>
                    </form>
        </div>
        </div>
        </div>
</div>
@endsection 