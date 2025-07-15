@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-4 text-gray-900">{{ __('dashboard.welcome') }}</h1>
                    <p class="text-gray-600 mb-6">{{ __('dashboard.welcome_subtitle') }}</p>
                    
                    <!-- Statistiques -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">👥</span>
                                <div>
                                    <p class="text-sm text-gray-600">{{ __('dashboard.students') }}</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $stats['eleves'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">🎓</span>
                                <div>
                                    <p class="text-sm text-gray-600">{{ __('dashboard.classes') }}</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $stats['classes'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">📩</span>
                                <div>
                                    <p class="text-sm text-gray-600">{{ __('dashboard.messages') }}</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ $stats['messages'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">📊</span>
                                <div>
                                    <p class="text-sm text-gray-600">{{ __('dashboard.notes') }}</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['total_notes'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('eleves.index') }}" class="bg-[#2196F3] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            Gérer les élèves
                        </a>
                        <a href="{{ route('classes.index') }}" class="bg-[#8BC34A] text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            Gérer les classes
                        </a>
                        <a href="{{ route('encadrants.index') }}" class="bg-pink-500 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                            Encadrants
                        </a>
                        <a href="{{ route('messages.index') }}" class="bg-[#FFD600] text-gray-900 font-bold py-2 px-4 rounded-lg shadow-md">
                            Messagerie
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
