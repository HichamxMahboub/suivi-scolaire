<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Preload critical resources -->
        <link rel="preload" href="{{ asset('logo-ecole.png') }}" as="image">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="dns-prefetch" href="https://fonts.bunny.net">

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Performance optimization -->
        <script>
            // Preload critical CSS
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'style';
            link.href = '{{ asset("build/assets/app.css") }}';
            document.head.appendChild(link);
        </script>
    </head>
    <body class="font-sans antialiased {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="min-h-screen bg-gray-100 flex flex-row-reverse">
            {{-- Sidebar à droite --}}
            @include('layouts.sidebar')
            {{-- Contenu principal --}}
            <div class="flex-1 flex flex-col min-h-screen">
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
                <main>
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
        // Dark mode toggle (à déplacer plus tard dans la page paramètres)
        function setDarkMode(on) {
            if(on) { document.body.classList.add('dark'); localStorage.setItem('theme', 'dark'); }
            else { document.body.classList.remove('dark'); localStorage.setItem('theme', 'light'); }
        }
        // Langue avec persistance backend (à déplacer plus tard dans la page paramètres)
        function setLang(lang, reload = false) {
            document.documentElement.lang = lang;
            document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
            document.body.classList.remove('rtl', 'ltr');
            document.body.classList.add(lang === 'ar' ? 'rtl' : 'ltr');
            localStorage.setItem('lang', lang);
            if (reload) {
                fetch('/lang/' + lang, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                }).then(() => { window.location.reload(); });
            }
        }
        // Taille police (à déplacer plus tard dans la page paramètres)
        function setFontSize(size) {
            document.body.classList.remove('text-sm','text-base','text-lg');
            document.body.classList.add('text-' + size);
            localStorage.setItem('fontsize', size);
        }
        // Contraste renforcé (à déplacer plus tard dans la page paramètres)
        function setContrast(on) {
            if(on) { document.body.classList.add('contrast'); localStorage.setItem('contrast', 'on'); }
            else { document.body.classList.remove('contrast'); localStorage.setItem('contrast', 'off'); }
        }
        // Reset (à déplacer plus tard dans la page paramètres)
        function resetSettings() {
            localStorage.removeItem('theme');
            localStorage.removeItem('lang');
            localStorage.removeItem('fontsize');
            localStorage.removeItem('contrast');
            location.reload();
        }
        </script>
    </body>
</html>
