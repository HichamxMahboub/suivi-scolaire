@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Nouveau message</h1>
                        <p class="text-gray-600">Envoyez un message à un élève ou une classe</p>
                    </div>
                    <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour à la liste
                    </a>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations du message</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Sujet du message">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de message</label>
                                <select name="type" id="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Général</option>
                                    <option value="academique" {{ old('type') == 'academique' ? 'selected' : '' }}>Académique</option>
                                    <option value="comportement" {{ old('type') == 'comportement' ? 'selected' : '' }}>Comportement</option>
                                    <option value="urgence" {{ old('type') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="recipient_id" class="block text-sm font-medium text-gray-700 mb-2">Destinataire (Utilisateur) *</label>
                                <select name="recipient_id" id="recipient_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner un destinataire</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                                            @if($user->role === 'admin') 👨‍💼
                                            @elseif($user->role === 'encadrant') 🎓
                                            @elseif($user->role === 'medical') 🏥
                                            @elseif($user->role === 'teacher') 📚
                                            @else 👤
                                            @endif
                                            {{ $user->name }} 
                                            <span class="text-gray-500">({{ ucfirst($user->role) }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                @error('recipient_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="eleve_id" class="block text-sm font-medium text-gray-700 mb-2">Destinataire (Élève)</label>
                                <select name="eleve_id" id="eleve_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner un élève</option>
                                    @foreach($eleves as $eleve)
                                        <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                            {{ $eleve->nom }} {{ $eleve->prenom }} - {{ $eleve->classe->nom ?? 'Non assigné' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('eleve_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="classe_id" class="block text-sm font-medium text-gray-700 mb-2">Destinataire (Classe)</label>
                                <select name="classe_id" id="classe_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }} ({{ $classe->eleves_count ?? 0 }} élèves)
                                        </option>
                                    @endforeach
                                </select>
                                @error('classe_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu *</label>
                                <textarea name="content" id="content" rows="6" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contenu du message...">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Pièces jointes</label>
                                <input type="file" name="attachments[]" id="attachments" multiple
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                <p class="text-sm text-gray-500 mt-1">Formats acceptés : PDF, DOC, DOCX, TXT, JPG, PNG (max 5 Mo par fichier)</p>
                                @error('attachments')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Envoyer le message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 