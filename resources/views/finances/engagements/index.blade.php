@extends('layouts.dashboard')

@section('title', 'Engagements des fidèles')
@section('page-title', 'Engagements des fidèles')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Finances</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Engagements</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Total engagé</p>
            <p class="text-xl font-bold mt-1" style="color:#3A9BDC">{{ number_format($stats['total_engage'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Total payé</p>
            <p class="text-xl font-bold mt-1" style="color:#3FA46A">{{ number_format($stats['total_paye'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#e53e3e">
            <p class="text-xs text-gray-500 uppercase font-medium">Reste à payer (global)</p>
            <p class="text-xl font-bold mt-1" style="color:#e53e3e">{{ number_format($stats['total_reste'], 0, ',', ' ') }} F</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Fidèle</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Motif</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Engagé</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Payé</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Reste</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($engagements as $engagement)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('finances.engagements.show', $engagement) }}'">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $engagement->believer->full_name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $engagement->motif }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ number_format($engagement->montant_engage, 0, ',', ' ') }} F</td>
                                <td class="px-4 py-3 text-green-600 whitespace-nowrap">{{ number_format($engagement->montant_paye, 0, ',', ' ') }} F</td>
                                <td class="px-4 py-3 text-red-600 whitespace-nowrap">{{ number_format($engagement->reste_a_payer, 0, ',', ' ') }} F</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if($engagement->is_solde)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Soldé</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded">En cours</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Aucun engagement enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($engagements->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">{{ $engagements->links() }}</div>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Nouvel engagement</h3>
                <form action="{{ route('finances.engagements.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fidèle <span class="text-red-500">*</span></label>
                        <select name="believer_id" required class="w-full border-gray-300 rounded-md text-sm js-believer-select">
                            <option value="">— Sélectionner —</option>
                            @foreach($believers as $believer)
                                <option value="{{ $believer->id }}">{{ $believer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Motif <span class="text-red-500">*</span></label>
                        <input type="text" name="motif" required placeholder="Ex: Construction" value="{{ old('motif') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Lié à un projet (optionnel)</label>
                        <select name="fleche_type" id="fleche_type" onchange="toggleFlecheId()"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Aucun —</option>
                            <option value="construction_project">Projet de construction</option>
                            <option value="dossier_foncier">Dossier foncier</option>
                        </select>
                    </div>

                    <div id="fleche_construction_field" class="hidden">
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Projet</label>
                        <select name="fleche_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Sélectionner —</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="fleche_foncier_field" class="hidden">
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Dossier</label>
                        <select name="fleche_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Sélectionner —</option>
                            @foreach($dossiers as $dossier)
                                <option value="{{ $dossier->id }}">{{ $dossier->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Montant engagé <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="montant_engage" required value="{{ old('montant_engage') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date d'engagement <span class="text-red-500">*</span></label>
                        <input type="date" name="date_engagement" required value="{{ old('date_engagement', now()->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Enregistrer l'engagement
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleFlecheId() {
        const type = document.getElementById('fleche_type').value;
        document.getElementById('fleche_construction_field').classList.toggle('hidden', type !== 'construction_project');
        document.getElementById('fleche_foncier_field').classList.toggle('hidden', type !== 'dossier_foncier');
    }
</script>
@endsection