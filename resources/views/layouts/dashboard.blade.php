<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bleu:  #3A9BDC;
            --blanc: #FFFFFF;
            --dore:  #C9A635;
            --vert:  #3FA46A;
        }
        .sidebar { background: linear-gradient(180deg, #1a2e4a 0%, #0f1e33 100%); }
        .nav-link { transition: all .15s ease; }
        .nav-link:hover, .nav-link.active {
            background: rgba(58,155,220,.15);
            border-left: 3px solid var(--bleu);
            color: var(--bleu) !important;
        }
        .nav-link.active { font-weight: 600; }
        .nav-section { color: var(--dore); font-size: .65rem; letter-spacing: .1em; }
        .stat-card { border-left: 4px solid; }
        .stat-bleu  { border-color: var(--bleu);  background: #eaf5fd; }
        .stat-vert  { border-color: var(--vert);  background: #eaf7f1; }
        .stat-dore  { border-color: var(--dore);  background: #fdf8ea; }
        .stat-rouge { border-color: #e53e3e; background: #fff5f5; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden relative">

    {{-- ======================== OVERLAY (mobile uniquement) ======================== --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col overflow-y-auto
                   fixed lg:static inset-y-0 left-0 z-40
                   -translate-x-full lg:translate-x-0
                   transition-transform duration-200 ease-in-out"
           id="sidebar">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm"
                     style="background:var(--dore); color:#1a2e4a">
                    ✝
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">{{ config('app.name') }}</p>
                    <p class="text-xs" style="color:var(--dore)">Gestion d'église</p>
                </div>
            </div>
        </div>

        {{-- Utilisateur connecté --}}
        <div class="px-6 py-4 border-b border-white/10">
            <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 capitalize">
                {{ auth()->user()->getRoleNames()->first() ?? 'Utilisateur' }}
            </p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1">

            {{-- Tableau de bord --}}
            <p class="nav-section px-3 py-2 uppercase">Tableau de bord</p>
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <span>📊</span> Vue d'ensemble
            </a>

            {{-- Membres — réservé admin/pasteur/secretariat --}}
            @hasanyrole('admin|pasteur|secretariat')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Membres</p>
            <a href="{{ route('believers.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('believers.*') ? 'active' : '' }}">
                <span>👥</span> Fidèles
            </a>
            <a href="{{ route('newcomers.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('newcomers.*') ? 'active' : '' }}">
                <span>🌱</span> Visiteurs
            </a>
            <a href="{{ route('departures.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('departures.*') ? 'active' : '' }}">
                <span>🚶</span> Départs & Décès
            </a>

            <a href="{{ route('sanctions.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('sanctions.*') ? 'active' : '' }}">
                <span>⚖️</span> Sanctions
            </a>
            @endhasanyrole

            {{-- Registres --}}
            @hasanyrole('admin|pasteur')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Registres</p>
            <a href="{{ route('mariage.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('mariage.*') ? 'active' : '' }}">
                <span>💍</span> Registre des mariages
            </a>
            <a href="{{ route('funeral.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('funeral.*') ? 'active' : '' }}">
                <span>📋</span> Registre funéraire
            </a>
            <a href="{{ route('dedication.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('dedication.*') ? 'active' : '' }}">
                <span>👶</span> Présentation d'enfant
            </a>
            @endhasanyrole

            {{-- Cultes --}}
            @hasanyrole('admin|pasteur|direction_culte')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Cultes</p>
            <a href="{{ route('cultes.periodes') }}"
                class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('cultes.*') ? 'active' : '' }}">
                    <span>⛪</span> Gestion des cultes
            </a>
            <a href="{{ route('cultes.acteurs') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('cultes.acteurs*') ? 'active' : '' }}">
                <span>🎤</span> Acteurs de culte
            </a>
            @endhasanyrole

            {{-- Équipes & Activités --}}
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Équipes</p>
            @hasanyrole('admin|pasteur')
            <a href="{{ route('teams.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>🤝</span> Équipes
            </a>
            @endhasanyrole
            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>📌</span> Activités
            </a>

            {{-- Groupes --}}
            @hasanyrole('admin|pasteur')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Groupes</p>
            <a href="{{ route('groups.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>👥</span> Groupes
            </a>
            @endhasanyrole

            {{-- Groupes de louange --}}
            @hasanyrole('admin|pasteur')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Groupes de Louange</p>
            <a href="{{ route('worship-groups.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>👥</span> Groupes de Louange
            </a>
            @endhasanyrole

            {{-- Finances --}}
            @hasanyrole('admin|pasteur|secretariat')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Gestion des Finances</p>
            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>💰</span> Transactions
            </a>
            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>📋</span> Budgets
            </a>
            @endhasanyrole

            {{-- Rapports --}}
            @hasanyrole('admin|pasteur')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Rapports</p>
            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>📈</span> Statistiques
            </a>
            <a href="#"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm">
                <span>📄</span> Rapports financiers
            </a>
            @endhasanyrole

            {{-- Administration --}}
            @hasrole('admin')
            <p class="nav-section px-3 py-2 mt-4 uppercase">Administration</p>
            <a href="{{ route('users.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2 rounded text-gray-300 text-sm {{ request()->routeIs('users*') ? 'active' : '' }}">
                <span>👤</span> Utilisateurs
            </a>
            @endhasrole

        </nav>

        {{-- Déconnexion --}}
        <div class="px-3 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="nav-link w-full flex items-center gap-3 px-3 py-2 rounded text-gray-400 text-sm hover:text-red-400">
                    <span>🚪</span> Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- ======================== CONTENU ======================== --}}
    <div class="flex-1 flex flex-col overflow-hidden w-full">

        {{-- Topbar --}}
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()"
                        class="text-gray-500 hover:text-gray-700 lg:hidden">
                        ☰
                    </button>
                    <h1 class="text-gray-700 font-semibold text-base">
                        @yield('page-title', 'Tableau de bord')
                    </h1>
                </div>
                <div class="text-sm text-gray-400">
                    {{ now()->translatedFormat('l d F Y') }}
                </div>
            </div>
        </header>

        {{-- Contenu principal --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.toggle('hidden');
    }
</script>

@stack('scripts')
</body>
</html>