@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-bold mb-8 text-brand-700 flex items-center gap-2">
        <span>⚙️</span> Paramètres
    </h1>
    <div class="bg-white rounded-lg shadow p-8 space-y-8">
        {{-- Section gestion du compte --}}
        <div>
            <h2 class="text-xl font-semibold mb-4">Mon compte</h2>
            @include('profile.partials.update-profile-information-form')
            <hr class="my-6">
            @include('profile.partials.update-password-form')
            <hr class="my-6">
            @include('profile.partials.delete-user-form')
        </div>
        {{-- Section gestion utilisateurs (admin) --}}
        @if(Auth::user()->is_admin ?? false)
        <div>
            <h2 class="text-xl font-semibold mb-4">Gestion des utilisateurs</h2>
            <a href="{{ route('users.index') }}" class="inline-block bg-brand-600 hover:bg-brand-700 text-white font-bold py-2 px-4 rounded transition">Gérer les utilisateurs</a>
        </div>
        @endif
        {{-- Section apparence et préférences --}}
        <div>
            <h2 class="text-xl font-semibold mb-4">Apparence & Préférences</h2>
            {{-- Ici, ajouter les réglages de thème, langue, etc. --}}
            <p>Bientôt disponible : personnalisation de l’apparence, langue, etc.</p>
        </div>
    </div>
</div>
@endsection 