@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec titre et boutons d'action -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Messagerie</h1>
                        <p class="text-gray-600">Gérez les messages de l'établissement</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-sm text-gray-500">Votre rôle :</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if(auth()->user()->role === 'admin') bg-red-100 text-red-800
                                @elseif(auth()->user()->role === 'encadrant') bg-blue-100 text-blue-800
                                @elseif(auth()->user()->role === 'medical') bg-green-100 text-green-800
                                @elseif(auth()->user()->role === 'teacher') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if(auth()->user()->role === 'admin') 👨‍💼 Administrateur
                                @elseif(auth()->user()->role === 'encadrant') 🎓 Encadrant
                                @elseif(auth()->user()->role === 'medical') 🏥 Personnel médical
                                @elseif(auth()->user()->role === 'teacher') 📚 Enseignant
                                @else 👤 Utilisateur
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('messages.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <span class="text-lg mr-1">✉️</span> Nouveau message
                        </a>
                        <a href="{{ route('messages.sent') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <span class="text-lg mr-1">📤</span> Messages envoyés
                        </a>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('messages.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-48">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Rechercher par sujet, expéditeur..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Rechercher
                            </button>
                            <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Réponse rapide -->
                @if(request('reply_to'))
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-2">Réponse rapide</h3>
                    <form method="POST" action="{{ route('messages.quick-reply', request('reply_to')) }}" class="space-y-4">
                        @csrf
                        <textarea name="content" rows="3" 
                            class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            placeholder="Votre réponse..."></textarea>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Envoyer la réponse
                        </button>
                    </form>
                </div>
                @endif

                <!-- Tableau des messages -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($messages as $message)
                            <tr class="hover:bg-gray-50 {{ $message->read_at ? '' : 'bg-blue-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $message->sender_name ?? 'Système' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $message->sender_email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">{{ $message->subject }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($message->content, 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('messages.show', $message) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('messages.index', ['reply_to' => $message->id]) }}" class="text-green-600 hover:text-green-900">Répondre</a>
                                        <form method="POST" action="{{ route('messages.destroy', $message) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Aucun message trouvé
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 