{{-- Composant pour afficher la photo d'un élève --}}
@props(['eleve', 'size' => 'md'])

@php
    $sizes = [
        'xs' => 'h-6 w-6 text-xs',
        'sm' => 'h-8 w-8 text-sm', 
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-16 w-16 text-lg',
        'xl' => 'h-24 w-24 text-xl',
        'xxl' => 'h-32 w-32 text-4xl'
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($eleve->photo)
    <img src="{{ asset('storage/' . $eleve->photo) }}" 
         alt="Photo de {{ $eleve->nom }} {{ $eleve->prenom }}" 
         class="{{ $sizeClass }} rounded-full object-cover border-2 border-gray-300">
@else
    <div class="{{ $sizeClass }} rounded-full bg-gray-300 flex items-center justify-center border-2 border-gray-300">
        <span class="font-medium text-gray-700">
            {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
        </span>
    </div>
@endif
