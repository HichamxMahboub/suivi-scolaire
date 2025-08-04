@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Détails de l'utilisateur</h1>
                        <p class="text-gray-600">Informations détaillées de {{ $user->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                        <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nom complet</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'teacher') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date de création</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dernière modification</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($user->email_verified_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email vérifié</label>
                                <p class="mt-1 text-sm text-green-600">✓ Vérifié le {{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email vérifié</label>
                                <p class="mt-1 text-sm text-red-600">✗ Non vérifié</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Messages envoyés</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->sentMessages()->count() }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Messages reçus</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->receivedMessages()->count() }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Messages non lus</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->unreadMessages()->count() }}</p>
                            </div>

                        </div>
                    </div>
                </div>

                @if($user->id !== auth()->id())
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    
                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Supprimer l'utilisateur
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 