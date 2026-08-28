@extends('layouts.dashboard')

@section('title', 'Départs & Décès')
@section('page-title', 'Départs & Décès')

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
            <span class="text-sm text-gray-700 font-medium">Départs & Décès</span>
        </div>
    </div>

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total enregistrés</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Départs</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['departs'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Réintégration possible</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-gray-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Décès</p>
            <p class="text-2xl font-bold mt-1 text-gray-500">{{ $stats['deces'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Définitif</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['annee'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('departures.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom, prénom..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="type"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Type</option>
                <option value="depart" @selected(request('type') === 'depart')>🚶 Départ</option>
                <option value="deces"  @selected(request('type') === 'deces')>🕊 Décès</option>
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
                <a href="{{ route('departures.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    {{ $departures->total() }} enregistrement(s)
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
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Enregistré par</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($departures as $departure)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('believers.show', $departure->believer) }}"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            {{ $departure->believer->full_name }}
                        </a>
                        <p class="text-xs text-gray-400">
                            {{ $departure->believer->gender_label }}
                            @if($departure->believer->age)
                                · {{ $departure->believer->age }} ans
                            @endif
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        @if($departure->type === 'depart')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                🚶 Départ
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                🕊 Décès
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $departure->departure_date?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $departure->destination ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2">{{ $departure->reason ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $departure->recorded_by ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($departure->type === 'depart')
                            @can('believers.edit')
                            <button type="button"
                                onclick="openReinstateModal({{ $departure->believer->id }}, '{{ addslashes($departure->believer->full_name) }}')"
                                class="inline-flex items-center px-3 py-1.5 text-white text-xs font-medium rounded-md"
                                style="background:#3A9BDC">
                                ↩ Réintégrer
                            </button>
                            @endcan
                        @else
                            <span class="text-xs text-gray-400 italic">Définitif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucun départ enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($departures->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $departures->links() }}
        </div>
        @endif
    </div>

</div>
{{-- ===================== MODAL RÉINTÉGRATION ===================== --}}
<div id="modal-reinstate"
     class="fixed inset-0 z-50 hidden overflow-y-auto"
     role="dialog" aria-modal="true">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeReinstateModal()"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10">

            {{-- En-tête --}}
            <div class="flex items-center justify-between px-6 py-4 border-b"
                 style="background:#3A9BDC">
                <h3 class="text-lg font-semibold text-white">
                    ↩ Réintégration d'un fidèle
                </h3>
                <button type="button" onclick="closeReinstateModal()"
                    class="text-white hover:text-gray-200 text-xl font-bold leading-none">
                    &times;
                </button>
            </div>

            {{-- Corps --}}
            <form id="form-reinstate" method="POST" action="">
                @csrf @method('PATCH')

                <div class="px-6 py-5 space-y-4">

                    <p class="text-sm text-gray-600">
                        Vous êtes sur le point de réintégrer
                        <strong id="reinstate-believer-name" class="text-gray-900"></strong>
                        dans la communauté. Le fidèle repassera au statut <strong>Actif</strong>
                        et réapparaîtra dans la liste des fidèles.
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded p-3 text-xs text-blue-700">
                        ℹ L'enregistrement de départ sera supprimé de cette liste.
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Motif de réintégration
                            <span class="text-gray-400 text-xs">(optionnel)</span>
                        </label>
                        <textarea name="reinstate_note" rows="3"
                            placeholder="Ex: Retour définitif dans la ville, réconciliation..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                </div>

                {{-- Pied --}}
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeReinstateModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        ✓ Confirmer la réintégration
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function openReinstateModal(believerId, believerName) {
        document.getElementById('reinstate-believer-name').textContent = believerName;
        document.getElementById('form-reinstate').action = '/believers/' + believerId + '/reinstate';
        document.getElementById('modal-reinstate').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeReinstateModal() {
        document.getElementById('modal-reinstate').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('form-reinstate').reset();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeReinstateModal();
    });
</script>

@endsection