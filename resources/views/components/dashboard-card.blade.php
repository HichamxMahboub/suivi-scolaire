@props(['icon', 'color' => 'blue', 'value', 'label'])

@php
    $bgColors = [
        'blue' => 'bg-[#2196F3] text-white',
        'green' => 'bg-[#8BC34A] text-white',
        'pink' => 'bg-[#EC407A] text-white',
        'yellow' => 'bg-[#FFD600] text-gray-900',
        'default' => 'bg-[#2196F3] text-white',
    ];
    $bgClass = $bgColors[$color] ?? $bgColors['default'];
@endphp

<div class="rounded-xl shadow flex flex-col items-center p-6 {{ $bgClass }}">
    <div class="mb-2">
        @if($icon === 'users')
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-4V9a4 4 0 00-3-3.87M7 9V7a4 4 0 013-3.87"/>
            </svg>
        @elseif($icon === 'academic-cap')
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-7"/>
            </svg>
        @elseif($icon === 'clipboard-document-list')
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2H7a2 2 0 00-2 2v5a2 2 0 002 2z"/>
            </svg>
        @elseif($icon === 'user-group')
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87"/>
            </svg>
        @elseif($icon === 'heart')
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a9 9 0 0118 0c0 7-9 13-9 13S4 13 4 6z"/>
            </svg>
        @endif
    </div>
    <div class="text-3xl font-bold">{{ $value }}</div>
    <div class="opacity-90">{{ $label }}</div>
</div> 