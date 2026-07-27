<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center px-4 py-8">

        <div class="w-full max-w-xl">

            {{-- Carte --}}
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden border border-white/30">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-sky-500 to-sky-700 text-white text-center py-5 px-6">

                    <div class="flex justify-center mb-4">

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

                </div>

                {{-- Corps --}}
                <div class="px-8 py-5">

                    <h2 class="text-lg font-bold text-center text-gray-800">
                        🔐 Changement de mot de passe
                    </h2>

                    <p class="text-center text-gray-500 text-sm mt-1 mb-5">
                        Pour votre sécurité, définissez un nouveau mot de passe avant de continuer.
                    </p>

                    {{-- @if(session('warning'))
                        <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                            {{ session('warning') }}
                        </div>
                    @endif --}}

                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-5 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                        Connecté en tant que :
                        <strong>{{ auth()->user()->username }}</strong>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- Mot de passe actuel --}}
                        <div class="relative">

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
                                id="current_password"
                                class="w-full rounded-xl pl-11 py-3"
                                type="password"
                                name="current_password"
                                placeholder="Mot de passe actuel"
                                required
                                autocomplete="current-password"/>

                        </div>

                        <x-input-error :messages="$errors->get('current_password')" class="mt-1"/>

                        {{-- Nouveau mot de passe --}}
                        <div class="relative mt-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2h-1V9a5 5 0 00-10 0v3H6a2 2 0 00-2 2v5a2 2 0 002 2z"/>
                            </svg>

                            <x-text-input
                                id="password"
                                class="w-full rounded-xl pl-11 py-3"
                                type="password"
                                name="password"
                                placeholder="Nouveau mot de passe"
                                required
                                autocomplete="new-password"/>

                        </div>

                        <p class="text-xs text-yellow-600 mt-2">
                            Minimum 8 caractères, avec lettres et chiffres.
                        </p>

                        <x-input-error :messages="$errors->get('password')" class="mt-1"/>

                        {{-- Confirmation --}}
                        <div class="relative mt-4">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m1-6H8a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V8l-4-4z"/>
                            </svg>

                            <x-text-input
                                id="password_confirmation"
                                class="w-full rounded-xl pl-11 py-3"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirmer le nouveau mot de passe"
                                required
                                autocomplete="new-password"/>

                        </div>

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1"/>

                        <button
                            type="submit"
                            class="mt-6 w-full rounded-xl bg-sky-500 py-3 text-white font-semibold shadow-lg transition duration-200 hover:bg-sky-700">

                            Enregistrer le nouveau mot de passe

                        </button>

                    </form>

                    {{-- Déconnexion (formulaire séparé) --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-gray-300 py-3 text-gray-600 hover:bg-gray-100 transition">

                            Se déconnecter

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