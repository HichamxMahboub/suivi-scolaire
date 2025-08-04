@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages liés à') }} {{ $eleve->nom }} {{ $eleve->prenom }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('messages.create', ['eleve' => $eleve->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    ✉️ Nouveau message
                </a>
                <a href="{{ route('eleves.show', $eleve) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    👤 Voir l'élève
                </a>
                <a href="{{ route('messages.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    📥 Boîte de réception
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations de l'élève -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-12 w-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($eleve->nom, 0, 1)) }}{{ strtoupper(substr($eleve->prenom, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                            <p class="text-gray-600">{{ $eleve->niveau_scolaire }} - {{ $eleve->classe?->nom ?? 'Classe non assignée' }}</p>
                            <p class="text-sm text-gray-500">Matricule: {{ $eleve->matricule ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($messages->count() > 0)
                        <div class="space-y-4">
                            @foreach($messages as $message)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors {{ $message->isUnread() ? 'border-l-4 border-l-blue-500 bg-blue-50' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3 flex-1">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <span class="text-lg">{{ $message->type_icon }}</span>
                                                    <h3 class="font-semibold text-gray-900 {{ $message->isUnread() ? 'font-bold' : '' }}">
                                                        {{ $message->subject }}
                                                    </h3>
                                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $message->priority_color }}-100 text-{{ $message->priority_color }}-800">
                                                        {{ ucfirst($message->priority) }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $message->status_color }}-100 text-{{ $message->status_color }}-800">
                                                        {{ ucfirst($message->status) }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-600 text-sm mb-2">
                                                    @if($message->sender_id === Auth::id())
                                                        <span class="text-blue-600">Envoyé à: {{ $message->recipient->name }}</span>
                                                    @else
                                                        <span class="text-green-600">Reçu de: {{ $message->sender->name }}</span>
                                                    @endif
                                                    <span class="text-gray-400">•</span>
                                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                <p class="text-gray-700 line-clamp-2">
                                                    {{ Str::limit($message->content, 150) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('messages.show', $message) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Lire
                                            </a>
                                            @if($message->sender_id === Auth::id())
                                                <form method="POST" action="{{ route('messages.destroy', $message) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📭</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun message pour cet élève</h3>
                            <p class="text-gray-600 mb-4">Aucun message n'a encore été échangé concernant cet élève.</p>
                            <a href="{{ route('messages.create', ['eleve' => $eleve->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                ✉️ Créer le premier message
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 