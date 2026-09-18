<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;

class FinanceController extends Controller
{
    public function index()
    {
        $accounts = FinanceAccount::all();
        $period   = FinancePeriod::current();

        $budgetLines = $period ? $period->budgetLines()->with('category')->get() : collect();

        $recentTransactions = FinanceTransaction::with('category', 'account')
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        $stats = [
            'total_recettes' => $period ? $period->transactions()->where('type', 'recette')->sum('montant') : 0,
            'total_depenses' => $period ? $period->transactions()->where('type', 'depense')->sum('montant') : 0,
        ];

        return view('finances.index', compact('accounts', 'period', 'budgetLines', 'recentTransactions', 'stats'));
    }
}