@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Utilisateurs') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Ajouter un utilisateur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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

                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Nom
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Rôle
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Date de création
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                                    @elseif($user->role === 'encadrant') bg-blue-100 text-blue-800
                                                    @elseif($user->role === 'medical') bg-green-100 text-green-800
                                                    @elseif($user->role === 'teacher') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    @if($user->role === 'admin') 👨‍💼 Administrateur
                                                    @elseif($user->role === 'encadrant') 🎓 Encadrant
                                                    @elseif($user->role === 'medical') 🏥 Personnel médical
                                                    @elseif($user->role === 'teacher') 📚 Enseignant
                                                    @elseif($user->role === 'student') 🎒 Élève
                                                    @else 👤 Utilisateur
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">
                                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">Vous</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">Aucun utilisateur trouvé.</p>
                            <a href="{{ route('users.create') }}" class="mt-4 inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Ajouter le premier utilisateur
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 