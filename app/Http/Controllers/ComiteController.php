<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Believer;
use App\Models\Comite;
use App\Services\ActivityLogger;
use App\Http\Requests\ComiteMembersRequest;

class ComiteController extends Controller
{
    public function index()
    {
        $members = Comite::with('believer.address', 'believer.profession')
            ->active()
            ->orderBy('role')
            ->get();

        $inactiveMembers = Comite::with('believer')
            ->where('is_active', false)
            ->orderByDesc('left_at')
            ->get();

        $availableBelievers = Believer::whereNotIn('status', ['parti', 'decede'])
            ->whereDoesntHave('comiteMembership', fn ($q) => $q->where('is_active', true))
            ->orderBy('lastname')->get();

        return view('comite.index', compact('members', 'inactiveMembers', 'availableBelievers'));
    }

    public function store(ComiteMembersRequest $request)
    {
        Comite::create([
            ...$request->validated(),
            'joined_at' => $request->joined_at ?? now(),
            'is_active' => true,
        ]);

        ActivityLogger::log("A attribué {$member->believer->full_name} au comité — fonction : {$member->role}");

        return redirect()
            ->route('comite.index')
            ->with('success', 'Membre attribué au comité avec succès.');
    }

    public function deactivate(Comite $member)
    {
        $member->update([
            'is_active' => false,
            'left_at'   => now(),
        ]);

        return redirect()
            ->route('comite.index')
            ->with('success', 'Membre désactivé du comité.');
    }

    public function reactivate(Comite $member)
    {
        $member->update([
            'is_active' => true,
            'left_at'   => null,
        ]);

        return redirect()
            ->route('comite.index')
            ->with('success', 'Membre réactivé.');
    }

    public function destroy(Comite $member)
    {
        ActivityLogger::log("A retiré {$member->believer->full_name} du comité");

        $member->delete();

        return redirect()
            ->route('comite.index')
            ->with('success', 'Membre retiré définitivement du comité.');
    }
}
