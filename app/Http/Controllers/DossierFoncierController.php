<?php

namespace App\Http\Controllers;

use App\Models\DossierFoncier;
use App\Http\Requests\DossierFoncierRequest;
use Illuminate\Support\Facades\Storage;

class DossierFoncierController extends Controller
{
    public function index()
    {
        $dossiers = DossierFoncier::orderByDesc('date_debut')->paginate(15);

        $stats = [
            'total'        => DossierFoncier::count(),
            'acquis'       => DossierFoncier::whereIn('statut', ['acquis', 'titre_obtenu'])->count(),
            'en_cours'     => DossierFoncier::whereIn('statut', ['recherche', 'negociation'])->count(),
            'cout_total'   => DossierFoncier::sum('cout'),
            'surface_totale' => DossierFoncier::whereIn('statut', ['acquis', 'titre_obtenu'])->sum('superficie'),
        ];

        return view('fonciere.dossiers', compact('dossiers', 'stats'));
    }

    public function store(DossierFoncierRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('dossiers-fonciers', 'public');
        }

        DossierFoncier::create($data);

        return redirect()->route('dossiers')->with('success', 'Dossier foncier enregistré avec succès.');
    }

    public function update(DossierFoncierRequest $request, DossierFoncier $dossier)
    {
        $data = $request->validated();

        if ($request->hasFile('document')) {
            if ($dossier->document_path) {
                Storage::disk('public')->delete($dossier->document_path);
            }
            $data['document_path'] = $request->file('document')->store('dossiers-fonciers', 'public');
        }

        $dossier->update($data);

        return redirect()->route('dossiers')->with('success', 'Dossier mis à jour.');
    }

    public function destroy(DossierFoncier $dossier)
    {
        if ($dossier->document_path) {
            Storage::disk('public')->delete($dossier->document_path);
        }

        $dossier->delete();

        return redirect()->route('dossiers')->with('success', 'Dossier supprimé.');
    }
}