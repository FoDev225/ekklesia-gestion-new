@extends('layouts.dashboard')
@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Administration')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
        </div>
        <a href="{{ route('users.create') }}"
           class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
            + Nouveau compte
        </a>
    </div>

    {{-- Flash création compte --}}
    @if(session('user_created'))
    @php $uc = session('user_created'); @endphp
    <div class="bg-green-50 border-2 border-green-400 rounded-lg p-5">
        <h4 class="font-bold text-green-800 mb-3 flex items-center gap-2">
            ✅ Compte créé avec succès — Transmettez ces identifiants à l'utilisateur
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nom</p>
                <p class="font-bold text-gray-800">{{ $uc['name'] }}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nom d'utilisateur</p>
                <p class="font-bold font-mono" style="color:#3A9BDC">{{ $uc['username'] }}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Mot de passe temporaire</p>
                <p class="font-bold font-mono text-red-600">{{ $uc['password'] }}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Rôle</p>
                <p class="font-bold text-gray-800">{{ $uc['role'] }}</p>
            </div>
        </div>
        <p class="text-xs text-green-700 mt-3">
            ⚠ Ce mot de passe temporaire ne sera plus affiché. L'utilisateur devra le changer à sa première connexion.
        </p>
    </div>
    @endif

    {{-- Flash reset mot de passe --}}
    @if(session('password_reset'))
    @php $pr = session('password_reset'); @endphp
    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-5">
        <h4 class="font-bold text-yellow-800 mb-3">🔑 Mot de passe réinitialisé</h4>
        <div class="grid grid-cols-3 gap-3 text-sm">
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Utilisateur</p>
                <p class="font-bold">{{ $pr['name'] }}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Username</p>
                <p class="font-bold font-mono" style="color:#3A9BDC">{{ $pr['username'] }}</p>
            </div>
            <div class="bg-white rounded p-3 border">
                <p class="text-gray-500 text-xs">Nouveau mot de passe temporaire</p>
                <p class="font-bold font-mono text-red-600">{{ $pr['password'] }}</p>
            </div>
        </div>
        <p class="text-xs text-yellow-700 mt-2">L'utilisateur devra changer ce mot de passe à sa prochaine connexion.</p>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
    @endif

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('users.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom ou username..."
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1">
            <select name="role" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                Filtrer
            </button>
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md">
                Reset
            </a>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Utilisateur</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Rôle</th>
                    {{-- <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Fidèle lié</th> --}}
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">MDP</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 {{ !$user->is_active ? 'opacity-60' : '' }}">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-sm" style="color:#3A9BDC">{{ $user->username }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            {{ $user->getRoleNames()->first() ?? '—' }}
                        </span>
                    </td>
                    {{-- <td class="px-4 py-3 text-gray-600 text-xs">
                        @if($user->believer)
                            <a href="{{ route('believers.show', $user->believer) }}"
                               class="hover:underline" style="color:#3A9BDC">
                                {{ $user->believer->full_name }}
                            </a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td> --}}
                    <td class="px-4 py-3 text-center">
                        @if($user->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">● Actif</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded-full font-medium">● Inactif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($user->must_change_password)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full" title="Mot de passe temporaire non changé">
                                ⚠ Temporaire
                            </span>
                        @else
                            <span class="text-green-600 text-xs">✓ Changé</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        {{-- Activer / Désactiver --}}
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                            @csrf
                            <button class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded
                                {{ $user->is_active ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700' }}">
                                {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>

                        {{-- Modifier rôle --}}
                        <a href="{{ route('users.edit', $user) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>

                        {{-- Réinitialiser mot de passe --}}
                        <button type="button"
                            onclick="openResetPasswordModal(
                                '{{ $user->id }}',
                                '{{ $user->name }}'
                            )"
                            class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded hover:bg-gray-200">
                            🔑 Reset MDP
                        </button>
                        {{-- <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="inline"
                              onsubmit="return confirm('Réinitialiser le mot de passe de {{ addslashes($user->name) }} ?')">
                            @csrf
                            <button class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                🔑 Reset MDP
                            </button>
                        </form> --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun utilisateur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-4 py-3 border-t">{{ $users->links() }}</div>
        @endif
    </div>
</div>
    {{-- ===================== MODAL RESET PASSWORD ===================== --}}
    <div id="resetPasswordModal"
        class="fixed inset-0 z-50 hidden overflow-y-auto">

        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75"
            onclick="closeResetPasswordModal()"></div>

        <div class="flex min-h-screen items-center justify-center px-4">

            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">

                {{-- En-tête --}}
                <div class="flex items-center justify-between px-6 py-4 border-b bg-yellow-500 text-white">

                    <h3 class="text-lg font-semibold">
                        Réinitialisation du mot de passe
                    </h3>

                    <button
                        type="button"
                        onclick="closeResetPasswordModal()"
                        class="text-white text-2xl leading-none hover:text-gray-200">
                        &times;
                    </button>

                </div>

                {{-- Corps --}}
                <form id="reset-password-form" method="POST">
                    @csrf

                    <div class="px-6 py-5 space-y-4">

                        <p class="text-sm text-gray-700">
                            Vous êtes sur le point de réinitialiser le mot de passe de
                            <strong id="reset-user-name"></strong>.
                        </p>

                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4">

                            <p class="text-sm text-yellow-800">
                                ⚠️ Un nouveau mot de passe temporaire sera généré automatiquement.
                            </p>

                            <p class="text-sm text-yellow-800 mt-2">
                                L'utilisateur devra le modifier lors de sa prochaine connexion.
                            </p>

                        </div>

                    </div>

                    {{-- Pied --}}
                    <div class="px-6 py-4 border-t flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="closeResetPasswordModal()"
                            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">

                            Annuler

                        </button>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                            🔑 Réinitialiser

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

<script>

    function openResetPasswordModal(userId, userName)
    {
        document.getElementById('reset-user-name').textContent = userName;

        document.getElementById('reset-password-form').action =
            '/admin/users/' + userId + '/reset-password';

        document.getElementById('resetPasswordModal').classList.remove('hidden');
    }

    function closeResetPasswordModal()
    {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }

</script>
@endsection