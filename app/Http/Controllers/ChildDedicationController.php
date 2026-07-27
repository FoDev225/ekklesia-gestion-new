<?php

namespace App\Http\Controllers;

use App\Models\ChildDedication;
use App\Models\Believer;
use App\Models\Church;
use App\Http\Requests\ChildDedicationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ChildDedicationController extends Controller
{
    public function index(Request $request)
    {
        $dedications = ChildDedication::with(['father', 'mother'])
            ->search($request->search)
            ->byGender($request->gender)
            ->byYear($request->year ? (int) $request->year : null)
            ->orderByDesc('dedication_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'    => ChildDedication::count(),
            'masculin' => ChildDedication::where('gender', 'Masculin')->count(),
            'feminin'  => ChildDedication::where('gender', 'Féminin')->count(),
            'annee'    => ChildDedication::whereYear('dedication_date', now()->year)->count(),
        ];

        $years = ChildDedication::selectRaw('YEAR(dedication_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('dedication.index', compact('dedications', 'stats', 'years'));
    }

    public function create()
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname', 'gender',
                   'birth_date', 'marital_status']);

        return view('dedication.create', compact('believers'));
    }

    public function store(ChildDedicationRequest $request)
    {
        ChildDedication::create($request->validated());

        return redirect()
            ->route('dedication.index')
            ->with('success', 'Présentation d\'enfant enregistrée avec succès.');
    }

    public function show(ChildDedication $dedication)
    {
        $dedication->load([
            'father.address',
            'father.churchInformation',
            'mother.address',
            'mother.churchInformation',
        ]);
        return view('dedication.show', compact('dedication'));
    }

    public function edit(ChildDedication $dedication)
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname', 'gender', 'birth_date', 'marital_status']);

        return view('dedication.edit', compact('dedication', 'believers'));
    }

    public function update(ChildDedicationRequest $request, ChildDedication $dedication)
    {
        $dedication->update($request->validated());

        return redirect()
            ->route('dedication.show', $dedication)
            ->with('success', 'Enregistrement mis à jour.');
    }

    public function destroy(ChildDedication $dedication)
    {
        $dedication->delete();

        return redirect()
            ->route('dedication.index')
            ->with('success', 'Enregistrement supprimé.');
    }

    public function downloadFiche(ChildDedication $dedication)
    {
        $dedication->load([
            'father.address',
            'father.churchInformation',
            'mother.address',
            'mother.churchInformation',
        ]);

        $church = Church::instance();

        $pdf = Pdf::loadView('dedication.fiche-pdf', compact('dedication', 'church'))
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

        $filename = 'presentation-enfant-' . strtolower($dedication->child_lastname) . '-' . now()->format('Y') . '.pdf';

        return $pdf->download($filename);
    }
}