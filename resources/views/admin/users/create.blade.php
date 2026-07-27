@extends('layouts.dashboard')
@section('title', 'Nouveau compte utilisateur')
@section('page-title', 'Administration')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Utilisateurs</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">Nouveau compte</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-700">
        <strong>ℹ Comment ça fonctionne</strong>
        <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
            <li>Sélectionnez un fidèle → le nom est repris automatiquement</li>
            <li>Un <strong>username</strong> est généré automatiquement (prénom.nom)</li>
            <li>Un <strong>mot de passe temporaire</strong> est généré et affiché une seule fois</li>
            <li>L'utilisateur doit changer son mot de passe à la première connexion</li>
        </ul>
    </div>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-5">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Informations du compte</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Fidèle <span class="text-red-500">*</span>
                </label>
                <select name="believer_id" id="believer_select" required
                    onchange="previewUsername(this)"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('believer_id') border-red-300 @enderror">
                    <option value="">-- Sélectionner un fidèle --</option>
                    @foreach($believers as $b)
                    <option value="{{ $b->id }}"
                            data-firstname="{{ $b->firstname }}"
                            data-lastname="{{ $b->lastname }}">
                        {{ $b->lastname }} {{ $b->firstname }}
                    </option>
                    @endforeach
                </select>
                @error('believer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Seuls les fidèles sans compte sont listés.</p>
            </div>

            {{-- Aperçu username --}}
            <div id="username-preview" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Username généré</label>
                <div class="mt-1 flex items-center gap-2">
                    <span id="username-display"
                        class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-md font-mono text-sm"
                        style="color:#3A9BDC; min-width: 200px;"></span>
                    <span class="text-xs text-gray-400">Généré automatiquement</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Rôle <span class="text-red-500">*</span>
                </label>
                <select name="role" required
                    onchange="toggleTeam(this.value)"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir un rôle --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Équipe (visible pour resp. équipes) --}}
            <div id="team-field" class="hidden">
                <label class="block text-sm font-medium text-gray-700">Équipe à gérer</label>
                <select name="team_id"
                    class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Aucune --</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}" @selected(old('team_id') == $team->id)>
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
                ✓ Créer le compte
            </button>
        </div>
    </form>
</div>

<script>
function previewUsername(select) {
    const option = select.options[select.selectedIndex];
    const preview = document.getElementById('username-preview');
    const display = document.getElementById('username-display');

    if (!option.value) {
        preview.classList.add('hidden');
        return;
    }

    const firstname = option.dataset.firstname?.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') ?? '';
    const lastname  = option.dataset.lastname?.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') ?? '';
    const username  = firstname.replace(/\s+/g, '-') + '.' + lastname.replace(/\s+/g, '-');

    display.textContent = username;
    preview.classList.remove('hidden');
}

function toggleTeam(role) {
    const teamField = document.getElementById('team-field');
    const rolesWithTeam = ['jaebeci', 'afebeci', 'direction_ecodim', 'direction_culte'];
    teamField.classList.toggle('hidden', !rolesWithTeam.includes(role));
}
</script>
@endsection