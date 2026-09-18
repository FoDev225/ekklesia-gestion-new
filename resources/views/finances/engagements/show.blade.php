@extends('layouts.dashboard')

@section('title', 'Engagement — ' . $engagement->believer->full_name)
@section('page-title', 'Détail de l\'engagement')

@section('content')
<div class="space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('finances.engagements.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Engagements</a>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-lg font-bold text-gray-900">{{ $engagement->believer->full_name }}</h2>
        <p class="text-sm text-gray-500 mb-4">{{ $engagement->motif }} @if($engagement->fleche_label) — {{ $engagement->fleche_label }} @endif</p>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-400 uppercase">Engagé</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($engagement->montant_engage, 0, ',', ' ') }} F</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Payé</p>
                <p class="text-lg font-bold text-green-600">{{ number_format($engagement->montant_paye, 0, ',', ' ') }} F</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Reste à payer</p>
                <p class="text-lg font-bold text-red-600">{{ number_format($engagement->reste_a_payer, 0, ',', ' ') }} F</p>
            </div>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
            <div class="h-2 rounded-full" style="width: {{ min(100, $engagement->montant_engage > 0 ? round($engagement->montant_paye / $engagement->montant_engage * 100) : 0) }}%; background:#3FA46A"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="md:col-span-2">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase">Historique des versements</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">N° Reçu</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Compte</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Encaisseur</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($engagement->payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $payment->numero_recu ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $payment->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ number_format($payment->montant, 0, ',', ' ') }} F</td>
                            <td class="px-4 py-3 text-gray-600">{{ $payment->account?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $payment->encaisseur ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun versement enregistré.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:col-span-1">
            @if(!$engagement->is_solde)
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 uppercase mb-3">Enregistrer un versement</h3>
                <form action="{{ route('finances.engagements.payments.store', $engagement) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">N° Reçu</label>
                        <input type="text" name="numero_recu" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Montant <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="montant" required max="{{ $engagement->reste_a_payer }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">Reste : {{ number_format($engagement->reste_a_payer, 0, ',', ' ') }} F</p>
                        @error('montant') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" required value="{{ now()->format('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Compte <span class="text-red-500">*</span></label>
                        <select name="account_id" required class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Encaisseur</label>
                        <input type="text" name="encaisseur" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-white text-sm font-medium rounded-md"
                        style="background:#3FA46A">
                        Enregistrer le versement
                    </button>
                </form>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center text-sm text-green-700">
                ✓ Engagement entièrement soldé
            </div>
            @endif
        </div>

    </div>
</div>
@endsection