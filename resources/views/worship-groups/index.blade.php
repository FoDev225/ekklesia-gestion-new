@extends('layouts.dashboard')

@section('title', 'Groupes de louange')
@section('page-title', 'Groupes de louange')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Groupes de louange</span>
        </div>
        @can('worship-groups.create')
        <a href="{{ route('worship-groups.create') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#3A9BDC">
            + Nouveau groupe de louange
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Total groupes</p>
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

    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" action="{{ route('worship-groups.index') }}"
              class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Nom du groupe de louange..."
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
                <a href="{{ route('worship-groups.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
            <span class="col-span-2 md:col-span-4 text-sm text-gray-500 text-right">
                {{ $worshipGroups->total() }} groupe(s)
            </span>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Responsable</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membres</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($worshipGroups as $worshipGroup)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $worshipGroup->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ Str::limit($worshipGroup->description, 40) ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $worshipGroup->leader->full_name ?? $worshipGroup->leader->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded">
                            {{ $worshipGroup->believers_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap space-x-1">
                        <a href="{{ route('worship-groups.show', $worshipGroup) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs font-medium rounded">
                            Voir
                        </a>
                        @can('worship-groups.edit')
                        <a href="{{ route('worship-groups.edit', $worshipGroup) }}"
                           class="inline-flex items-center px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                            Modifier
                        </a>
                        @endcan
                        @can('worship-groups.delete')
                        <button type="button"
                            onclick="openDeleteModal('{{ route('worship-groups.destroy', $worshipGroup) }}', @js($worshipGroup->name))"
                            class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                            Supprimer
                        </button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        Aucun groupe de louange enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($worshipGroups->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $worshipGroups->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Modal : Confirmation de suppression --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Supprimer le groupe de louange</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Êtes-vous sûr de vouloir supprimer
            <span id="deleteGroupName" class="font-semibold text-gray-900"></span> ?
            Cette action est <strong>définitive</strong> et irréversible.
        </p>
        <form id="deleteForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#dc2626">
                Supprimer
            </button>
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl, name) {
        document.getElementById('deleteGroupName').textContent = name;
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection