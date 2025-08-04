@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $message->sujet }}</h1>
                        <p class="text-gray-600">Détails du message</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('messages.edit', $message) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Informations du message -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Métadonnées -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Informations du message</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Type</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($message->type ?? 'information') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Priorité</p>
                                @php
                                    $prioriteColors = [
                                        'basse' => 'bg-green-100 text-green-800',
                                        'normale' => 'bg-blue-100 text-blue-800',
                                        'haute' => 'bg-yellow-100 text-yellow-800',
                                        'urgente' => 'bg-red-100 text-red-800'
                                    ];
                                    $color = $prioriteColors[$message->priorite ?? 'normale'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($message->priorite ?? 'normale') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Statut</p>
                                @php
                                    $statutColors = [
                                        'brouillon' => 'bg-gray-100 text-gray-800',
                                        'envoye' => 'bg-blue-100 text-blue-800',
                                        'lu' => 'bg-green-100 text-green-800',
                                        'archive' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $color = $statutColors[$message->statut ?? 'brouillon'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ ucfirst($message->statut ?? 'brouillon') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Date de création</p>
                                <p class="text-gray-900">{{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Destinataires -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Destinataires</h3>
                        <div class="space-y-3">
                            @if($message->eleve)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Élève</p>
                                <p class="text-gray-900">{{ $message->eleve->nom }} {{ $message->eleve->prenom }}</p>
                                <a href="{{ route('eleves.show', $message->eleve) }}" class="text-blue-600 hover:text-blue-900 text-sm">Voir l'élève</a>
                            </div>
                            @endif
                            
                            @if($message->enseignant)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Enseignant</p>
                                <p class="text-gray-900">{{ $message->enseignant->nom }} {{ $message->enseignant->prenom }}</p>
                                <a href="{{ route('enseignants.show', $message->enseignant) }}" class="text-blue-600 hover:text-blue-900 text-sm">Voir l'enseignant</a>
                            </div>
                            @endif
                            
                            @if(!$message->eleve && !$message->enseignant)
                            <div>
                                <p class="text-sm text-gray-500">Message général (pas de destinataire spécifique)</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenu du message -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Contenu du message</h3>
                    <div class="bg-white p-4 rounded border">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $message->contenu }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Actions</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('messages.edit', $message) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Modifier le message
                        </a>
                        @if($message->eleve)
                        <a href="{{ route('messages.create', ['eleve_id' => $message->eleve->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nouveau message à cet élève
                        </a>
                        @endif
                        @if($message->enseignant)
                        <a href="{{ route('messages.create', ['enseignant_id' => $message->enseignant->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nouveau message à cet enseignant
                        </a>
                        @endif
                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 