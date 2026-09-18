<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\ProjetConstruction;
use App\Models\DossierFoncier;
use App\Models\FinanceAccount;
use App\Models\FinanceEngagement;
use App\Http\Requests\StoreFinanceEngagementRequest;
use App\Http\Requests\StoreFinanceEngagementPaymentRequest;

class FinanceEngagementController extends Controller
{
    public function index()
    {
        $engagements = FinanceEngagement::with('believer', 'payments')
            ->orderByDesc('date_engagement')
            ->paginate(20);

        $stats = [
            'total_engage' => FinanceEngagement::sum('montant_engage'),
            'total_paye'   => \App\Models\FinanceEngagementPayment::sum('montant'),
        ];
        $stats['total_reste'] = $stats['total_engage'] - $stats['total_paye'];

        $believers = Believer::whereNotIn('status', ['parti', 'decede'])->orderBy('lastname')->get();
        $projects  = ProjetConstruction::orderByDesc('date_lancement')->get();
        $dossiers  = DossierFoncier::orderByDesc('date_debut')->get();

        return view('finances.engagements.index', compact('engagements', 'stats', 'believers', 'projects', 'dossiers'));
    }

    public function store(StoreFinanceEngagementRequest $request)
    {
        FinanceEngagement::create($request->validated());

        return redirect()
            ->route('finances.engagements.index')
            ->with('success', 'Engagement enregistré avec succès.');
    }

    public function show(FinanceEngagement $engagement)
    {
        $engagement->load('believer', 'payments.account');
        $accounts = FinanceAccount::all();

        return view('finances.engagements.show', compact('engagement', 'accounts'));
    }

    public function storePayment(StoreFinanceEngagementPaymentRequest $request, FinanceEngagement $engagement)
    {
        if ($request->montant > $engagement->reste_a_payer) {
            return redirect()->back()
                ->withErrors(['montant' => "Le montant dépasse le reste à payer ({$engagement->reste_a_payer} FCFA)."])
                ->withInput();
        }

        $engagement->payments()->create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('finances.engagements.show', $engagement)
            ->with('success', 'Versement enregistré avec succès.');
    }

    public function destroy(FinanceEngagement $engagement)
    {
        $engagement->delete();

        return redirect()
            ->route('finances.engagements.index')
            ->with('success', 'Engagement supprimé.');
    }
}