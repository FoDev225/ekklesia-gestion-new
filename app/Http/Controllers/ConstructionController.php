<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\EquipeConstruction;
use App\Http\Requests\ConstructionRequest;

class ConstructionController extends Controller
{
    public function index()
    {
        $members = EquipeConstruction::with('believer.address', 'believer.profession')
            ->active()
            ->orderBy('role')
            ->get();

        $inactiveMembers = EquipeConstruction::with('believer')
            ->where('is_active', false)
            ->orderByDesc('left_at')
            ->get();

        $availableBelievers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->whereDoesntHave('constructionMembership', fn ($q) => $q->where('is_active', true))
            ->orderBy('lastname')->get();

        return view('construction.index', compact('members', 'inactiveMembers', 'availableBelievers'));
    }

    public function store(ConstructionRequest $request)
    {
        EquipeConstruction::create([
            ...$request->validated(),
            'joined_at' => $request->joined_at ?? now(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('construction.index')
            ->with('success', 'Membre attribué à l\'équipe de construction avec succès.');
    }

    public function deactivate(EquipeConstruction $member)
    {
        $member->update([
            'is_active' => false,
            'left_at'   => now(),
        ]);

        return redirect()
            ->route('construction.index')
            ->with('success', 'Membre désactivé.');
    }

    public function reactivate(EquipeConstruction $member)
    {
        $member->update([
            'is_active' => true,
            'left_at'   => null,
        ]);

        return redirect()
            ->route('construction.index')
            ->with('success', 'Membre réactivé.');
    }

    public function destroy(EquipeConstruction $member)
    {
        $member->delete();

        return redirect()
            ->route('construction.index')
            ->with('success', 'Membre retiré définitivement.');
    }
}