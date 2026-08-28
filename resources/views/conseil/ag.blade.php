@extends('layouts.dashboard')

@section('title', 'Rapport d\'Assemblée Générale')
@section('page-title', 'Rapport d\'Assemblée Générale')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('conseil.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Comité</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Rapport d'Assemblée Générale</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Durée</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Ordre du jour</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Rapport</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($ag as $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $a->ag_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $a->ag_time?->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded
                                        {{ $a->ag_type === 'extraordinaire' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $a->type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs max-w-sm truncate">{{ $a->ag_objective }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($a->rapport_path)
                                        <a href="{{ $a->rapport_url }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <button type="button"
                                        onclick="openDeleteAgModal('{{ route('conseil.ag.destroy', $a) }}', '{{ $a->ag_date->format('d/m/Y') }}')"
                                        class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucune réunion enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($ag->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">{{ $ag->links() }}</div>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Ajouter une Nouvelle AG</h3>
                <form action="{{ route('conseil.ag.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="ag_type" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="ordinaire">Ordinaire</option>
                            <option value="extraordinaire">Extraordinaire</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="ag_date" required value="{{ old('ag_date', now()->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Heure</label>
                        <input type="time" name="ag_time" value="{{ old('ag_time') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Ordre du jour <span class="text-red-500">*</span></label>
                        <textarea name="ag_objective" rows="4" required
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('ag_objective') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Rapport (PDF)</label>
                        <input type="file" name="rapport_path" accept=".pdf" class="w-full text-sm">
                        <p class="text-xs text-gray-400 mt-1">Max 10 Mo.</p>
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3FA46A">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Modal : Supprimer une réunion --}}
<div id="deleteAgModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Supprimer le rapport de l'AG</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Êtes-vous sûr de vouloir supprimer le rapport de l'AG du
            <span id="deleteAgDate" class="font-semibold text-gray-900"></span> ?
            Le document associé sera également supprimé. Cette action est irréversible.
        </p>
        <form id="deleteAgForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#dc2626">
                Supprimer
            </button>
            <button type="button" onclick="closeDeleteAgModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteAgModal(actionUrl, date) {
        document.getElementById('deleteAgDate').textContent = date;
        document.getElementById('deleteAgForm').action = actionUrl;
        document.getElementById('deleteAgModal').classList.remove('hidden');
    }
    function closeDeleteAgModal() {
        document.getElementById('deleteAgModal').classList.add('hidden');
    }
</script>
@endsection