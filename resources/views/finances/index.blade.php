@extends('layouts.dashboard')

@section('title', 'Finances')
@section('page-title', 'Gestion des finances')

@section('content')
<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">Finances</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('finances.transactions.index') }}"
               class="inline-flex items-center px-4 py-2 
                    text-white text-sm font-medium rounded-md hover:bg-gray-300" style="background:#3A9BDC">
                Transactions
            </a>
            <a href="{{ route('finances.budget.index') }}"
               class="inline-flex items-center px-4 py-2 text-white 
                        text-sm font-medium rounded-md hover:bg-gray-300" style="background:#3FA46A">
                Budget
            </a>
            <a href="{{ route('finances.engagements.index') }}"
               class="inline-flex items-center px-4 py-2 
                    text-white text-sm font-medium rounded-md hover:bg-gray-300" style="background:#C9A635">
                Engagements
            </a>
            <a href="{{ route('finances.periods.index') }}"
               class="inline-flex items-center px-4 py-2 
                    text-white text-sm font-medium rounded-md hover:bg-gray-300" style="background:#7c3aed">
                Périodes
            </a>
            <a href="{{ route('finances.documents.create') }}"
                class="inline-flex items-center px-4 py-2 
                text-white text-sm font-medium rounded-md" style="background:#3A9BDC">
                    + Nouveau bordereau / bon de sortie
            </a>
        </div>
    </div>

    {{-- Période courante --}}
    <div class="bg-white shadow-sm rounded-lg p-4 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 uppercase font-medium">Période comptable en cours</p>
            <p class="text-lg font-bold text-gray-900">{{ $period?->label ?? 'Aucune période active' }}</p>
        </div>
        @if($period)
        <div class="text-right text-xs text-gray-400">
            Du {{ $period->date_debut->format('d/m/Y') }} au {{ $period->date_fin->format('d/m/Y') }}
        </div>
        @endif
    </div>

    {{-- Stats globales --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3FA46A">
            <p class="text-xs text-gray-500 uppercase font-medium">Recettes</p>
            <p class="text-xl font-bold mt-1" style="color:#3FA46A">{{ number_format($stats['total_recettes'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#e53e3e">
            <p class="text-xs text-gray-500 uppercase font-medium">Dépenses</p>
            <p class="text-xl font-bold mt-1" style="color:#e53e3e">{{ number_format($stats['total_depenses'], 0, ',', ' ') }} F</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#3A9BDC">
            <p class="text-xs text-gray-500 uppercase font-medium">Solde de la période</p>
            <p class="text-xl font-bold mt-1" style="color:#3A9BDC">{{ number_format($stats['total_recettes'] - $stats['total_depenses'], 0, ',', ' ') }} F</p>
        </div>
        @if($period)
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Part AEBECI (10%) attendue</p>
            <p class="text-xl font-bold mt-1" style="color:#C9A635">{{ number_format($period->montant_attendu_aebeci, 0, ',', ' ') }} F</p>
        </div>
        @endif
    </div>

    {{-- Comptes --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @if($period)
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4" style="border-color:#C9A635">
            <p class="text-xs text-gray-500 uppercase font-medium">Part Vision (40%) attendue</p>
            <p class="text-xl font-bold mt-1" style="color:#C9A635">{{ number_format($period->montant_attendu_vision, 0, ',', ' ') }} F</p>
        </div>
        @endif
        @foreach($accounts as $account)
        <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 {{ $account->is_over_threshold ? 'animate-pulse' : '' }}"
             style="border-color: {{ $account->is_over_threshold ? '#e53e3e' : '#3A9BDC' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 uppercase font-medium">{{ $account->name }}</p>
                @if($account->is_over_threshold)
                    <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 rounded">⚠ Seuil dépassé</span>
                @endif
            </div>
            <p class="text-lg font-bold mt-1 text-gray-800">{{ number_format($account->balance, 0, ',', ' ') }} F</p>
            @if($account->alert_threshold)
                <p class="text-xs text-gray-400">Plafond : {{ number_format($account->alert_threshold, 0, ',', ' ') }} F</p>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Budget en cours (aperçu) --}}
    @if($budgetLines->isNotEmpty())
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700 uppercase">Suivi budgétaire — {{ $period->label }}</h3>
            <a href="{{ route('finances.budget.index') }}" class="text-xs text-blue-600 hover:underline">Voir tout →</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Alloué</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Réalisé</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">% </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($budgetLines->take(5) as $line)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900">{{ $line->category->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ number_format($line->montant_alloue, 0, ',', ' ') }} F</td>
                    <td class="px-4 py-3 {{ $line->is_over_budget ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                        {{ number_format($line->realise, 0, ',', ' ') }} F
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded {{ $line->is_over_budget ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $line->pourcentage }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Dernières transactions --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700 uppercase">Dernières transactions</h3>
            <a href="{{ route('finances.transactions.index') }}" class="text-xs text-blue-600 hover:underline">Voir tout →</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Catégorie</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Montant</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $t->date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 text-xs rounded {{ $t->type === 'recette' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $t->type === 'recette' ? 'Recette' : 'Dépense' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-900">{{ $t->category->name }}</td>
                    <td class="px-4 py-3 font-medium {{ $t->type === 'recette' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $t->type === 'recette' ? '+' : '−' }}{{ number_format($t->montant, 0, ',', ' ') }} F
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Aucune transaction récente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection