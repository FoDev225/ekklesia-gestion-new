@extends('layouts.dashboard')

@section('title', 'Sanctions disciplinaires')
@section('page-title', 'Sanctions disciplinaires')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('believers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Sanctions disciplinaires</span>
        </div>
    </div>

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-red-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Actives</p>
            <p class="text-2xl font-bold mt-1 text-red-500">{{ $stats['actives'] }}</p>
            <p class="text-xs text-gray-400 mt-1">En cours</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Levées</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['levees'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Terminées</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['annee'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('sanctions.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom, prénom..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="status"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Toutes</option>
                <option value="active" @selected(request('status') === 'active')>🔴 Actives</option>
                <option value="levee"  @selected(request('status') === 'levee')>🟢 Levées</option>
            </select>

            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                @endforeach
            </select>

            <div class="col-span-2 md:col-span-4 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="{{ route('sanctions.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    {{ $sanctions->total() }} sanction(s)
                </span>
            </div>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Fidèle</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Décidé par</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sanctions as $sanction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('believers.show', $sanction->believer) }}"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            {{ $sanction->believer->full_name }}
                        </a>
                        <p class="text-xs text-gray-400">
                            {{ $sanction->believer->gender_label }}
                            @if($sanction->believer->age)
                                · {{ $sanction->believer->age }} ans
                            @endif
                        </p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $sanction->start_date?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $sanction->end_date?->format('d/m/Y') ?? 'Indéterminée' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2">{{ $sanction->reason ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $sanction->decided_by ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($sanction->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                🔴 Active
                            </span>
                        @else
                            <div>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    🟢 Levée
                                </span>
                                @if($sanction->lifted_at)
                                    <p class="text-xs text-gray-400 mt-1">
                                        le {{ $sanction->lifted_at->format('d/m/Y') }}
                                    </p>
                                @endif
                                @if($sanction->lift_note)
                                    <p class="text-xs text-gray-400 italic mt-0.5 line-clamp-1">
                                        {{ $sanction->lift_note }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($sanction->is_active)
                            @can('believers.edit')
                            <button type="button"
                                onclick="openLiftModal({{ $sanction->id }}, '{{ addslashes($sanction->believer->full_name) }}')"
                                class="inline-flex items-center px-3 py-1.5 text-white text-xs font-medium rounded-md"
                                style="background:#3FA46A">
                                ✓ Lever la sanction
                            </button>
                            @endcan
                        @else
                            <span class="text-xs text-gray-400 italic">Terminée</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucune sanction enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($sanctions->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $sanctions->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ===================== MODAL LEVER LA SANCTION ===================== --}}
<div id="modal-lift"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     role="dialog" aria-modal="true">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeLiftModal()"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            {{-- En-tête --}}
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="background:#3FA46A">
                <h3 class="text-lg font-semibold text-white">
                    ✓ Lever une sanction disciplinaire
                </h3>
                <button type="button" onclick="closeLiftModal()"
                    class="text-white hover:text-gray-200 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            {{-- Corps --}}
            <form id="form-lift" method="POST" action="">
                @csrf @method('PATCH')

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point de lever la sanction disciplinaire de
                        <strong id="lift-believer-name" class="text-gray-900"></strong>.
                        Le fidèle repassera au statut <strong>Actif</strong>.
                    </p>

                    <div class="bg-green-50 border border-green-200 rounded p-3 text-xs text-green-700">
                        ℹ La sanction sera conservée dans l'historique avec la date de levée.
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Observation sur la levée
                            <span class="text-gray-400 text-xs">(optionnel)</span>
                        </label>
                        <textarea name="lift_note" rows="3"
                            placeholder="Ex: Repentance sincère, décision du conseil..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                </div>

                {{-- Pied --}}
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeLiftModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3FA46A">
                        ✓ Confirmer la levée
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function openLiftModal(sanctionId, believerName) {
        document.getElementById('lift-believer-name').textContent = believerName;
        document.getElementById('form-lift').action = '/sanctions/' + sanctionId + '/lift';
        document.getElementById('modal-lift').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeLiftModal() {
        document.getElementById('modal-lift').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-lift').reset();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLiftModal();
    });
</script>

@endsection