<?php

namespace App\Http\Controllers;

use App\Models\NewComer;
use App\Models\Believer;
use App\Http\Requests\NewcomerRequest;
use Illuminate\Http\Request;

class NewcomerController extends Controller
{
    public function index(Request $request)
    {
        $newcomers = NewComer::with('believer')
            ->search($request->search)
            ->byCategory($request->category)
            ->byGender($request->gender)
            ->byYear($request->year ? (int) $request->year : null)
            ->orderByDesc('first_visit_date')
            ->paginate(20)
            ->withQueryString();

        // Stats pour les compteurs
        $stats = [
            'total'            => NewComer::count(),
            'passage'          => NewComer::where('category', 'passage')->count(),
            'court_sejour'     => NewComer::where('category', 'court_sejour')->count(),
            'demeurant'        => NewComer::where('category', 'demeurant')->count(),
            'nouveau_converti' => NewComer::where('category', 'nouveau_converti')->count(),
            'convertis'        => NewComer::whereNotNull('believer_id')->count(),
            'annee'            => NewComer::whereYear('first_visit_date', now()->year)->count(),
        ];

        $years = NewComer::selectRaw('YEAR(first_visit_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('newcomers.index', compact('newcomers', 'stats', 'years'));
    }

    public function create()
    {
        return view('newcomers.create');
    }

    public function store(NewcomerRequest $request)
    {
        NewComer::create($request->validated());

        return redirect()
            ->route('newcomers.index')
            ->with('success', 'Nouvelle personne enregistrée avec succès.');
    }

    public function show(NewComer $newcomer)
    {
        $newcomer->load('believer');
        return view('newcomers.show', compact('newcomer'));
    }

    public function edit(NewComer $newcomer)
    {
        return view('newcomers.edit', compact('newcomer'));
    }

    public function update(NewcomerRequest $request, NewComer $newcomer)
    {
        $newcomer->update($request->validated());

        return redirect()
            ->route('newcomers.show', $newcomer)
            ->with('success', 'Informations mises à jour avec succès.');
    }

    public function destroy(NewComer $newcomer)
    {
        $newcomer->delete();

        return redirect()
            ->route('newcomers.index')
            ->with('success', 'Enregistrement supprimé.');
    }

    // -------------------------------------------------------
    // Conversion d'un demeurant en fidèle
    // -------------------------------------------------------
    public function convert(Request $request, Newcomer $newcomer)
    {
        // Vérifications
        if ($newcomer->category !== 'demeurant') {
            return redirect()->back()
                ->with('error', 'Seuls les demeurants peuvent être convertis en fidèles.');
        }

        if ($newcomer->believer_id) {
            return redirect()->back()
                ->with('error', 'Cette personne est déjà enregistrée comme fidèle.');
        }

        // Créer le fidèle à partir des données du newcomer
        $believer = Believer::create([
            'lastname'       => $newcomer->lastname,
            'firstname'      => $newcomer->firstname,
            'gender'         => $newcomer->gender,
            'birth_date'     => $newcomer->birth_date,
            'status'         => 'actif',
            'is_active'      => true,
        ]);

        // Créer l'adresse si téléphone disponible
        if ($newcomer->phone || $newcomer->whatsapp) {
            $believer->address()->create([
                'phone'    => $newcomer->phone,
                'whatsapp' => $newcomer->whatsapp,
            ]);
        }

        // Créer les infos église
        $believer->churchInformation()->create([
            'arrival_year' => now()->year,
        ]);

        // Lier le newcomer au fidèle créé
        $newcomer->update([
            'believer_id'              => $believer->id,
            'converted_to_believer_at' => now(),
        ]);

        return redirect()
            ->route('believers.show', $believer)
            ->with('success', "{$newcomer->full_name} a été enregistré(e) comme fidèle. Complétez son profil.");
    }
}