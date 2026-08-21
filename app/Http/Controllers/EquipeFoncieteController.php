<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\EquipeFonciere;
use App\Http\Requests\EquipeFoncieteRequest;

class EquipeFoncieteController extends Controller
{
    public function index()
    {
        $members = EquipeFonciere::with('believer.address', 'believer.profession')
            ->active()
            ->orderBy('role')
            ->get();

        $inactiveMembers = EquipeFonciere::with('believer')
            ->where('is_active', false)
            ->orderByDesc('left_at')
            ->get();

        $availableBelievers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->whereDoesntHave('fonciereMembership', fn ($q) => $q->where('is_active', true))
            ->orderBy('lastname')->get();

        return view('fonciere.index', compact('members', 'inactiveMembers', 'availableBelievers'));
    }

    public function store(EquipeFoncieteRequest $request)
    {
        EquipeFonciere::create([
            ...$request->validated(),
            'joined_at' => $request->joined_at ?? now(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('fonciere.index')
            ->with('success', 'Membre attribué à la cellule foncière avec succès.');
    }

    public function deactivate(EquipeFonciere $member)
    {
        $member->update(['is_active' => false, 'left_at' => now()]);

        return redirect()->route('fonciere.index')->with('success', 'Membre désactivé.');
    }

    public function reactivate(EquipeFonciere $member)
    {
        $member->update(['is_active' => true, 'left_at' => null]);

        return redirect()->route('fonciere.index')->with('success', 'Membre réactivé.');
    }

    public function destroy(EquipeFonciere $member)
    {
        $member->delete();

        return redirect()->route('fonciere.index')->with('success', 'Membre retiré définitivement.');
    }
}