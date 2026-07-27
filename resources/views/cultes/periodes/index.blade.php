@extends('layouts.dashboard')
@section('title', 'Périodes des cultes')
@section('page-title', 'Gestion des cultes')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-medium text-gray-700">Périodes</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('cultes.acteurs') }}"
               class="px-3 py-2 text-white text-sm rounded-md" style="background:#C9A635">
                👥 Acteurs de culte
            </a>
            <a href="{{ route('cultes.periodes.create') }}"
               class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">
                + Nouvelle période
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Thème général</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Du</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Au</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Cultes</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($periodes as $periode)
                <tr class="hover:bg-gray-50 {{ $periode->is_active ? 'bg-blue-50' : '' }}">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $periode->name }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs max-w-xs">{{ $periode->general_theme ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $periode->start_date?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $periode->end_date?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold" style="color:#3A9BDC">{{ $periode->services_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($periode->is_archive)
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs rounded-full">Archivée</span>
                        @elseif($periode->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">● Active</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">En attente</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('cultes.services', $periode) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Cultes
                        </a>
                        <a href="{{ route('cultes.programme.pdf', $periode) }}"
                           class="inline-flex items-center px-2.5 py-1 text-white text-xs font-medium rounded"
                           style="background:#1a2e4a">
                            📄 PDF
                        </a>
                        @if(!$periode->is_active && !$periode->is_archive)
                        <form method="POST" action="{{ route('cultes.periode.activate', $periode) }}" class="inline">
                            @csrf
                            <button class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                Activer
                            </button>
                        </form>
                        @endif
                        @if(!$periode->is_archive)
                        <form method="POST" action="{{ route('cultes.periode.archive', $periode) }}" class="inline"
                              onsubmit="return confirm('Archiver cette période ?')">
                            @csrf
                            <button class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-500 text-xs rounded">
                                Archiver
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('cultes.periodes.edit', $periode) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Aucune période enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($periodes->hasPages())
        <div class="px-4 py-3 border-t">{{ $periodes->links() }}</div>
        @endif
    </div>
</div>
@endsection