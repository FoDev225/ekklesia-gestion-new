@extends('layouts.dashboard')

@section('title', $team->name)
@section('page-title', 'Détail de l\'équipe')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
            class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            @can('viewAny', \App\Models\Team::class)
                <span class="text-gray-300">/</span>
                <a href="{{ route('teams.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Équipes</a>
            @endcan
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $team->name }}</span>
        </div>
        <div class="flex gap-2">
            @can('update', $team)
            <a href="{{ route('teams.edit', $team) }}"
            class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
            style="background:#C9A635">
                Modifier
            </a>
            @endcan
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- En-tête équipe --}}
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $team->name }}</h2>
            <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $team->slug }}</span>
        </div>
        <a href="{{ route('teams.members-pdf', $team) }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            📄 Télécharger liste des membres (PDF)
        </a>
    </div>

    {{-- Stats équipe --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $team->believers->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Responsable</p>
            <p class="text-sm font-bold mt-2" style="color:#3A9BDC">
                {{ $team->leader->full_name ?? $team->leader->name ?? '—' }}
            </p>
        </div>
    </div>

    {{-- Stats programme d'activité --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Statistiques du programme d'activité</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="rounded-lg p-3 border-l-4" style="border-color:#3A9BDC; background:#f0f9ff">
                <p class="text-xs text-gray-500 uppercase">Programmées</p>
                <p class="text-xl font-bold" style="color:#3A9BDC">{{ $activityStats['total'] }}</p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#3FA46A; background:#f0fdf4">
                <p class="text-xs text-gray-500 uppercase">Réalisées</p>
                <p class="text-xl font-bold" style="color:#3FA46A">
                    {{ $activityStats['realisees'] }} <span class="text-xs font-normal">({{ $activityStats['pct_realisees'] }}%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#e11d48; background:#fef2f2">
                <p class="text-xs text-gray-500 uppercase">Non réalisées</p>
                <p class="text-xl font-bold" style="color:#e11d48">
                    {{ $activityStats['non_realisees'] }} <span class="text-xs font-normal">({{ $activityStats['pct_non_realisees'] }}%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#C9A635; background:#fefce8">
                <p class="text-xs text-gray-500 uppercase">En cours</p>
                <p class="text-xl font-bold" style="color:#C9A635">
                    {{ $activityStats['en_cours'] }} <span class="text-xs font-normal">({{ $activityStats['pct_en_cours'] }}%)</span>
                </p>
            </div>
            <div class="rounded-lg p-3 border-l-4" style="border-color:#1a2e4a; background:#f8fafc">
                <p class="text-xs text-gray-500 uppercase">Budget total</p>
                <p class="text-lg font-bold" style="color:#1a2e4a">{{ number_format($activityStats['budget_total'], 0, ',', ' ') }} F</p>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="{{ route('teams.activities.program-pdf', $team) }}"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#3A9BDC">
                📄 Télécharger programme (PDF)
            </a>
            <a href="{{ route('teams.activities.report-pdf', $team) }}"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#1a2e4a">
                📄 Télécharger rapport d'activités (PDF)
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Programme d'activité --}}
        <div class="md:col-span-2 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Informations</h3>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-800">Description :</span>
                    {{ $team->description ?: '—' }}
                </p>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Programme d'activité</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Activité</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Thème</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Modérateur</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Prédicateur</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Présence</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Rapport</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Budget</th>
                            <th class="px-3 py-3 text-left font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-3 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 font-medium text-gray-900">{{ $activity->title }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $activity->date->format('d/m/Y') }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $activity->theme ?? '—' }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $activity->moderator ?? '—' }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $activity->preacher ?? '—' }}</td>
                            <td class="px-3 py-3 text-center">
                                @if($activity->attendance_list_path)
                                    <a href="{{ Storage::url($activity->attendance_list_path) }}" target="_blank"
                                       class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($activity->report_path)
                                    <a href="{{ Storage::url($activity->report_path) }}" target="_blank"
                                       class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-gray-600">{{ $activity->budget ? number_format($activity->budget, 0, ',', ' ') . ' F' : '—' }}</td>
                            <td class="px-3 py-3">
                                @switch($activity->status)
                                    @case('realisee')
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Réalisée</span>
                                        @break
                                    @case('non_realisee')
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Non réalisée</span>
                                        @break
                                    @default
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">En cours</span>
                                @endswitch
                            </td>
                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                @if($activity->isTerminable())
                                @can('manage', $team)
                                    <button type="button"
                                        onclick="openFinishModal({{ $activity->id }}, '{{ $activity->title }}')"
                                        class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded mr-1">
                                        Terminer
                                    </button>
                                    <button type="button"
                                        onclick="openPostponeModal({{ $activity->id }}, '{{ $activity->title }}')"
                                        class="text-xs px-2 py-1 bg-orange-100 text-orange-700 rounded">
                                        Ajourner
                                    </button>
                                @endcan
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                                Aucune activité programmée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Membres de l'équipe</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membre depuis</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($team->believers as $believer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $believer->full_name ?? $believer->name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $believer->pivot->joined_at ? \Carbon\Carbon::parse($believer->pivot->joined_at)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <form action="{{ route('teams.believers.destroy', [$team, $believer]) }}" method="POST"
                                      onsubmit="return confirm('Retirer ce membre de l\'équipe ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                                        Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Aucun membre pour le moment.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Formulaires --}}
        <div class="md:col-span-1 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Programmer une activité</h3>
                <form action="{{ route('teams.activities.store', $team) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Activité</label>
                        <input type="text" name="title" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                               value="{{ old('title') }}">
                        @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date</label>
                        <input type="date" name="date" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('date') border-red-500 @enderror"
                               value="{{ old('date') }}">
                        @error('date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Thème</label>
                        <input type="text" name="theme"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('theme') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Président</label>
                        <input type="text" name="moderator"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('moderator') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Prédicateur</label>
                        <input type="text" name="preacher"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('preacher') }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Budget (FCFA)</label>
                        <input type="number" step="0.01" name="budget"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('budget') }}">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Programmer
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Affecter un fidèle</h3>
                <form action="{{ route('teams.believers.store', $team) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fidèle</label>
                        <select name="believer_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('believer_id') border-red-500 @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach ($availableBelievers as $believer)
                                <option value="{{ $believer->id }}">{{ $believer->full_name ?? $believer->name }}</option>
                            @endforeach
                        </select>
                        @error('believer_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date d'adhésion</label>
                        <input type="date" name="joined_at" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('joined_at', now()->format('Y-m-d')) }}">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Affecter
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

{{-- Modal : Terminer une activité --}}
<div id="finishModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">
            Clôturer l'activité : <span id="finishActivityTitle" class="font-normal normal-case"></span>
        </h3>
        <form id="finishForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Liste de présence (PDF)</label>
                <input type="file" name="attendance_list_path" accept="application/pdf" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Rapport (PDF)</label>
                <input type="file" name="report_path" accept="application/pdf" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#3FA46A">
                    Confirmer
                </button>
                <button type="button" onclick="closeFinishModal()"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal : Ajourner une activité --}}
<div id="postponeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">
            Ajourner l'activité : <span id="postponeActivityTitle" class="font-normal normal-case"></span>
        </h3>
        <form id="postponeForm" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Nouvelle date</label>
                <input type="date" name="new_date" required
                       class="w-full border-gray-300 rounded-md text-sm">
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                    style="background:#C9A635">
                    Confirmer
                </button>
                <button type="button" onclick="closePostponeModal()"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openFinishModal(activityId, title) {
        document.getElementById('finishActivityTitle').textContent = title;
        document.getElementById('finishForm').action = `/teams/{{ $team->id }}/activities/${activityId}/finish`;
        document.getElementById('finishModal').classList.remove('hidden');
    }
    function closeFinishModal() {
        document.getElementById('finishModal').classList.add('hidden');
    }

    function openPostponeModal(activityId, title) {
        document.getElementById('postponeActivityTitle').textContent = title;
        document.getElementById('postponeForm').action = `/teams/{{ $team->id }}/activities/${activityId}/postpone`;
        document.getElementById('postponeModal').classList.remove('hidden');
    }
    function closePostponeModal() {
        document.getElementById('postponeModal').classList.add('hidden');
    }
</script>
@endsection