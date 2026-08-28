@extends('layouts.dashboard')

@section('title', 'Registre funéraire')
@section('page-title', 'Registre funéraire')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Registre funéraire</span>
        </div>
        
        @can('believers.create')
        <a href="{{ route('funeral.create') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvel enregistrement
        </a>
        @endcan
    </div>

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#1a2e4a">
            <p class="text-xs text-gray-500 uppercase font-medium">Total</p>
            <p class="text-2xl font-bold mt-1" style="color:#1a2e4a">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Père</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['pere'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Mère</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['mere'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Enfant</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['enfant'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-gray-400">
            <p class="text-xs text-gray-500 uppercase font-medium">Cette année</p>
            <p class="text-2xl font-bold mt-1 text-gray-600">{{ $stats['annee'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('funeral.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom défunt ou fidèle..."
                class="col-span-2 md:col-span-1 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">

            <select name="relationship"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Lien de parenté</option>
                <option value="pere"   @selected(request('relationship') === 'pere')>Père</option>
                <option value="mere"   @selected(request('relationship') === 'mere')>Mère</option>
                <option value="enfant" @selected(request('relationship') === 'enfant')>Enfant biologique</option>
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
                <a href="{{ route('funeral.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Réinitialiser
                </a>
                <span class="ml-auto text-sm text-gray-500 self-center">
                    {{ $registers->total() }} enregistrement(s)
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
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Défunt</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Lien</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date décès</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Date funérailles</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Assistance église</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($registers as $register)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('believers.show', $register->believer) }}"
                           class="font-medium hover:underline" style="color:#3A9BDC">
                            {{ $register->believer->full_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $register->deceased_full_name }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $colors = ['pere' => 'bg-blue-100 text-blue-700', 'mere' => 'bg-yellow-100 text-yellow-700', 'enfant' => 'bg-green-100 text-green-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$register->family_relationship] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $register->family_relationship_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $register->death_date?->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $register->funeral_date?->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        {{ $register->loincloths_number }} pagne(s)<br>
                        <span style="color:#3FA46A">{{ $register->amount_paid }} FCFA</span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('funeral.show', $register) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 hover:bg-cyan-200 text-xs font-medium rounded">
                            Voir
                        </a>
                        @can('believers.edit')
                        <a href="{{ route('funeral.edit', $register) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-medium rounded">
                            Modifier
                        </a>
                        @endcan
                        <a href="{{ route('funeral.fiche', $register) }}"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        Aucun enregistrement funéraire.
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