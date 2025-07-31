@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
            <div>
                        <h1 class="text-3xl font-bold text-brand-700 mb-2 font-sans flex items-center gap-2">
                            <span class="inline-block align-middle">👤</span> Ajouter un élève
                        </h1>
                        <p class="text-gray-600 font-sans">Remplissez les informations de l'élève</p>
            </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                        Retour à la liste
                    </a>
                </div>
                
                <!-- Formulaire -->
                <form method="POST" action="{{ route('eleves.store') }}" class="space-y-8 font-sans" enctype="multipart/form-data">
                        @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Colonne Tuteur -->
                        <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                            <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>👨‍👩‍👧‍👦</span> Tuteur</h3>
                            <!-- Pour chaque champ, corriger le label flottant -->
                            <!-- Exemple pour un champ corrigé -->
                            <div class="mb-4">
                                <label for="nom_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Nom du tuteur *</label>
                                <input type="text" name="nom_parent_1" id="nom_parent_1" value="{{ old('nom_parent_1') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Nom du tuteur" autocomplete="off">
                                @error('nom_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="prenom_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Prénom du tuteur</label>
                                <input type="text" name="prenom_parent_1" id="prenom_parent_1" value="{{ old('prenom_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Prénom du tuteur" autocomplete="off">
                                @error('prenom_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="email_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Email du tuteur</label>
                                <input type="email" name="email_parent_1" id="email_parent_1" value="{{ old('email_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Email du tuteur" autocomplete="off">
                                @error('email_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="telephones_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Téléphone du tuteur</label>
                                <input type="tel" name="telephones_parent_1" id="telephones_parent_1" value="{{ old('telephones_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Téléphone du tuteur" autocomplete="off">
                                @error('telephones_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="adresse_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Adresse du tuteur</label>
                                <input type="text" name="adresse_parent_1" id="adresse_parent_1" value="{{ old('adresse_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Adresse du tuteur" autocomplete="off">
                                @error('adresse_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="lien_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Lien de parenté</label>
                                <input type="text" name="lien_parent_1" id="lien_parent_1" value="{{ old('lien_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Lien de parenté" autocomplete="off">
                                @error('lien_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="profession_parent_1" class="block text-sm font-medium text-gray-700 mb-1">Profession du tuteur</label>
                                <input type="text" name="profession_parent_1" id="profession_parent_1" value="{{ old('profession_parent_1') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-400 focus:border-transparent transition-all" placeholder="Profession du tuteur" autocomplete="off">
                                @error('profession_parent_1')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                                </div>
                        <!-- Colonne Élève -->
                        <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-600 flex flex-col gap-6">
                            <h3 class="text-lg font-semibold mb-2 text-brand-700 flex items-center gap-2"><span>🧑‍🎓</span> Élève</h3>
                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'élève *</label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Nom de l'élève" autocomplete="off">
                                @error('nom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom de l'élève *</label>
                                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Prénom de l'élève" autocomplete="off">
                                @error('prenom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Date de naissance" autocomplete="off">
                                @error('date_naissance')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sexe *</label>
                                <div class="flex items-center gap-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="sexe" value="M" {{ old('sexe') == 'M' ? 'checked' : '' }} required class="form-radio text-brand-600 focus:ring-brand-500">
                                        <span class="ml-2">Masculin</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="sexe" value="F" {{ old('sexe') == 'F' ? 'checked' : '' }} required class="form-radio text-brand-600 focus:ring-brand-500">
                                        <span class="ml-2">Féminin</span>
                                    </label>
                                </div>
                                @error('sexe')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="annee_entree" class="block text-sm font-medium text-gray-700 mb-1">Année d'entrée</label>
                                <input type="number" name="annee_entree" id="annee_entree" value="{{ old('annee_entree') }}" min="2000" max="2030"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Année d'entrée" autocomplete="off">
                                @error('annee_entree')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="numero_matricule" class="block text-sm font-medium text-gray-700 mb-1">Numéro matricule (auto)</label>
                                <input type="text" name="numero_matricule" id="numero_matricule" value="{{ old('numero_matricule', $numero_matricule ?? '') }}" readonly
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 text-gray-500 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all cursor-not-allowed" placeholder=" " aria-readonly="true">
                                @error('numero_matricule')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <!-- Champ niveau scolaire avec optgroup par cycle -->
                            <div class="mb-4">
                                <label for="niveau_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Niveau scolaire *</label>
                                <select name="niveau_scolaire" id="niveau_scolaire" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                    <option value="">Sélectionner un niveau</option>
                                    @php
                                        $groupLabels = [
                                            'primaire' => 'Primaire',
                                            'college' => 'Collège',
                                            'lycee_tronc_commun' => 'Lycée - Tronc Commun',
                                            'lycee_1ere_bac' => 'Lycée - 1ère Bac',
                                            'lycee_2eme_bac' => 'Lycée - 2ème Bac',
                                        ];
                                    @endphp
                                    @foreach(\App\Helpers\NiveauScolaireHelper::getNiveauxParGroupe() as $cycle => $niveaux)
                                        <optgroup label="{{ $groupLabels[$cycle] ?? ucfirst($cycle) }}">
                                            @foreach($niveaux as $code => $nom)
                                                <option value="{{ $code }}" {{ old('niveau_scolaire') == $code ? 'selected' : '' }}>{{ $nom }}</option>
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
                                        <input type="radio" name="redoublant" id="redoublant_oui" value="1" {{ old('redoublant') == '1' ? 'checked' : '' }} class="form-radio text-brand-600 focus:ring-brand-500" required>
                                        <span class="ml-2">Oui</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="redoublant" id="redoublant_non" value="0" {{ old('redoublant') === '0' ? 'checked' : '' }} class="form-radio text-brand-600 focus:ring-brand-500" required>
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
                                        $groupLabels = [
                                            'primaire' => 'Primaire',
                                            'college' => 'Collège',
                                            'lycee' => 'Lycée',
                                        ];
                                        $oldNiveauxRedoubles = old('niveaux_redoubles', []);
                                        $anneesParCycle = [
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
                                    @foreach($anneesParCycle as $cycle => $annees)
                                    <div>
                                            <div class="font-semibold text-brand-700 mb-1">{{ $groupLabels[$cycle] ?? ucfirst($cycle) }}</div>
                                            <div class="flex flex-col gap-1">
                                                @foreach($annees as $code => $nom)
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
                                <input type="number" name="annee_sortie" id="annee_sortie" value="{{ old('annee_sortie') }}" min="2000" max="2100"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Année de sortie" autocomplete="off">
                                @error('annee_sortie')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>
                            <div class="mb-4">
                                <label for="cause_sortie" class="block text-sm font-medium text-gray-700 mb-1">Cause de sortie</label>
                                <input type="text" name="cause_sortie" id="cause_sortie" value="{{ old('cause_sortie') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Cause de sortie" autocomplete="off">
                                @error('cause_sortie')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            <div class="mb-4">
                                <label for="etablissement_actuel" class="block text-sm font-medium text-gray-700 mb-1">Établissement actuel</label>
                                <input type="text" name="etablissement_actuel" id="etablissement_actuel" value="{{ old('etablissement_actuel') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Établissement actuel" autocomplete="off">
                                @error('etablissement_actuel')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="remarques" class="block text-sm font-medium text-gray-700 mb-1">Remarques</label>
                                <textarea name="remarques" id="remarques" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all" placeholder="Remarques sur l'élève">{{ old('remarques') }}</textarea>
                                @error('remarques')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    Photo
                                    <span tabindex="0" class="relative group cursor-pointer">
                                        <svg class="w-4 h-4 text-brand-600 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        <span class="absolute left-1/2 -translate-x-1/2 mt-2 w-56 bg-white border border-gray-300 text-gray-700 text-xs rounded-lg shadow-lg px-3 py-2 opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity z-10">
                                            Formats acceptés : <b>JPEG, PNG</b><br>Poids max : <b>2 Mo</b>
                                        </span>
                                    </span>
                                </label>
                                <input type="file" name="photo" id="photo"
                                    accept="image/jpeg,image/png"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                                @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Annuler">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                            Ajouter un élève
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