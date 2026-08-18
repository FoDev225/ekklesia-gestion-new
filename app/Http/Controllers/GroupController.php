<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Requests\AssignGroupBelieverRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::withCount('believers')->with('leader');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($leaderId = $request->input('leader_id')) {
            $query->where('leader_id', $leaderId);
        }

        $groups = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total'            => Group::count(),
            'total_members'    => \DB::table('believer_group')->count(),
            'sans_responsable' => Group::whereNull('leader_id')->count(),
        ];

        $leaders = Believer::whereHas('groupsLed')->orderBy('lastname')->get();

        return view('groups.index', compact('groups', 'stats', 'leaders'));
    }

    public function create()
    {
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('groups.create', compact('believers'));
    }

    public function store(StoreGroupRequest $request)
    {
        $group = Group::create($request->validated());

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Groupe créé avec succès.');
    }

    public function show(Group $group)
    {
        $group->load(['leader', 'believers' => fn ($q) => $q->orderBy('lastname'), 'rapports']);

        $availableBelievers = Believer::whereDoesntHave('groups', function ($q) use ($group) {
            $q->where('groups.id', $group->id);
        })->orderBy('lastname')->get();

        return view('groups.show', compact('group', 'availableBelievers'));
    }

    public function edit(Group $group)
    {
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('groups.edit', compact('group', 'believers'));
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->validated());

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Groupe mis à jour avec succès.');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()
            ->route('groups.index')
            ->with('success', 'Groupe supprimé avec succès.');
    }

    public function assignBeliever(AssignGroupBelieverRequest $request, Group $group)
    {
        $group->believers()->syncWithoutDetaching([
            $request->believer_id => [
                'joined_at' => $request->joined_at ?? now(),
            ],
        ]);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Fidèle affecté au groupe avec succès.');
    }

    public function removeBeliever(Group $group, Believer $believer)
    {
        $group->believers()->detach($believer->id);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Fidèle retiré du groupe.');
    }

    public function membersPdf(Group $group)
    {
        $group->load(['believers' => fn ($q) => $q->orderBy('lastname')]);

        $pdf = Pdf::loadView('groups.pdf.members', compact('group'));

        return $pdf->download('membres-groupe-' . \Str::slug($group->name) . '.pdf');
    }
}