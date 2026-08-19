<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-md">

            {{-- Carte --}}
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden border border-white/30">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-sky-500 to-sky-700 text-white text-center py-5 px-6">

                    <div class="flex justify-center mb-4">

                        {{-- Logo --}}
                        <div class="w-11 h-11 rounded-full bg-white shadow flex items-center justify-center">

                            @if(file_exists(public_path('storage/logo.png')))
                                <img src="{{ asset('storage/logo.png') }}"
                                     class="w-11 h-11 object-contain">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-10 h-10 text-sky-600"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 4 2-7L3 9h7z"/>
                                </svg>
                            @endif

                        </div>

                    </div>

                    <h1 class="text-xl font-bold tracking-wide">
                        EKKLESIA GESTION
                    </h1>

                    {{-- <p class="mt-1 text-sky-100 text-sm">
                        Eglise AEBECI Yopougon Nouveau Bureau
                    </p> --}}

                </div>

                {{-- Corps --}}
                {{-- Corps --}}
                <div class="px-8 py-5">

                    <h2 class="text-lg font-bold text-center text-gray-800">
                        Bienvenue
                    </h2>

                    <p class="text-center text-gray-500 text-sm mt-1 mb-5">
                        Accédez à votre espace sécurisé.
                    </p>

                    @if(session('warning'))
                        <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Nom d'utilisateur --}}
                        <div class="relative">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A9 9 0 1118.364 4.56M15 11a3 3 0 11-6 0 3 3 0 016 0zm-3 5c-2.21 0-4 1.79-4 4h8c0-2.21-1.79-4-4-4z"/>
                            </svg>

                            <x-text-input
                                id="username"
                                class="w-full rounded-xl pl-11 py-3"
                                type="text"
                                name="username"
                                :value="old('username')"
                                placeholder="Nom d'utilisateur"
                                required
                                autofocus
                                autocomplete="username"/>

                        </div>

                        <x-input-error :messages="$errors->get('username')" class="mt-1"/>

                        {{-- Mot de passe --}}
                        <div class="relative mt-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v3H6a2 2 0 00-2 2v5a2 2 0 002 2zm3-9V9a3 3 0 016 0v3"/>
                            </svg>

                            <x-text-input
                                id="password"
                                class="w-full rounded-xl pl-11 py-3"
                                type="password"
                                name="password"
                                placeholder="Mot de passe"
                                required
                                autocomplete="current-password"/>

                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-1"/>

                        {{-- Bouton de connexion --}}

                        <button
                            type="submit"
                            class="mt-5 w-full rounded-xl bg-sky-500 py-3 text-white font-semibold shadow-lg transition duration-200 hover:bg-sky-700">

                            Se connecter

                        </button>

                    </form>

                </div>

                {{-- Footer --}}
                <div class="bg-gray-50 px-8 py-4 text-center">

                    <div class="w-14 h-1 bg-yellow-500 rounded mx-auto mb-2"></div>

                    <p class="italic text-gray-600 text-xs">
                        « Mais que tout se fasse avec bienséance et avec ordre. »
                    </p>

                    <p class="mt-1 font-semibold text-yellow-600 text-sm">
                        1 Corinthiens 14:40
                    </p>

                </div>

            </div>

            <p class="text-center text-yellow-600 text-xs mt-6">

                © {{ date('Y') }} • Eglise AEBECI Yopougon Nouveau Bureau

            </p>

        </div>

    </div>

</x-guest-layout>