<?php

namespace App\Http\Controllers;

use App\Models\FuneralRegister;
use App\Models\Believer;
use App\Models\Church;
use App\Http\Requests\FuneralRegisterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FuneralRegisterController extends Controller
{
    public function index(Request $request)
    {
        $registers = FuneralRegister::with('believer')
            ->search($request->search)
            ->byRelationship($request->relationship)
            ->byYear($request->year ? (int) $request->year : null)
            ->orderByDesc('funeral_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'  => FuneralRegister::count(),
            'pere'   => FuneralRegister::where('family_relationship', 'pere')->count(),
            'mere'   => FuneralRegister::where('family_relationship', 'mere')->count(),
            'enfant' => FuneralRegister::where('family_relationship', 'enfant')->count(),
            'annee'  => FuneralRegister::whereYear('funeral_date', now()->year)->count(),
        ];

        $years = FuneralRegister::selectRaw('YEAR(funeral_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('funeral.index', compact('registers', 'stats', 'years'));
    }

    public function create()
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname']);

        return view('funeral.create', compact('believers'));
    }

    public function store(FuneralRegisterRequest $request)
    {
        // Vérifier la limite de 3 entrées par fidèle (père, mère, enfant)
        $existing = FuneralRegister::where('believer_id', $request->believer_id)
            ->where('family_relationship', $request->family_relationship)
            ->exists();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['family_relationship' => "Ce fidèle a déjà un enregistrement pour ce lien de parenté ({$request->family_relationship})."]);
        }

        FuneralRegister::create($request->validated());

        return redirect()
            ->route('funeral.index')
            ->with('success', 'Enregistrement funéraire ajouté avec succès.');
    }

    public function show(FuneralRegister $funeral)
    {
        $funeral->load('believer.address');
        return view('funeral.show', compact('funeral'));
    }

    public function edit(FuneralRegister $funeral)
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname']);

        return view('funeral.edit', compact('funeral', 'believers'));
    }

    public function update(FuneralRegisterRequest $request, FuneralRegister $funeral)
    {
        // Vérifier doublon uniquement si le lien de parenté a changé
        if ($request->family_relationship !== $funeral->family_relationship) {
            $existing = FuneralRegister::where('believer_id', $request->believer_id)
                ->where('family_relationship', $request->family_relationship)
                ->where('id', '!=', $funeral->id)
                ->exists();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['family_relationship' => "Ce fidèle a déjà un enregistrement pour ce lien de parenté."]);
            }
        }

        $funeral->update($request->validated());

        return redirect()
            ->route('funeral.show', $funeral)
            ->with('success', 'Enregistrement mis à jour avec succès.');
    }

    public function destroy(FuneralRegister $funeral)
    {
        $funeral->delete();

        return redirect()
            ->route('funeral.index')
            ->with('success', 'Enregistrement supprimé.');
    }

    // -------------------------------------------------------
    // Export PDF — fiche funéraire
    // -------------------------------------------------------
    public function downloadFiche(FuneralRegister $funeral)
    {
        $funeral->load('believer.address', 'believer.churchInformation');
        $church = Church::instance();

        $pdf = Pdf::loadView('funeral.fichepdf', compact('funeral', 'church'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'defaultFont'     => 'Arial',
                'isRemoteEnabled' => true,
                'margin_top'      => 10,
                'margin_bottom'   => 10,
                'margin_left'     => 12,
                'margin_right'    => 12,
                'dpi'             => 150,
            ]);

        $filename = 'registre-funeraire-' . strtolower($funeral->parent_lastname) . '-' . now()->format('Y') . '.pdf';

        return $pdf->download($filename);
    }
}