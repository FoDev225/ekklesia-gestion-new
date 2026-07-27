<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\Departure;
use Illuminate\Http\Request;

class DepartureController extends Controller
{
    public function index(Request $request)
    {
        $departures = Departure::with('believer')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('believer', function ($q2) use ($request) {
                    $q2->where('lastname', 'like', "%{$request->search}%")
                       ->orWhere('firstname', 'like', "%{$request->search}%");
                });
            })
            ->when($request->year, fn($q) => $q->whereYear('departure_date', $request->year))
            ->orderByDesc('departure_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'   => Departure::count(),
            'departs' => Departure::where('type', 'depart')->count(),
            'deces'   => Departure::where('type', 'deces')->count(),
            'annee'   => Departure::whereYear('departure_date', now()->year)->count(),
        ];

        $years = Departure::selectRaw('YEAR(departure_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('believers.departures.departure', compact('departures', 'stats', 'years'));
    }

    public function reinstate(Request $request, Believer $believer)
    {
        if ($believer->status === 'decede') {
            return redirect()->back()
                ->with('error', 'Un fidèle décédé ne peut pas être réintégré.');
        }

        $departure = $believer->departures()->latest()->first();

        if ($departure) {
            // Stocker la note dans le motif avant suppression
            if ($request->filled('reinstate_note')) {
                $departure->update([
                    'reason' => ($departure->reason ? $departure->reason . ' | ' : '')
                               . 'Réintégration : ' . $request->reinstate_note,
                ]);
            }
            $departure->delete();
        }

        $believer->update([
            'status'    => 'actif',
            'is_active' => true,
        ]);

        return redirect()
            ->route('departures.index')
            ->with('success', "{$believer->full_name} a été réintégré(e) dans la communauté.");
    }
}