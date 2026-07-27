<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\Team;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Requests\AssignBelieverRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TeamController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Team::class, 'team');
    // }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Team::class);

        // authorizeResource appelle déjà viewAny() automatiquement ici
        $query = Team::withCount('believers')->with('leader');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($leaderId = $request->input('leader_id')) {
            $query->where('leader_id', $leaderId);
        }

        $teams = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total'            => Team::count(),
            'total_members'    => \DB::table('believer_team')->count(),
            'sans_responsable' => Team::whereNull('leader_id')->count(),
        ];

        $leaders = Believer::whereHas('teamsLed')->orderBy('lastname')->get();

        return view('teams.index', compact('teams', 'stats', 'leaders'));
    }

    public function create()
    {
        // authorizeResource appelle déjà create()
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('teams.create', compact('believers'));
    }

    public function store(StoreTeamRequest $request)
    {
        // authorizeResource appelle déjà create()
        $team = Team::create($request->validated());

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Équipe créée avec succès.');
    }

    public function show(Team $team)
    {
        // authorizeResource appelle déjà view($team)
        $team->load(['leader', 'believers' => fn ($q) => $q->orderBy('lastname'), 'activities']);

        $availableBelievers = Believer::whereDoesntHave('teams', function ($q) use ($team) {
            $q->where('teams.id', $team->id);
        })->orderBy('lastname')->get();

        $believers = Believer::orderBy('lastname')->get();

        $activities = $team->activities;
        $activityStats = [
            'total'         => $activities->count(),
            'realisees'     => $activities->where('status', 'realisee')->count(),
            'non_realisees' => $activities->where('status', 'non_realisee')->count(),
            'en_cours'      => $activities->where('status', 'en_cours')->count(),
            'budget_total'  => $activities->sum('budget'),
        ];
        $activityStats['pct_realisees'] = $activityStats['total']
            ? round($activityStats['realisees'] / $activityStats['total'] * 100) : 0;
        $activityStats['pct_non_realisees'] = $activityStats['total']
            ? round($activityStats['non_realisees'] / $activityStats['total'] * 100) : 0;
        $activityStats['pct_en_cours'] = $activityStats['total']
            ? round($activityStats['en_cours'] / $activityStats['total'] * 100) : 0;

        return view('teams.show', compact(
            'team', 'availableBelievers', 'believers', 'activities', 'activityStats'
        ));
    }

    public function edit(Team $team)
    {
        // authorizeResource appelle déjà update($team)
        $believers = Believer::orderBy('lastname')->orderBy('firstname')->get();

        return view('teams.edit', compact('team', 'believers'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        // authorizeResource appelle déjà update($team)
        $team->update($request->validated());

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Équipe mise à jour avec succès.');
    }

    public function destroy(Team $team)
    {
        // authorizeResource appelle déjà delete($team)
        $team->delete();

        return redirect()
            ->route('teams.index')
            ->with('success', 'Équipe supprimée avec succès.');
    }

    /**
     * Ces méthodes ne font PAS partie du resource standard,
     * donc authorizeResource ne les couvre pas : on vérifie manuellement
     * via la policy "manage".
     */
    public function assignBeliever(AssignBelieverRequest $request, Team $team)
    {
        $this->authorize('manage', $team);

        $team->believers()->syncWithoutDetaching([
            $request->believer_id => [
                'joined_at' => $request->joined_at ?? now(),
            ],
        ]);

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Fidèle affecté à l\'équipe avec succès.');
    }

    public function removeBeliever(Team $team, Believer $believer)
    {
        $this->authorize('manage', $team);

        $team->believers()->detach($believer->id);

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Fidèle retiré de l\'équipe.');
    }

    public function membersPdf(Team $team)
    {
        $this->authorize('manage', $team);

        $team->load(['believers' => fn ($q) => $q->orderBy('lastname')]);

        $pdf = Pdf::loadView('teams.pdf.members', compact('team'));

        return $pdf->download("membres-{$team->slug}.pdf");
    }
}