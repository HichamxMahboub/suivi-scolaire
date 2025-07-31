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
                            <span class="inline-block align-middle">🏫</span> Modifier la classe
                        </h1>
                        <p class="text-gray-600 font-sans">Modifiez les informations de {{ $classe->nom }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('classes.show', $classe) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Voir la classe">
                            Voir la classe
                        </a>
                        <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                            Retour à la liste
                        </a>
                    </div>
                </div>
                <!-- Formulaire -->
                <form method="POST" action="{{ route('classes.update', $classe) }}" class="space-y-8 font-sans">
                    @csrf
                    @method('PUT')
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                        <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>🏷️</span> Informations de la classe</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la classe *</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom', $classe->nom) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Ex: 6ème A, 5ème B, etc.">
                                @error('nom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="niveau_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Niveau scolaire</label>
                                <select name="niveau_scolaire" id="niveau_scolaire" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                    <option value="">Sélectionner un niveau</option>
                                    <option value="CP1" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CP1' ? 'selected' : '' }}>CP1</option>
                                    <option value="CP2" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CP2' ? 'selected' : '' }}>CP2</option>
                                    <option value="CE1" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CE1' ? 'selected' : '' }}>CE1</option>
                                    <option value="CE2" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CE2' ? 'selected' : '' }}>CE2</option>
                                    <option value="CM1" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CM1' ? 'selected' : '' }}>CM1</option>
                                    <option value="CM2" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'CM2' ? 'selected' : '' }}>CM2</option>
                                    <option value="6EME" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '6EME' ? 'selected' : '' }}>6ème</option>
                                    <option value="5EME" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '5EME' ? 'selected' : '' }}>5ème</option>
                                    <option value="4EME" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '4EME' ? 'selected' : '' }}>4ème</option>
                                    <option value="3EME" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '3EME' ? 'selected' : '' }}>3ème</option>
                                    <option value="2NDE" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '2NDE' ? 'selected' : '' }}>2nde</option>
                                    <option value="1ERE" {{ old('niveau_scolaire', $classe->niveau_scolaire) == '1ERE' ? 'selected' : '' }}>1ère</option>
                                    <option value="TLE" {{ old('niveau_scolaire', $classe->niveau_scolaire) == 'TLE' ? 'selected' : '' }}>Terminale</option>
                                </select>
                                @error('niveau_scolaire')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="capacite" class="block text-sm font-medium text-gray-700 mb-1">Capacité maximale</label>
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $classe->capacite) }}" min="1" max="50"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Nombre maximum d'élèves">
                                @error('capacite')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="annee_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Année scolaire</label>
                                <input type="text" name="annee_scolaire" id="annee_scolaire" value="{{ old('annee_scolaire', $classe->annee_scolaire) }}"
                                    placeholder="ex: 2023-2024"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('annee_scolaire')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                        <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>📝</span> Informations supplémentaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="mb-4">
                                <label for="enseignant_id" class="block text-sm font-medium text-gray-700 mb-1">Enseignant principal</label>
                                <select name="enseignant_id" id="enseignant_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                    <option value="">Sélectionner un enseignant</option>
                                    @foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" {{ old('enseignant_id', $classe->enseignant_id) == $enseignant->id ? 'selected' : '' }}>
                                            {{ $enseignant->nom }} {{ $enseignant->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="salle" class="block text-sm font-medium text-gray-700 mb-1">Salle de classe</label>
                                <input type="text" name="salle" id="salle" value="{{ old('salle', $classe->salle) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('salle')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2 mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">{{ old('description', $classe->description) }}</textarea>
                                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                        <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>✅</span> Statut</h3>
                        <div class="flex items-center">
                            <input type="checkbox" name="active" id="active" value="1" {{ old('active', $classe->active) ? 'checked' : '' }}
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded">
                            <label for="active" class="ml-2 block text-sm text-gray-700">
                                Classe active
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Une classe inactive ne peut pas recevoir de nouveaux élèves</p>
                    </div>
                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('classes.show', $classe) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Voir la classe">
                            Voir la classe
                        </a>
                        <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Annuler">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 