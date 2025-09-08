<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
            <div class="flex justify-center mb-4">
                <img class="h-12 w-auto" src="{{ asset('logo-ecole.png') }}" alt="Logo" />
            </div>
            <!-- App Title -->
            <h1 class="text-center text-3xl font-extrabold text-indigo-600">Suivi Scolaire</h1>
            <p class="text-center text-sm text-gray-500 mb-6">Connexion</p>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Forgot password link -->
                @if (Route::has('password.request'))
                    <div class="text-right mt-2">
                        <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    </div>
                @endif

                <!-- Login Button -->
                <div class="mt-6">
                    <x-primary-button class="w-full py-2">
                        {{ __('Se connecter') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
