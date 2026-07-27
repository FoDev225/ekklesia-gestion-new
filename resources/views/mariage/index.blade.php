@extends('layouts.dashboard')

@section('title', 'Registre des mariages')
@section('page-title', 'Registre des mariages')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Registre des mariages</span>
        </div>
        @can('believers.create')
        <a href="{{ route('mariage.create') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouveau mariage
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Total mariages</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['annee'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('mariage.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom époux ou épouse..."
                class="col-span-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="year"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Année</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="{{ route('mariage.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
            <span class="col-span-2 md:col-span-4 text-sm text-gray-500 text-right">
                {{ $registers->total() }} mariage(s)
            </span>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Époux</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Épouse</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Mariage civil</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Mariage religieux</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Officiant</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registers as $mariage)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $mariage->groom_display_name }}
                        @if($mariage->groom_id)
                            <span class="text-xs ml-1 px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded">fidèle</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $mariage->bride_display_name }}
                        @if($mariage->bride_id)
                            <span class="text-xs ml-1 px-1.5 py-0.5 bg-pink-100 text-pink-600 rounded">fidèle</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $mariage->civil_marriage_date?->format('d/m/Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $mariage->civil_marriage_place }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $mariage->religious_marriage_date?->format('d/m/Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $mariage->religious_marriage_place }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $mariage->officiant }}</td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('mariage.show', $mariage) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        @can('believers.edit')
                        <a href="{{ route('mariage.edit', $mariage) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        @endcan
                        <a href="{{ route('mariage.fiche', $mariage) }}"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Aucun mariage enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($registers->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $registers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection