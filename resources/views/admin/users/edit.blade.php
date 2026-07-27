@extends('layouts.dashboard')
@section('title', 'Modifier compte')
@section('page-title', 'Administration')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Utilisateurs</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Infos compte --}}
    <div class="bg-white shadow-sm rounded-lg p-5">
        <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Informations du compte</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs">Nom</p>
                <p class="font-medium text-gray-800">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Username</p>
                <p class="font-mono font-medium" style="color:#3A9BDC">{{ $user->username }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Statut</p>
                <p class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-500' }}">
                    {{ $user->is_active ? '● Actif' : '● Inactif' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">Mot de passe</p>
                <p class="font-medium {{ $user->must_change_password ? 'text-yellow-600' : 'text-green-600' }}">
                    {{ $user->must_change_password ? '⚠ Temporaire' : '✓ Changé' }}
                </p>
            </div>
            @if($user->believer)
            <div class="col-span-2">
                <p class="text-gray-500 text-xs">Fidèle lié</p>
                <a href="{{ route('believers.show', $user->believer) }}"
                   class="font-medium hover:underline" style="color:#3A9BDC">
                    {{ $user->believer->full_name }}
                </a>
            </div>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Modifier le rôle</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                <select name="role" required
                    onchange="toggleTeam(this.value)"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                        @selected(old('role', $user->getRoleNames()->first()) === $role->name)>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="team-field">
                <label class="block text-sm font-medium text-gray-700">Équipe à gérer</label>
                <select name="team_id"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Aucune --</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}"
                        @selected(old('team_id', $user->believer?->teams->first()?->id) == $team->id)>
                        {{ $team->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ route('users.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
function toggleTeam(role) {
    const teamField = document.getElementById('team-field');
    const rolesWithTeam = ['resp_j_aebeci', 'resp_afebeci', 'resp_ecodim', 'resp_culte'];
    teamField.style.opacity = rolesWithTeam.includes(role) ? '1' : '0.5';
}
toggleTeam('{{ $user->getRoleNames()->first() }}');
</script>
@endsection