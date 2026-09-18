@extends('layouts.dashboard')

@section('title', 'Budget')
@section('page-title', 'Suivi budgétaire')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Finances</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Budget</span>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="period_id" onchange="this.form.submit()"
                class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach($periods as $p)
                    <option value="{{ $p->id }}" @selected($period && $period->id === $p->id)>{{ $p->label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if(!$period)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
        Aucune période comptable disponible. <a href="{{ route('finances.periods.index') }}" class="underline">Créez-en une</a>.
    </div>
    @else

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Total alloué</p>
            <p class="text-xl font-bold mt-1" style="color:#3A9BDC">{{ number_format($totalAlloue, 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#e53e3e">
            <p class="text-xs text-gray-500 uppercase font-medium">Total réalisé</p>
            <p class="text-xl font-bold mt-1" style="color:#e53e3e">{{ number_format($totalRealise, 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Écart</p>
            <p class="text-xl font-bold mt-1" style="color:#3FA46A">{{ number_format($totalAlloue - $totalRealise, 0, ',', ' ') }} F</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Catégorie</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Alloué</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">Réalisé</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase whitespace-nowrap">%</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($budgetLines as $line)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-900">{{ $line->category->name }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ number_format($line->montant_alloue, 0, ',', ' ') }} F</td>
                            <td class="px-4 py-3 whitespace-nowrap {{ $line->is_over_budget ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ number_format($line->realise, 0, ',', ' ') }} F
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs rounded {{ $line->is_over_budget ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $line->pourcentage }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <button type="button"
                                    onclick="openDeleteBudgetLineModal('{{ route('finances.budget.lines.destroy', $line) }}', @js($line->category->name))"
                                    class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded">
                                    Retirer
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucune ligne budgétaire pour cette période.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Ajouter une ligne</h3>
                <form action="{{ route('finances.budget.lines.store', $period) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Catégorie <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Sélectionner —</option>
                            @foreach($availableCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Montant alloué <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="montant_alloue" required
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                        Ajouter
                    </button>
                </form>
            </div>
        </div>

    </div>
    @endif
</div>

{{-- Modal : Retirer une ligne budgétaire --}}
<div id="deleteBudgetLineModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <span class="text-red-600 text-lg">⚠️</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Retirer la ligne budgétaire</h3>
        </div>
        <p class="text-sm text-gray-600 mb-5">
            Retirer la ligne <span id="deleteBudgetLineName" class="font-semibold text-gray-900"></span> du budget ?
        </p>
        <form id="deleteBudgetLineForm" method="POST" class="flex gap-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md" style="background:#dc2626">
                Retirer
            </button>
            <button type="button" onclick="closeDeleteBudgetLineModal()"
                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">
                Annuler
            </button>
        </form>
    </div>
</div>

<script>
    function openDeleteBudgetLineModal(actionUrl, name) {
        document.getElementById('deleteBudgetLineName').textContent = name;
        document.getElementById('deleteBudgetLineForm').action = actionUrl;
        document.getElementById('deleteBudgetLineModal').classList.remove('hidden');
    }
    function closeDeleteBudgetLineModal() {
        document.getElementById('deleteBudgetLineModal').classList.add('hidden');
    }
</script>
@endsection