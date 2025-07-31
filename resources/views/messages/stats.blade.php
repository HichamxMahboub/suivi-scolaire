@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Statistiques de la messagerie</h1>
                        <p class="text-gray-600">Aperçu de l'activité des messages par rôle</p>
                    </div>
                    <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour aux messages
                    </a>
                </div>

                <!-- Statistiques globales -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-800 bg-opacity-30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 3.26a2 2 0 001.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Messages reçus</h3>
                                <p class="text-2xl font-bold">{{ $stats['total_received'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-800 bg-opacity-30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Messages envoyés</h3>
                                <p class="text-2xl font-bold">{{ $stats['total_sent'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-800 bg-opacity-30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 10l6 6-6 6z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Non lus</h3>
                                <p class="text-2xl font-bold">{{ $stats['unread_received'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-800 bg-opacity-30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Favoris</h3>
                                <p class="text-2xl font-bold">{{ $stats['favorites'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par type de message -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages par type</h3>
                        <div class="space-y-3">
                            @forelse($stats['by_type'] as $type => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 capitalize">
                                        @if($type === 'general') 📋 Général
                                        @elseif($type === 'academic') 📚 Académique
                                        @elseif($type === 'behavior') ⚠️ Comportement
                                        @elseif($type === 'health') 🏥 Santé
                                        @elseif($type === 'parent_contact') 👨‍👩‍👧‍👦 Contact parent
                                        @else 📌 {{ ucfirst($type) }}
                                        @endif
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">Aucun message reçu</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages par priorité</h3>
                        <div class="space-y-3">
                            @forelse($stats['by_priority'] as $priority => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 capitalize">
                                        @if($priority === 'low') 🟢 Basse
                                        @elseif($priority === 'normal') 🟡 Normale
                                        @elseif($priority === 'high') 🟠 Haute
                                        @elseif($priority === 'urgent') 🔴 Urgente
                                        @else {{ ucfirst($priority) }}
                                        @endif
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">Aucun message reçu</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Informations sur votre rôle -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-200">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Votre rôle et permissions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-3">
                                    @if(auth()->user()->role === 'admin') 👨‍💼
                                    @elseif(auth()->user()->role === 'encadrant') 🎓
                                    @elseif(auth()->user()->role === 'medical') 🏥
                                    @elseif(auth()->user()->role === 'teacher') 📚
                                    @else 👤
                                    @endif
                                </span>
                                <div>
                                    <h4 class="font-semibold text-indigo-900">{{ auth()->user()->name }}</h4>
                                    <p class="text-indigo-700">
                                        @if(auth()->user()->role === 'admin') Administrateur
                                        @elseif(auth()->user()->role === 'encadrant') Encadrant pédagogique
                                        @elseif(auth()->user()->role === 'medical') Personnel médical
                                        @elseif(auth()->user()->role === 'teacher') Enseignant
                                        @else Utilisateur
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-medium text-indigo-900 mb-2">Permissions accordées :</h5>
                            <ul class="text-sm text-indigo-700 space-y-1">
                                @if(auth()->user()->canViewMedicalInfo())
                                    <li>✅ Accès aux informations médicales</li>
                                @else
                                    <li>❌ Pas d'accès aux informations médicales</li>
                                @endif
                                
                                @if(auth()->user()->canManageNotes())
                                    <li>✅ Gestion des notes</li>
                                @else
                                    <li>❌ Pas de gestion des notes</li>
                                @endif
                                
                                @if(auth()->user()->canManageUsers())
                                    <li>✅ Gestion des utilisateurs</li>
                                @else
                                    <li>❌ Pas de gestion des utilisateurs</li>
                                @endif
                                
                                <li>✅ Envoi et réception de messages</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('messages.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        ✉️ Nouveau message
                    </a>
                    <a href="{{ route('messages.index', ['filter' => 'unread']) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        👁️ Messages non lus
                    </a>
                    <a href="{{ route('messages.index', ['filter' => 'favorite']) }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                        ❤️ Favoris
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
