<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Activity;
use App\Http\Requests\StoreTeamActivityRequest;
use App\Http\Requests\FinishTeamActivityRequest;
use App\Http\Requests\PostponeTeamActivityRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class TeamActivityController extends Controller
{
    public function store(StoreTeamActivityRequest $request, Team $team)
    {
        $this->authorize('manage', $team);

        $team->activities()->create($request->validated());

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Activité programmée avec succès.');
    }

    public function finish(FinishTeamActivityRequest $request, Team $team, Activity $activity)
    {
        $this->authorize('manage', $team);

        $attendancePath = $request->file('attendance_list_path')->store("team-activities/{$team->id}/attendance", 'public');
        $reportPath = $request->file('report_path')->store("team-activities/{$team->id}/reports", 'public');

        $activity->update([
            'attendance_list_path' => $attendancePath,
            'report_path'          => $reportPath,
            'status'               => 'realisee',
        ]);

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Activité clôturée avec succès.');
    }

    public function postpone(PostponeTeamActivityRequest $request, Team $team, Activity $activity)
    {
        $this->authorize('manage', $team);

        $activity->update([
            'date'   => $request->new_date,
            'status' => 'non_realisee',
        ]);

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Activité ajournée, nouvelle date enregistrée.');
    }

    public function programPdf(Team $team)
    {
        $this->authorize('manage', $team);

        $activities = $team->activities()->orderBy('date')->get();

        $pdf = Pdf::loadView('teams.pdf.program', compact('team', 'activities'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("programme-activites-{$team->slug}.pdf");
    }

    public function reportPdf(Team $team)
    {
        $this->authorize('manage', $team);

        $activities = $team->activities()->orderBy('date')->get();

        $stats = [
            'total'         => $activities->count(),
            'realisees'     => $activities->where('status', 'realisee')->count(),
            'non_realisees' => $activities->where('status', 'non_realisee')->count(),
            'en_cours'      => $activities->where('status', 'en_cours')->count(),
            'budget_total'  => $activities->sum('budget'),
        ];

        $pdf = Pdf::loadView('teams.pdf.report', compact('team', 'activities', 'stats'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("rapport-activites-{$team->slug}.pdf");
    }
}