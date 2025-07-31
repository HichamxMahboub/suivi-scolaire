@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages envoyés') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('messages.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    📥 Boîte de réception
                </a>
                <a href="{{ route('messages.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    ✉️ Nouveau message
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3 flex-1">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <span class="text-lg">{{ $message->type_icon }}</span>
                                                    <h3 class="font-semibold text-gray-900">
                                                        {{ $message->subject }}
                                                    </h3>
                                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $message->priority_color }}-100 text-{{ $message->priority_color }}-800">
                                                        {{ ucfirst($message->priority) }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $message->status_color }}-100 text-{{ $message->status_color }}-800">
                                                        {{ ucfirst($message->status) }}
                                                    </span>
                                                    @if($message->eleve)
                                                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                                            👤 {{ $message->eleve->nom }} {{ $message->eleve->prenom }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-600 text-sm mb-2">
                                                    À: <span class="font-medium">{{ $message->recipient->name }}</span>
                                                    <span class="text-gray-400">•</span>
                                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                                    @if($message->read_at)
                                                        <span class="text-gray-400">•</span>
                                                        <span class="text-green-600">Lu le {{ $message->read_at->format('d/m/Y H:i') }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-gray-700 line-clamp-2">
                                                    {{ Str::limit($message->content, 150) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('messages.show', $message) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Voir
                                            </a>
                                            <form method="POST" action="{{ route('messages.destroy', $message) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    🗑️
                                                </button>
                                            </form>
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
                            <div class="text-6xl mb-4">📤</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun message envoyé</h3>
                            <p class="text-gray-600 mb-4">Vous n'avez pas encore envoyé de messages.</p>
                            <a href="{{ route('messages.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                ✉️ Envoyer un message
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 