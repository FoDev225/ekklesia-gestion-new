<?php

namespace App\Http\Controllers;

use App\Models\MariageRegister;
use App\Models\Believer;
use App\Models\Church;
use App\Http\Requests\MariageRegisterRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MariageRegisterController extends Controller
{
    public function index(Request $request)
    {
        $registers = MariageRegister::with(['groom', 'bride'])
            ->search($request->search)
            ->byYear($request->year ? (int) $request->year : null)
            ->orderByDesc('religious_marriage_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'  => MariageRegister::count(),
            'annee'  => MariageRegister::whereYear('religious_marriage_date', now()->year)->count(),
        ];

        $years = MariageRegister::selectRaw('YEAR(religious_marriage_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('mariage.index', compact('registers', 'stats', 'years'));
    }

    public function create()
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname', 'gender']);

        return view('mariage.create', compact('believers'));
    }

    public function store(MariageRegisterRequest $request)
    {
        $data = $request->validated();

        // Upload photos
        if ($request->hasFile('groom_photo')) {
            $data['groom_photo'] = $request->file('groom_photo')->store('mariage/photos', 'public');
        }
        if ($request->hasFile('bride_photo')) {
            $data['bride_photo'] = $request->file('bride_photo')->store('mariage/photos', 'public');
        }

        MariageRegister::create($data);

        return redirect()
            ->route('mariage.index')
            ->with('success', 'Mariage enregistré avec succès.');
    }

    public function show(MariageRegister $mariage)
    {
        $mariage->load(['groom.address', 'groom.churchInformation', 'bride.address', 'bride.churchInformation']);
        return view('mariage.show', compact('mariage'));
    }

    public function edit(MariageRegister $mariage)
    {
        $believers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname', 'gender']);

        return view('mariage.edit', compact('mariage', 'believers'));
    }

    public function update(MariageRegisterRequest $request, MariageRegister $mariage)
    {
        $data = $request->validated();

        if ($request->hasFile('groom_photo')) {
            if ($mariage->groom_photo) Storage::disk('public')->delete($mariage->groom_photo);
            $data['groom_photo'] = $request->file('groom_photo')->store('mariage/photos', 'public');
        }
        if ($request->hasFile('bride_photo')) {
            if ($mariage->bride_photo) Storage::disk('public')->delete($mariage->bride_photo);
            $data['bride_photo'] = $request->file('bride_photo')->store('mariage/photos', 'public');
        }

        $mariage->update($data);

        return redirect()
            ->route('mariage.show', $mariage)
            ->with('success', 'Enregistrement mis à jour.');
    }

    public function destroy(MariageRegister $mariage)
    {
        if ($mariage->groom_photo) Storage::disk('public')->delete($mariage->groom_photo);
        if ($mariage->bride_photo) Storage::disk('public')->delete($mariage->bride_photo);
        $mariage->delete();

        return redirect()
            ->route('mariage.index')
            ->with('success', 'Enregistrement supprimé.');
    }

    public function downloadFiche(MariageRegister $mariage)
    {
        $mariage->load(['groom.address', 'groom.churchInformation', 'bride.address', 'bride.churchInformation']);
        $church = Church::instance();

        $pdf = Pdf::loadView('mariage.fiche-pdf', compact('mariage', 'church'))
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

        $filename = 'mariage-' . now()->format('Y') . '-' . $mariage->id . '.pdf';

        return $pdf->download($filename);
    }
}