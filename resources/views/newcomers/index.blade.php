@extends('layouts.dashboard')

@section('title', 'Nouvelles personnes')
@section('page-title', 'Nouvelles personnes')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        @can('newcomers.create')
        <a href="{{ route('newcomers.create') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvelle personne
        </a>
        @endcan
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase">Année {{ now()->year }}</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['annee'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase">Demeurants</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['demeurant'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-purple-400">
            <p class="text-xs text-gray-500 uppercase">Convertis en fidèles</p>
            <p class="text-2xl font-bold mt-1 text-purple-600">{{ $stats['convertis'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('newcomers.index') }}"
              class="grid grid-cols-2 md:grid-cols-5 gap-3">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom, prénom, téléphone..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="category"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Catégorie</option>
                <option value="passage"          @selected(request('category') === 'passage')>De passage</option>
                <option value="court_sejour"     @selected(request('category') === 'court_sejour')>Court séjour</option>
                <option value="demeurant"        @selected(request('category') === 'demeurant')>Demeurant</option>
                <option value="nouveau_converti" @selected(request('category') === 'nouveau_converti')>Nouveau converti</option>
            </select>

            <select name="gender"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Genre</option>
                <option value="M" @selected(request('gender') === 'M')>Homme</option>
                <option value="F" @selected(request('gender') === 'F')>Femme</option>
            </select>

            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                @endforeach
            </select>

            <div class="col-span-2 md:col-span-5 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="{{ route('newcomers.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    {{ $newcomers->total() }} personne(s) trouvée(s)
                </span>
            </div>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Recommandé</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">1ère visite</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($newcomers as $newcomer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <a href="{{ route('newcomers.show', $newcomer) }}" class="hover:underline"
                           style="color:#3A9BDC">
                            {{ $newcomer->full_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $newcomer->gender_label }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $newcomer->category_color }}">
                            {{ $newcomer->category }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        @if($newcomer->category === 'nouveau_converti')
                            <span class="text-gray-300 text-xs">N/A</span>
                        @elseif($newcomer->is_recommended)
                            <span class="text-green-600 text-xs font-medium">✓ Oui</span>
                            @if($newcomer->recommended_by)
                                <span class="text-gray-400 text-xs ml-1">({{ $newcomer->recommended_by }})</span>
                            @endif
                        @else
                            <span class="text-red-400 text-xs">✗ Non</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $newcomer->first_visit_date?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $newcomer->phone ?? $newcomer->whatsapp ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($newcomer->is_converted)
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                                Fidèle ✓
                            </span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">
                                En suivi
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('newcomers.show', $newcomer) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 hover:bg-cyan-200 text-xs font-medium rounded">
                            Voir
                        </a>
                        @can('newcomers.edit')
                        <a href="{{ route('newcomers.edit', $newcomer) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-medium rounded">
                            Modifier
                        </a>
                        @endcan
                        @if($newcomer->category === 'demeurant' && !$newcomer->is_converted)
                        @can('believers.create')
                        <form method="POST" action="{{ route('newcomers.convert', $newcomer) }}"
                              class="inline"
                              onsubmit="return confirm('Convertir {{ addslashes($newcomer->full_name) }} en fidèle ?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                                style="background:#3FA46A">
                                → Fidèle
                            </button>
                        </form>
                        @endcan
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                        Aucune nouvelle personne trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($newcomers->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $newcomers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection