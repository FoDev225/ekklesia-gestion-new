<?php

namespace App\Http\Controllers;

use App\Models\FinanceBudgetLine;
use App\Models\FinanceCategory;
use App\Models\FinancePeriod;
use App\Http\Requests\StoreFinanceBudgetLineRequest;
use Illuminate\Http\Request;

class FinanceBudgetController extends Controller
{
    public function index(Request $request)
    {
        $periods = FinancePeriod::orderByDesc('annee')->orderByDesc('type')->get();
        $period  = $request->period_id
            ? FinancePeriod::find($request->period_id)
            : FinancePeriod::current();

        $budgetLines = $period
            ? $period->budgetLines()->with('category')->get()
            : collect();

        $availableCategories = $period
            ? FinanceCategory::depense()
                ->whereNotIn('id', $budgetLines->pluck('category_id'))
                ->orderBy('groupe')->orderBy('name')->get()
            : collect();

        $totalAlloue = $budgetLines->sum('montant_alloue');
        $totalRealise = $budgetLines->sum('realise');

        return view('finances.budget.index', compact(
            'periods', 'period', 'budgetLines', 'availableCategories', 'totalAlloue', 'totalRealise'
        ));
    }

    public function storeLine(StoreFinanceBudgetLineRequest $request, FinancePeriod $period)
    {
        FinanceBudgetLine::create([
            'period_id'      => $period->id,
            'category_id'    => $request->category_id,
            'montant_alloue' => $request->montant_alloue,
        ]);

        return redirect()
            ->route('finances.budget.index', ['period_id' => $period->id])
            ->with('success', 'Ligne budgétaire ajoutée.');
    }

    public function destroyLine(FinanceBudgetLine $line)
    {
        $periodId = $line->period_id;
        $line->delete();

        return redirect()
            ->route('finances.budget.index', ['period_id' => $periodId])
            ->with('success', 'Ligne budgétaire supprimée.');
    }
}