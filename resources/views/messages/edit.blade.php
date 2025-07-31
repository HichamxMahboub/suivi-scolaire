@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Modifier le message</h1>
                        <p class="text-gray-600">Modifiez le contenu du message</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('messages.show', $message) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Voir le message
                        </a>
                        <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('messages.update', $message) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Informations du message -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations du message</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="sujet" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                                <input type="text" name="sujet" id="sujet" value="{{ old('sujet', $message->sujet) }}" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('sujet')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de message</label>
                                <select name="type" id="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="information" {{ old('type', $message->type) == 'information' ? 'selected' : '' }}>Information</option>
                                    <option value="urgence" {{ old('type', $message->type) == 'urgence' ? 'selected' : '' }}>Urgence</option>
                                    <option value="reminder" {{ old('type', $message->type) == 'reminder' ? 'selected' : '' }}>Rappel</option>
                                    <option value="notification" {{ old('type', $message->type) == 'notification' ? 'selected' : '' }}>Notification</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                                <select name="priorite" id="priorite" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="basse" {{ old('priorite', $message->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                                    <option value="normale" {{ old('priorite', $message->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                    <option value="haute" {{ old('priorite', $message->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                                    <option value="urgente" {{ old('priorite', $message->priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                </select>
                                @error('priorite')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <select name="statut" id="statut" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="brouillon" {{ old('statut', $message->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="envoye" {{ old('statut', $message->statut) == 'envoye' ? 'selected' : '' }}>Envoyé</option>
                                    <option value="lu" {{ old('statut', $message->statut) == 'lu' ? 'selected' : '' }}>Lu</option>
                                    <option value="archive" {{ old('statut', $message->statut) == 'archive' ? 'selected' : '' }}>Archivé</option>
                                </select>
                                @error('statut')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contenu du message -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Contenu du message</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                                <textarea name="contenu" id="contenu" rows="8" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('contenu', $message->contenu) }}</textarea>
                                @error('contenu')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Destinataires -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Destinataires</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="eleve_id" class="block text-sm font-medium text-gray-700 mb-2">Élève (optionnel)</label>
                                <select name="eleve_id" id="eleve_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner un élève</option>
                                    @foreach($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" {{ old('eleve_id', $message->eleve_id) == $eleve->id ? 'selected' : '' }}>
                                            {{ $eleve->nom }} {{ $eleve->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="enseignant_id" class="block text-sm font-medium text-gray-700 mb-2">Enseignant (optionnel)</label>
                                <select name="enseignant_id" id="enseignant_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner un enseignant</option>
                                    @foreach($enseignants as $enseignant)
                                        <option value="{{ $enseignant->id }}" {{ old('enseignant_id', $message->enseignant_id) == $enseignant->id ? 'selected' : '' }}>
                                            {{ $enseignant->nom }} {{ $enseignant->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('messages.show', $message) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Voir le message
                        </a>
                        <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 