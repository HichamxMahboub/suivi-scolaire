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
                            <span class="inline-block align-middle">🏫</span> Ajouter une classe
                        </h1>
                        <p class="text-gray-600 font-sans">Ajoutez une nouvelle classe à l'établissement</p>
                    </div>
                    <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                        Retour à la liste
                    </a>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('classes.store') }}" class="space-y-8 font-sans">
                    @csrf
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                        <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>🏷️</span> Informations de la classe</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la classe *</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Ex: 6ème A, 5ème B, etc.">
                                @error('nom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="niveau_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Niveau scolaire</label>
                                <select name="niveau_scolaire" id="niveau_scolaire" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                    <option value="">Sélectionner un niveau</option>
                                    @foreach($niveaux as $cycle => $niveauxCycle)
                                        <optgroup label="{{ $cycle }}">
                                            @foreach($niveauxCycle as $niveau)
                                        <option value="{{ $niveau }}" {{ old('niveau_scolaire') == $niveau ? 'selected' : '' }}>
                                            {{ $niveau }}
                                        </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('niveau_scolaire')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="capacite" class="block text-sm font-medium text-gray-700 mb-1">Capacité maximale</label>
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" min="1" max="50"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Nombre maximum d'élèves">
                                @error('capacite')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="annee_scolaire" class="block text-sm font-medium text-gray-700 mb-1">Année scolaire</label>
                                <input type="text" name="annee_scolaire" id="annee_scolaire" value="{{ old('annee_scolaire', date('Y') . '-' . (date('Y') + 1)) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Ex: 2024-2025">
                                @error('annee_scolaire')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2 mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Description optionnelle de la classe">{{ old('description') }}</textarea>
                                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Annuler">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                            Ajouter la classe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 