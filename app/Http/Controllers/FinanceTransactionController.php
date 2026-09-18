<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\ProjetConstruction;
use App\Models\DossierFoncier;
use App\Http\Requests\StoreFinanceTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = FinanceTransaction::with('category', 'account', 'period')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
            ->orderByDesc('date')
            ->paginate(30)
            ->withQueryString();

        $accounts   = FinanceAccount::all();
        $categories = FinanceCategory::orderBy('type')->orderBy('groupe')->orderBy('name')->get();

        return view('finances.transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function create()
    {
        $accounts   = FinanceAccount::all();
        $categories = FinanceCategory::where('is_active', true)->orderBy('type')->orderBy('groupe')->orderBy('name')->get();
        $projects   = ProjetConstruction::orderByDesc('date_lancement')->get();
        $dossiers   = DossierFoncier::orderByDesc('date_debut')->get();

        return view('finances.transactions.create', compact('accounts', 'categories', 'projects', 'dossiers'));
    }

    public function store(StoreFinanceTransactionRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('piece_justificative')) {
            $data['piece_justificative_path'] = $request->file('piece_justificative')
                ->store('finances/pieces-justificatives', 'public');
        }

        $data['created_by'] = auth()->id();

        FinanceTransaction::create($data);

        return redirect()
            ->route('finances.transactions.index')
            ->with('success', 'Transaction enregistrée avec succès.');
    }

    public function destroy(FinanceTransaction $transaction)
    {
        if ($transaction->piece_justificative_path) {
            Storage::disk('public')->delete($transaction->piece_justificative_path);
        }

        $transaction->delete();

        return redirect()
            ->route('finances.transactions.index')
            ->with('success', 'Transaction supprimée.');
    }
}