<button style="color: white; background: #2196F3; font-size: 16px; min-width: 120px;" {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#2196F3] rounded-lg shadow-md font-semibold text-base text-white uppercase tracking-widest']) }}>
    {{ $slot }}
</button> 