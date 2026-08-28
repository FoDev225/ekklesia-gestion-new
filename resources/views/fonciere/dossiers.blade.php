@extends('layouts.dashboard')

@section('title', 'Dossiers fonciers')
@section('page-title', 'Dossiers fonciers')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('fonciere.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Cellule foncière</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Dossiers</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Total dossiers</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Acquis / Titrés</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['acquis'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">En cours</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['en_cours'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Coût total</p>
            <p class="text-sm font-bold mt-1" style="color:#1a2e4a">{{ number_format($stats['cout_total'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#7c3aed">
            <p class="text-xs text-gray-500 uppercase font-medium">Surface acquise</p>
            <p class="text-sm font-bold mt-1" style="color:#7c3aed">{{ number_format($stats['surface_totale'], 0, ',', ' ') }} m²</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Libellé</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Localisation</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Superficie</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Coût</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Statut</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Document</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dossiers as $dossier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $dossier->libelle }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $dossier->localisation ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $dossier->superficie ? number_format($dossier->superficie, 0, ',', ' ') . ' m²' : '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $dossier->cout ? number_format($dossier->cout, 0, ',', ' ') . ' F' : '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded {{ $dossier->statut_color }}">
                                        {{ $dossier->statut_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if($dossier->document_path)
                                        <a href="{{ $dossier->document_url }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <button type="button"
                                        onclick="openDeleteDossierModal('{{ route('dossiers.destroy', $dossier) }}', @js($dossier->libelle))"
                                        class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun dossier enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($dossiers->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">{{ $dossiers->links() }}</div>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Nouveau dossier</h3>
                <form action="{{ route('dossiers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Libellé <span class="text-red-500">*</span></label>
                        <input type="text" name="libelle" required value="{{ old('libelle') }}"
                            placeholder="Ex: Terrain Yopougon Extension"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Localisation</label>
                        <input type="text" name="localisation" value="{{ old('localisation') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Superficie (m²)</label>
                        <input type="number" step="0.01" name="superficie" value="{{ old('superficie') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Statut <span class="text-red-500">*</span></label>
                        <select name="statut" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach(\App\Models\DossierFoncier::STATUTS as $key => $label)
                                <option value="{{ $key }}" @selected(old('statut') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Coût (FCFA)</label>
                        <input type="number" step="0.01" name="cout" value="{{ old('cout') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date de début <span class="text-red-500">*</span></label>
                        <input type="date" name="date_debut" required value="{{ old('date_debut', now()->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date d'acquisition</label>
                        <input type="date" name="date_acquisition" value="{{ old('date_acquisition') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Document (PDF)</label>
                        <input type="file" name="document" accept=".pdf" class="w-full text-sm">
                        <p class="text-xs text-gray-400 mt-1">Titre, contrat, ou rapport. Max 10 Mo.</p>
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

{{-- Modal : Supprimer un dossier --}}
<div id="deleteDossierModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Supprimer le dossier</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Êtes-vous sûr de vouloir supprimer le dossier
            <span id="deleteDossierName" class="font-semibold text-gray-900"></span> ?
            Le document associé sera également supprimé. Cette action est irréversible.
        </p>
        <form id="deleteDossierForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#dc2626">
                Supprimer
            </button>
            <button type="button" onclick="closeDeleteDossierModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteDossierModal(actionUrl, libelle) {
        document.getElementById('deleteDossierName').textContent = libelle;
        document.getElementById('deleteDossierForm').action = actionUrl;
        document.getElementById('deleteDossierModal').classList.remove('hidden');
    }
    function closeDeleteDossierModal() {
        document.getElementById('deleteDossierModal').classList.add('hidden');
    }
</script>
@endsection