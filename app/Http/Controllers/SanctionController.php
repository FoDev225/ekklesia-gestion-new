<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\Sanction;
use Illuminate\Http\Request;

class SanctionController extends Controller
{
    public function index(Request $request)
    {
        $sanctions = Sanction::with('believer')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('believer', function ($q2) use ($request) {
                    $q2->where('lastname', 'like', "%{$request->search}%")
                       ->orWhere('firstname', 'like', "%{$request->search}%");
                });
            })
            ->when($request->status !== null, function ($q) use ($request) {
                $q->where('is_active', $request->status === 'active');
            })
            ->when($request->year, fn($q) => $q->whereYear('start_date', $request->year))
            ->orderByDesc('start_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'    => Sanction::count(),
            'actives'  => Sanction::where('is_active', true)->count(),
            'levees'   => Sanction::where('is_active', false)->count(),
            'annee'    => Sanction::whereYear('start_date', now()->year)->count(),
        ];

        $years = Sanction::selectRaw('YEAR(start_date) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('believers.sanctions.sanction', compact('sanctions', 'stats', 'years'));
    }

    public function lift(Request $request, Sanction $sanction)
    {
        $request->validate([
            'lift_note' => 'nullable|string|max:1000',
        ]);

        $sanction->update([
            'is_active' => false,
            'lifted_at' => now(),
            'lift_note' => $request->lift_note,
        ]);

        // Remettre le fidèle en actif si plus aucune sanction active
        $believer = $sanction->believer;
        if (!$believer->sanctions()->where('is_active', true)->exists()) {
            $believer->update([
                'status'    => 'actif',
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('sanctions.index')
            ->with('success', "La sanction de {$believer->full_name} a été levée.");
    }
}