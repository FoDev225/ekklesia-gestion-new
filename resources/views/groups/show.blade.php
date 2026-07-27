@extends('layouts.dashboard')

@section('title', $group->name)
@section('page-title', 'Détail du groupe')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('groups.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Groupes</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $group->name }}</span>
        </div>
        <div class="flex gap-2">
            @can('groups.edit')
            <a href="{{ route('groups.edit', $group) }}"
               class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
               style="background:#C9A635">
                Modifier
            </a>
            @endcan
            <a href="{{ route('groups.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    {{-- En-tête groupe --}}
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">{{ $group->name }}</h2>
        </div>
        <a href="{{ route('groups.members-pdf', $group) }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            📄 Télécharger liste des membres (PDF)
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Membres</p>
            <p class="text-2xl font-bold mt-1" style="color:#3FA46A">{{ $group->believers->count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Responsable</p>
            <p class="text-sm font-bold mt-2" style="color:#3A9BDC">
                {{ $group->leader->full_name ?? $group->leader->name ?? '—' }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Infos + membres --}}
        <div class="md:col-span-2 space-y-4">

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Informations</h3>
                <p class="text-sm text-gray-600">
                    <span class="font-medium text-gray-800">Description :</span>
                    {{ $group->description ?: '—' }}
                </p>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Membres du groupe</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Membre depuis</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($group->believers as $believer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $believer->full_name ?? $believer->name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $believer->pivot->joined_at
                                    ? \Carbon\Carbon::parse($believer->pivot->joined_at)->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <form action="{{ route('groups.believers.destroy', [$group, $believer]) }}" method="POST"
                                      onsubmit="return confirm('Retirer ce membre du groupe ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                                        Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                Aucun membre pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Affecter un fidèle --}}
        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Affecter un fidèle</h3>
                <form action="{{ route('groups.believers.store', $group) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="believer_id" class="block text-xs font-medium text-gray-500 uppercase mb-1">
                            Fidèle
                        </label>
                        <select name="believer_id" id="believer_id"
                                class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('believer_id') border-red-500 @enderror"
                                required>
                            <option value="">— Sélectionner —</option>
                            @foreach ($availableBelievers as $believer)
                                <option value="{{ $believer->id }}">
                                    {{ $believer->full_name ?? $believer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('believer_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="joined_at" class="block text-xs font-medium text-gray-500 uppercase mb-1">
                            Date d'adhésion
                        </label>
                        <input type="date" name="joined_at" id="joined_at"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ old('joined_at', now()->format('Y-m-d')) }}">
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Affecter
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection