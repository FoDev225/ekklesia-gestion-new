<?php

namespace App\Http\Controllers;

use App\Models\FinancePeriod;
use App\Http\Requests\StoreFinancePeriodRequest;

class FinancePeriodController extends Controller
{
    public function index()
    {
        $periods = FinancePeriod::orderByDesc('annee')->orderByDesc('type')->get();

        return view('finances.periods.index', compact('periods'));
    }

    public function store(StoreFinancePeriodRequest $request)
    {
        FinancePeriod::create($request->validated());

        return redirect()
            ->route('finances.periods.index')
            ->with('success', 'Période comptable créée.');
    }
}