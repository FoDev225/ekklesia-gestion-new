<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conseil;
use App\Models\Believer;
use App\Http\Requests\ConseilRequest;

class ConseilController extends Controller
{
    public function index()
    {
        $members = Conseil::with('believer.address', 'believer.profession')
            ->active()
            ->orderBy('role')
            ->get();

        $inactiveMembers = Conseil::with('believer')
            ->where('is_active', false)
            ->orderByDesc('left_at')
            ->get();

        $availableBelievers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->whereDoesntHave('conseilMembership', fn ($q) => $q->where('is_active', true))
            ->orderBy('lastname')->get();

        return view('conseil.index', compact('members', 'inactiveMembers', 'availableBelievers'));
    }

    public function store(ConseilRequest $request)
    {
        Conseil::create([
            ...$request->validated(),
            'joined_at' => $request->joined_at ?? now(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('conseil.index')
            ->with('success', 'Membre attribué au conseil avec succès.');
    }

    public function deactivate(Conseil $member)
    {
        $member->update([
            'is_active' => false,
            'left_at'   => now(),
        ]);

        return redirect()
            ->route('conseil.index')
            ->with('success', 'Membre désactivé du conseil.');
    }

    public function reactivate(Conseil $member)
    {
        $member->update([
            'is_active' => true,
            'left_at'   => null,
        ]);

        return redirect()
            ->route('conseil.index')
            ->with('success', 'Membre réactivé.');
    }

    public function destroy(Conseil $member)
    {
        $member->delete();

        return redirect()
            ->route('conseil.index')
            ->with('success', 'Membre retiré définitivement du conseil.');
    }
}
