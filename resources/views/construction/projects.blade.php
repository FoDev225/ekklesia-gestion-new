@extends('layouts.dashboard')

@section('title', 'Projets de construction')
@section('page-title', 'Projets de construction')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('construction.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Équipe</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Projets</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Total projets</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Réalisés</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['realises'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">En cours</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['en_cours'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Coût total</p>
            <p class="text-lg font-bold mt-1" style="color:#1a2e4a">{{ number_format($stats['cout_total'], 0, ',', ' ') }} F</p>
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
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Lancement</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Fin</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Coût</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Statut</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Rapport</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($projects as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $project->libelle }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $project->date_lancement->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $project->date_fin?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ $project->cout ? number_format($project->cout, 0, ',', ' ') . ' F' : '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded
                                        {{ $project->status === 'realise' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $project->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if($project->rapport_path)
                                        <a href="{{ $project->rapport_url }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <form action="{{ route('construction.projects.destroy', $project) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Supprimer ce projet ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucun projet enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($projects->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">{{ $projects->links() }}</div>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Nouveau projet</h3>
                <form action="{{ route('construction.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Libellé <span class="text-red-500">*</span></label>
                        <input type="text" name="libelle" required value="{{ old('libelle') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date de lancement <span class="text-red-500">*</span></label>
                        <input type="date" name="date_lancement" required value="{{ old('date_lancement', now()->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date de fin</label>
                        <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Coût (FCFA)</label>
                        <input type="number" step="0.01" name="cout" value="{{ old('cout') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Statut <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="en_cours">En cours</option>
                            <option value="realise">Réalisé</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Rapport (PDF)</label>
                        <input type="file" name="rapport" accept=".pdf" class="w-full text-sm">
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
@endsection