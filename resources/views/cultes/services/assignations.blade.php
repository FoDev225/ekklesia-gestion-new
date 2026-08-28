@extends('layouts.dashboard')
@section('title', 'Programmer le culte du ' . $service->service_date?->format('d/m/Y'))
@section('page-title', 'Gestion des cultes')

@section('content')
<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('cultes.periodes') }}" class="text-sm text-gray-500 hover:text-gray-700">Périodes</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('cultes.services', $service->periode) }}" class="text-sm text-gray-500 hover:text-gray-700">{{ $service->periode->name }}</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm font-medium text-gray-700">{{ $service->service_date?->format('d/m/Y') }}</span>
    </div>

    {{-- Entête culte --}}
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">
                Culte du {{ $service->service_date?->translatedFormat('l d F Y') }}
            </h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                    {{ $service->service_type_label }}
                </span>
                @if($service->service_theme)
                <span class="text-sm text-gray-500">{{ $service->service_theme }}</span>
                @endif
            </div>
        </div>
        <div class="text-xs text-gray-400">{{ $service->assignments->count() }} attribution(s)</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Formulaire ajout attribution --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Ajouter une attribution</h4>
            <form method="POST" action="{{ route('cultes.assignations.store', $service) }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rôle <span class="text-red-500">*</span></label>
                    <select name="service_role_id" id="role_select" required
                        onchange="onRoleChange(this)"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir un rôle --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" data-slug="{{ $role->slug }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Champ Acteur — masqué si rôle = Louange --}}
                <div id="acteur_field">
                    <label class="block text-sm font-medium text-gray-700">Acteur <span class="text-red-500">*</span></label>
                    <select name="believer_id" id="acteur_select"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 js-believer-select">
                        <option value="">-- Sélectionner un acteur --</option>
                        @foreach($acteurs as $roleId => $roleActeurs)
                            @foreach($roleActeurs as $acteur)
                            <option value="{{ $acteur->believer_id }}"
                                    data-role="{{ $acteur->service_role_id }}"
                                    class="acteur-option">
                                {{ $acteur->believer->full_name }}
                            </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                {{-- Champ Groupe de louange — visible uniquement si rôle = Louange --}}
                <div id="worship_group_field" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Groupe de louange <span class="text-red-500">*</span></label>
                    <select name="worship_group_id" id="worship_group_select"
                        class="mt-1 block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Sélectionner un groupe --</option>
                        @foreach($worshipGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $louangeCount }} / {{ $maxGroups }} groupe(s) déjà programmé(s) pour ce culte
                    </p>
                </div>

                <div id="backup_field">
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" name="is_backup" value="1" class="rounded border-gray-300 text-indigo-600">
                        Suppléant
                    </label>
                </div>

                <button type="submit"
                    class="w-full px-4 py-2 text-white text-sm rounded-md font-medium" style="background:#3A9BDC">
                    + Ajouter l'attribution
                </button>
            </form>
        </div>

        {{-- Attributions actuelles --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-3 mb-4">Attributions actuelles</h4>

            @php
                $grouped = $service->assignments->groupBy('role.name');
            @endphp

            @forelse($grouped as $roleName => $assignments)
            <div class="mb-4">
                <p class="text-xs font-bold uppercase text-gray-500 mb-2" style="color:#1F4E79">{{ $roleName }}</p>
                @foreach($assignments as $assignment)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                    <div class="flex items-center gap-2">
                        @if($assignment->worshipGroup)
                            <span class="text-sm text-gray-800">{{ $assignment->worshipGroup->name }}</span>
                            <span class="px-1.5 py-0.5 bg-purple-100 text-purple-600 text-xs rounded">Groupe</span>
                        @else
                            <span class="text-sm text-gray-800">{{ $assignment->believer?->full_name }}</span>
                            @if($assignment->is_backup)
                                <span class="px-1.5 py-0.5 bg-yellow-100 text-black text-xs rounded">Suppléant</span>
                            @endif
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($assignment->is_backup && !$assignment->worshipGroup)
                        <form method="POST" action="{{ route('cultes.assignations.promote', $assignment) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-blue-500 hover:text-blue-700 hover:underline">
                                Promouvoir
                            </button>
                        </form>
                        @endif
                        <form method="POST"
                            action="{{ route('cultes.assignations.destroy', $assignment) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-xs">✕</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <p class="text-gray-400 text-sm">Aucune attribution pour ce culte.</p>
            @endforelse
        </div>

    </div>
</div>

<script>
    const acteurs = @json($acteurs);

    function onRoleChange(select) {
        const selectedOption = select.options[select.selectedIndex];
        const slug = selectedOption?.dataset.slug;
        const isLouange = slug === 'louange';

        document.getElementById('acteur_field').classList.toggle('hidden', isLouange);
        document.getElementById('worship_group_field').classList.toggle('hidden', !isLouange);
        document.getElementById('backup_field').classList.toggle('hidden', isLouange);

        document.getElementById('acteur_select').required = !isLouange;
        document.getElementById('worship_group_select').required = isLouange;

        if (!isLouange) {
            filterActeurs(select.value);
        }
    }

    function filterActeurs(roleId) {
        const select = document.getElementById('acteur_select');
        const options = select.querySelectorAll('.acteur-option');
        options.forEach(opt => {
            opt.hidden = roleId && opt.dataset.role !== roleId;
        });
        select.value = '';
    }
</script>
@endsection