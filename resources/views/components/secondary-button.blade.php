<button style="color: #222; background: #FFD600; font-size: 16px; min-width: 120px;" {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#FFD600] rounded-lg shadow-md font-semibold text-base text-gray-900 uppercase tracking-widest']) }}>
    {{ $slot }}
</button> 