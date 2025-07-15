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
                            <span class="inline-block align-middle">🧑‍💼</span> Ajouter un encadrant
                        </h1>
                        <p class="text-gray-600 font-sans">Remplissez les informations de l'encadrant</p>
                    </div>
                    <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                        Retour à la liste
                    </a>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('encadrants.store') }}" class="space-y-8 font-sans" enctype="multipart/form-data">
                    @csrf
                    <!-- Informations personnelles -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                        <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>🧑‍💼</span> Informations personnelles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="mb-4">
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('nom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('prenom')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="matricule" class="block text-sm font-medium text-gray-700 mb-1">Matricule *</label>
                                <input type="text" name="matricule" id="matricule" value="{{ old('matricule') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('matricule')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('telephone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('adresse')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo (optionnelle)</label>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                                @error('photo')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="mb-4 md:col-span-2">
                                <label for="remarque" class="block text-sm font-medium text-gray-700 mb-1">Remarque (optionnelle)</label>
                                <textarea name="remarque" id="remarque" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all"
                                    placeholder="Remarque sur l'encadrant">{{ old('remarque') }}</textarea>
                                @error('remarque')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('encadrants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Annuler">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                            Ajouter l'encadrant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 