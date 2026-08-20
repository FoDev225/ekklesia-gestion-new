@extends('layouts.dashboard')

@section('title', 'Équipe de construction')
@section('page-title', 'Équipe de construction')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Équipe de construction</span>
        </div>
        <a href="{{ route('construction.projects') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            🏗️ Projets de construction
        </a>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2 space-y-4">

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200" style="background:#3A9BDC">
                    <h3 class="text-sm font-semibold text-white uppercase">Membres de l'équipe</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Fonction</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Profession</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <a href="{{ route('believers.show', $member->believer) }}" class="hover:text-indigo-600">
                                    {{ $member->believer->full_name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                    {{ $member->role }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $member->believer->profession?->profession ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $member->believer->address?->whatsapp ?? '—' }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <form action="{{ route('construction.deactivate', $member) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Désactiver ce membre ?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">Désactiver</button>
                                </form>
                                <form action="{{ route('construction.destroy', $member) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Retirer définitivement ce membre ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">Retirer</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Aucun membre.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inactiveMembers->isNotEmpty())
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-100">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase">Anciens membres</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($inactiveMembers as $member)
                        <tr class="hover:bg-gray-50 text-gray-500">
                            <td class="px-4 py-3">{{ $member->believer->full_name }}</td>
                            <td class="px-4 py-3">{{ $member->role }}</td>
                            <td class="px-4 py-3">Sorti le {{ $member->left_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('construction.reactivate', $member) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Réactiver</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Attribuer un membre</h3>
                <form action="{{ route('construction.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fidèle <span class="text-red-500">*</span></label>
                        <select name="believer_id" required
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Sélectionner —</option>
                            @foreach($availableBelievers as $believer)
                                <option value="{{ $believer->id }}">{{ $believer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fonction <span class="text-red-500">*</span></label>
                        <select name="role" required
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Sélectionner —</option>
                            @foreach(\App\Models\EquipeConstruction::ROLES as $r)
                                <option value="{{ $r }}" @selected(old('role') === $r)>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Contact</label>
                        <input type="text" name="contact" value="{{ old('contact') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date d'entrée</label>
                        <input type="date" name="joined_at" value="{{ old('joined_at', now()->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3A9BDC">
                        Attribuer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection