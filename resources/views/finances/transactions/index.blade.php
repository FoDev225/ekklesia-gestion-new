@extends('layouts.dashboard')

@section('title', 'Transactions')
@section('page-title', 'Transactions financières')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Finances</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Transactions</span>
        </div>
        <div class="flex gap-2">
            @hasanyrole('admin|tresorier')
            <a href="{{ route('finances.transactions.create') }}"
            class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                + Nouvelle transaction
            </a>
            
            <a href="{{ route('finances.documents.create') }}"
                class="inline-flex items-center px-4 py-2 
                text-white text-sm font-medium rounded-md" style="background:#C9A635">
                    + Nouveau bordereau / bon de sortie
            </a>
            @endhasanyrole
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <select name="type" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous types</option>
                <option value="recette" @selected(request('type')==='recette')>Recette</option>
                <option value="depense" @selected(request('type')==='depense')>Dépense</option>
            </select>
            <select name="category_id" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="account_id" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous comptes</option>
                @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" @selected(request('account_id') == $acc->id)>{{ $acc->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-white text-sm rounded-md" style="background:#3A9BDC">Filtrer</button>
                <a href="{{ route('finances.transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Catégorie</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Compte</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Montant</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Pièce</th>
                        @hasanyrole('tresorier')
                        <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Action</th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-xs rounded {{ $t->type === 'recette' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $t->type === 'recette' ? 'Recette' : 'Dépense' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-900">{{ $t->category->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $t->account->name }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs max-w-xs truncate">{{ $t->description ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium whitespace-nowrap {{ $t->type === 'recette' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $t->type === 'recette' ? '+' : '−' }}{{ number_format($t->montant, 0, ',', ' ') }} F
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if($t->piece_justificative_path)
                                <a href="{{ $t->piece_url }}" target="_blank" class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded">Voir</a>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        @hasanyrole('tresorier')
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <button type="button"
                                onclick="openDeleteTransactionModal('{{ route('finances.transactions.destroy', $t) }}', @js($t->category->name . ' - ' . number_format($t->montant, 0, ',', ' ') . ' F'))"
                                class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">
                                Supprimer
                            </button>
                        </td>
                        @endhasanyrole
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Aucune transaction trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">{{ $transactions->links() }}</div>
        @endif
    </div>

</div>

{{-- Modal : Supprimer une transaction --}}
<div id="deleteTransactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Supprimer la transaction</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Êtes-vous sûr de vouloir supprimer <span id="deleteTransactionName" class="font-semibold text-gray-900"></span> ?
            Cette action est irréversible.
        </p>
        <form id="deleteTransactionForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md" style="background:#dc2626">
                Supprimer
            </button>
            <button type="button" onclick="closeDeleteTransactionModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteTransactionModal(actionUrl, label) {
        document.getElementById('deleteTransactionName').textContent = label;
        document.getElementById('deleteTransactionForm').action = actionUrl;
        document.getElementById('deleteTransactionModal').classList.remove('hidden');
    }
    function closeDeleteTransactionModal() {
        document.getElementById('deleteTransactionModal').classList.add('hidden');
    }
</script>
@endsection