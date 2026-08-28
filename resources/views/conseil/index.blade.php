@extends('layouts.dashboard')

@section('title', 'Conseil de l\'église')
@section('page-title', 'Conseil de gestion')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Conseil</span>
        </div>
        <a href="{{ route('conseil.ag') }}"
           class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
           style="background:#1a2e4a">
            📋 Réunions du conseil
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2 space-y-4">

            {{-- Conseil de Gestion --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200" style="background:#3A9BDC">
                    <h3 class="text-sm font-semibold text-white uppercase">Conseil de l'église</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Fonction (conseil)</th>
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
                                <button type="button"
                                    onclick="openDeactivateModal('{{ route('conseil.deactivate', $member) }}', @js($member->believer->full_name))"
                                    class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded">
                                    Désactiver
                                </button>
                                <button type="button"
                                    onclick="openRemoveModal('{{ route('conseil.destroy', $member) }}', @js($member->believer->full_name))"
                                    class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">
                                    Retirer
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Aucun membre.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Membres inactifs --}}
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
                                <form action="{{ route('conseil.reactivate', $member) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">
                                        Réactiver
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>

        {{-- Formulaire d'attribution --}}
        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Attribuer un membre</h3>
                <form action="{{ route('conseil.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Fidèle <span class="text-red-500">*</span></label>
                        <select name="believer_id" required
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 js-believer-select">
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
                            @foreach(\App\Models\Conseil::ROLES_GESTION as $r)
                                <option value="{{ $r }}" @selected(old('role') === $r)>{{ $r }}</option>
                            @endforeach
                        </select>
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

{{-- Modal : Désactiver un membre --}}
<div id="deactivateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <span class="text-yellow-600 text-lg">⏸</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Désactiver le membre</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Voulez-vous désactiver
            <span id="deactivateMemberName" class="font-semibold text-gray-900"></span>
            du conseil ? Il restera visible dans les anciens membres et pourra être réactivé plus tard.
        </p>
        <form id="deactivateForm" method="POST" class="flex gap-2">
            @csrf
            @method('PATCH')
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#C9A635">
                Désactiver
            </button>
            <button type="button" onclick="closeDeactivateModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

{{-- Modal : Retirer définitivement un membre --}}
<div id="removeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Retirer définitivement</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Êtes-vous sûr de vouloir retirer définitivement
            <span id="removeMemberName" class="font-semibold text-gray-900"></span>
            du conseil ? Cette action est irréversible.
        </p>
        <form id="removeForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#dc2626">
                Retirer
            </button>
            <button type="button" onclick="closeRemoveModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeactivateModal(actionUrl, name) {
        document.getElementById('deactivateMemberName').textContent = name;
        document.getElementById('deactivateForm').action = actionUrl;
        document.getElementById('deactivateModal').classList.remove('hidden');
    }
    function closeDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
    }

    function openRemoveModal(actionUrl, name) {
        document.getElementById('removeMemberName').textContent = name;
        document.getElementById('removeForm').action = actionUrl;
        document.getElementById('removeModal').classList.remove('hidden');
    }
    function closeRemoveModal() {
        document.getElementById('removeModal').classList.add('hidden');
    }
</script>
@endsection