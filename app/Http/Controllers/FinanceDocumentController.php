<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\ProjetConstruction;
use App\Models\DossierFoncier;
use App\Http\Requests\StoreFinanceDocumentRequest;
use Illuminate\Support\Str;

class FinanceDocumentController extends Controller
{
    public function create()
    {
        $accounts   = FinanceAccount::all();
        $categories = FinanceCategory::where('is_active', true)->orderBy('type')->orderBy('groupe')->orderBy('name')->get();
        $projects   = ProjetConstruction::orderByDesc('date_lancement')->get();
        $dossiers   = DossierFoncier::orderByDesc('date_debut')->get();

        return view('finances.documents.create', compact('accounts', 'categories', 'projects', 'dossiers'));
    }

    public function store(StoreFinanceDocumentRequest $request)
    {
        $data = $request->validated();

        $pieceJustificativePath = null;
        if ($request->hasFile('piece_justificative')) {
            $pieceJustificativePath = $request->file('piece_justificative')
                ->store('finances/pieces-justificatives', 'public');
        }

        $header = [
            'type'                     => $data['type'],
            'numero_document'          => $data['numero_document'] ?? null,
            'account_id'               => $data['account_id'],
            'date'                     => $data['date'],
            'mode_paiement'            => $data['mode_paiement'] ?? null,
            'numero_cheque'            => $data['numero_cheque'] ?? null,
            'beneficiaire'             => $data['beneficiaire'] ?? null,
            'emetteur'                 => $data['emetteur'] ?? null,
            'verificateur'             => $data['verificateur'] ?? null,
            'recepteur'                => $data['recepteur'] ?? null,
            'piece_justificative_path' => $pieceJustificativePath,
            'fleche_type'              => $data['fleche_type'] ?? null,
            'fleche_id'                => $data['fleche_id'] ?? null,
            'created_by'               => auth()->id(),
        ];

        $total = 0;
        foreach ($data['lines'] as $line) {
            FinanceTransaction::create([
                ...$header,
                'category_id' => $line['category_id'],
                'montant'     => $line['montant'],
                'description' => $line['motif'] ?? null,
            ]);
            $total += $line['montant'];
        }

        $label = $data['type'] === 'recette' ? 'Bordereau de versement' : 'Bon de sortie';

        return redirect()
            ->route('finances.transactions.index')
            ->with('success', "{$label} enregistré avec succès — " . count($data['lines']) . " ligne(s), total " . number_format($total, 0, ',', ' ') . ' FCFA.');
    }
}