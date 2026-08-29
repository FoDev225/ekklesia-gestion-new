@extends('layouts.dashboard')

@section('title', 'Gestion des fidèles')
@section('page-title', 'Gestion des fidèles')

@section('content')
<div class="space-y-4">

    {{-- Barre de navigation --}}
    <div class="flex items-center justify-between">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
            ← Retour au dashboard
        </a>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Export PDF --}}
            <a href="{{ route('believers.export.pdf', request()->only(['gender','marital_status','age_group','team_id','status'])) }}"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#e53e3e" title="Exporter la liste en PDF">
                📄 PDF
            </a>
 
            {{-- Export Excel --}}
            <a href="{{ route('believers.export.excel', request()->only(['gender','marital_status','age_group','team_id','status'])) }}"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#3FA46A" title="Exporter la liste en Excel">
                📊 Excel
            </a>
 
            {{-- Import Excel --}}
            @can('believers.create')
            <a href="{{ route('believers.import.form') }}"
               class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
               style="background:#C9A635" title="Importer une liste Excel">
                ⬆ Import
            </a>

            <a href="{{ route('believers.photo-import.form') }}"
                class="inline-flex items-center gap-1 px-3 py-2 text-white text-xs font-medium rounded-md"
                style="background:#7c3aed">
                    📷 Photos
            </a>
            @endcan
 
            {{-- Nouveau fidèle --}}
            @can('believers.create')
            <a href="{{ route('believers.create') }}"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#3A9BDC">
                + Nouveau fidèle
            </a>
            @endcan
        </div>
    </div>

            @if(session('import_result'))
                @php $result = session('import_result'); @endphp

                @if($result['imported'] > 0)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-3">
                    ✅ {{ $result['imported'] }} fidèle(s) importé(s) avec succès.
                    @if($result['skipped'] > 0)
                        {{ $result['skipped'] }} ligne(s) ignorée(s).
                    @endif
                </div>
                @endif

                @if(count($result['errors']) > 0)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-3">
                    <p class="font-semibold mb-2">⚠ Détail des lignes ignorées :</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($result['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            @endif

            {{-- Filtres --}}
            <div class="bg-white shadow-sm rounded-lg p-4">
                <form method="GET" action="{{ route('believers.index') }}" class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nom, prénom, CNI..."
                        class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />

                    <select name="gender" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Genre</option>
                        <option value="M" @selected(request('gender') === 'M')>Homme</option>
                        <option value="F" @selected(request('gender') === 'F')>Femme</option>
                    </select>

                    <select name="marital_status" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Situation maritale</option>
                        <option value="Célibataire" @selected(request('marital_status') === 'Célibataire')>Célibataire</option>
                        <option value="Marié(e)"       @selected(request('marital_status') === 'Marié(e)')>Marié(e)</option>
                        <option value="Veuf(ve)"        @selected(request('marital_status') === 'Veuf(ve)')>Veuf(ve)</option>
                        <option value="Divorcé"     @selected(request('marital_status') === 'Divorcé')>Divorcé</option>
                    </select>

                    <select name="age_group" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tranche d'âge</option>
                        <option value="nourrisson"   @selected(request('age_group') === 'nourrisson')>Nourrisson (0-2)</option>
                        <option value="pre_scolaire" @selected(request('age_group') === 'pre_scolaire')>Pré-scolaire (3-4)</option>
                        <option value="ecodim"       @selected(request('age_group') === 'ecodim')>ECODIM (5-18)</option>
                        <option value="jeunes"       @selected(request('age_group') === 'jeunes')>Jeunes (19-40)</option>
                        <option value="adultes"      @selected(request('age_group') === 'adultes')>Adultes (41+)</option>
                    </select>

                    <select name="team_id" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Équipe</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" @selected(request('team_id') == $team->id)>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="col-span-2 md:col-span-5 flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                            Filtrer
                        </button>
                        <a href="{{ route('believers.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                            Réinitialiser
                        </a>
                        <span class="ml-auto text-sm text-gray-500 self-center">
                            {{ $believers->total() }} fidèle(s) trouvé(s)
                        </span>
                    </div>
                </form>
            </div>

            {{-- Tableau --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tranche d'âge</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Situation</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Sanction</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($believers as $i => $believer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">
                                    @if($believer->profile_picture)
                                        <img src="{{ $believer->profile_picture_url }}" alt="{{ $believer->full_name }}"
                                            onclick="openPhotoModal('{{ $believer->profile_picture_url }}', '{{ addslashes($believer->full_name) }}')"
                                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 flex-shrink-0 cursor-pointer hover:opacity-80 hover:ring-2 hover:ring-indigo-400 transition">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm font-bold flex-shrink-0">
                                            {{ strtoupper(substr($believer->firstname, 0, 1) . substr($believer->lastname, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <a href="{{ route('believers.show', $believer) }}" class="hover:text-indigo-600">
                                        {{ $believer->full_name }}
                                    </a>
                                    <div class="text-xs text-gray-400 font-normal font-mono">
                                        {{ $believer->register_number ?? '—' }}
                                    </div>
                                    @if($believer->is_sanctioned ?? false)
                                        <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs rounded">Sanction</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $believer->gender_label }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $believer->age_group_color }}">
                                        {{ $believer->age_group }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $believer->marital_status }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $believer->address?->whatsapp ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    @if($believer->sanctions()->where('is_active', true)->exists())
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded">
                                            Sous sanction
                                        </span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                                    <a href="{{ route('believers.show', $believer) }}"
                                    class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                                        Voir
                                    </a>
                                    @can('believers.edit')
                                    <a href="{{ route('believers.edit', $believer) }}"
                                    class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                                        Modifier
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                    Aucun fidèle trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
            @if($believers->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $believers->links() }}
            </div>
            @endif
        </div>

    </div>

    {{-- Modal : Photo en grand format --}}
    <div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
        onclick="closePhotoModal()">
        <div class="relative max-w-md w-full" onclick="event.stopPropagation()">
            <button type="button" onclick="closePhotoModal()"
                class="absolute -top-10 right-0 text-white text-2xl hover:text-gray-300">
                ✕
            </button>
            <div class="aspect-square w-full rounded-lg shadow-2xl overflow-hidden bg-gray-800">
                <img id="photoModalImg" src="" alt=""
                    class="w-full h-full object-cover">
            </div>
            <p id="photoModalName" class="text-white text-center mt-3 text-sm font-medium"></p>
        </div>
    </div>

    <script>
        function openPhotoModal(src, name) {
            document.getElementById('photoModalImg').src = src;
            document.getElementById('photoModalName').textContent = name;
            document.getElementById('photoModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closePhotoModal();
        });
    </script>

</div>

@endsection