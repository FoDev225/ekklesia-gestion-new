<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\WorshipGroup;
use App\Http\Requests\StoreWorshipGroupRequest;
use App\Http\Requests\UpdateWorshipGroupRequest;
use App\Http\Requests\AssignWorshipGroupBelieverRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class WorshipGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = WorshipGroup::withCount('believers')->with('leader');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($leaderId = $request->input('leader_id')) {
            $query->where('leader_id', $leaderId);
        }

        $worshipGroups = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total'            => WorshipGroup::count(),
            'total_members'    => \DB::table('believer_worship_group')->count(),
            'sans_responsable' => WorshipGroup::whereNull('leader_id')->count(),
        ];

        $leaders = Believer::whereHas('worshipGroupsLed')->orderBy('lastname')->get();

        return view('worship-groups.index', compact('worshipGroups', 'stats', 'leaders'));
    }

    public function create()
    {
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('worship-groups.create', compact('believers'));
    }

    public function store(StoreWorshipGroupRequest $request)
    {
        $worshipGroup = WorshipGroup::create($request->validated());

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Groupe de louange créé avec succès.');
    }

    public function show(WorshipGroup $worshipGroup)
    {
        $worshipGroup->load(['leader', 'believers' => fn ($q) => $q->orderBy('lastname')]);

        $availableBelievers = Believer::whereDoesntHave('worshipGroups', function ($q) use ($worshipGroup) {
            $q->where('worship_groups.id', $worshipGroup->id);
        })->orderBy('lastname')->get();

        return view('worship-groups.show', compact('worshipGroup', 'availableBelievers'));
    }

    public function edit(WorshipGroup $worshipGroup)
    {
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('worship-groups.edit', compact('worshipGroup', 'believers'));
    }

    public function update(UpdateWorshipGroupRequest $request, WorshipGroup $worshipGroup)
    {
        $worshipGroup->update($request->validated());

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Groupe de louange mis à jour avec succès.');
    }

    public function destroy(WorshipGroup $worshipGroup)
    {
        // Pas de soft deletes sur cette table : suppression définitive
        $worshipGroup->delete();

        return redirect()
            ->route('worship-groups.index')
            ->with('success', 'Groupe de louange supprimé avec succès.');
    }

    public function assignBeliever(AssignWorshipGroupBelieverRequest $request, WorshipGroup $worshipGroup)
    {
        $worshipGroup->believers()->syncWithoutDetaching([
            $request->believer_id => [
                'joined_at' => $request->joined_at ?? now(),
            ],
        ]);

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Fidèle affecté au groupe de louange avec succès.');
    }

    public function removeBeliever(WorshipGroup $worshipGroup, Believer $believer)
    {
        $worshipGroup->believers()->detach($believer->id);

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Fidèle retiré du groupe de louange.');
    }

    public function membersPdf(WorshipGroup $worshipGroup)
    {
        $worshipGroup->load(['believers' => fn ($q) => $q->orderBy('lastname')]);

        $pdf = Pdf::loadView('worship-groups.pdf.members', compact('worshipGroup'));

        return $pdf->download('membres-groupe-louange-' . Str::slug($worshipGroup->name) . '.pdf');
    }
}