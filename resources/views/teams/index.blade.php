@extends('layouts.dashboard')

@section('title', 'Gestion des équipes')
@section('page-title', 'Gestion des équipes')

@section('content')
<div class="space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Équipes</span>
        </div>
        @can('teams.create')
        <a href="{{ route('teams.create') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouvelle équipe
        </a>
        @endcan
    </div>

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Total équipes</p>
            <p class="text-2xl font-bold mt-1" style="color:#C9A635">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Total membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $stats['total_members'] }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Sans responsable</p>
            <p class="text-2xl font-bold mt-1" style="color:#3A9BDC">{{ $stats['sans_responsable'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('teams.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom ou slug de l'équipe..."
                class="col-span-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="leader_id"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Responsable</option>
                @foreach($leaders as $leader)
                    <option value="{{ $leader->id }}" @selected(request('leader_id') == $leader->id)>
                        {{ $leader->full_name ?? $leader->name }}
                    </option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                    Filtrer
                </button>
                <a href="{{ route('teams.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
            <span class="col-span-2 md:col-span-4 text-sm text-gray-500 text-right">
                {{ $teams->total() }} équipe(s)
            </span>
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Responsable</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membres</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($teams as $team)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $team->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $team->slug }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $team->leader->full_name ?? $team->leader->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded">
                            {{ $team->believers_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('teams.show', $team) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        @can('update', $team)
                        <a href="{{ route('teams.edit', $team) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        @endcan
                        @can('delete', $team)
                        <form action="{{ route('teams.destroy', $team) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cette équipe ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                                Supprimer
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        Aucune équipe enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($teams->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $teams->links() }}
        </div>
        @endif
    </div>

</div>
@endsection