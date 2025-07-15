<div class="relative" x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false">
    <div @click="open = !open">
        {{ $trigger }}
    </div>
    <div x-show="open" x-transition class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div> 