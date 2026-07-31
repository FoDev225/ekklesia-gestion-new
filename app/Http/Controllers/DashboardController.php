<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\NewComer;
use App\Models\Departure;
use App\Models\Sanction;
use App\Models\Periode;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Redirection intelligente après connexion, selon le rôle de l'utilisateur.
     */
    public function redirect()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('pasteur')) {
            return redirect()->route('dashboard.pasteur');
        }

        if ($user->hasRole('secretariat')) {
            return redirect()->route('dashboard.secretariat');
        }

        if ($user->hasRole('direction_culte')) {
            return redirect()->route('cultes.periodes');
        }

        if ($user->hasRole('jaebeci')) {
            return redirect()->route('dashboard.jeunes');
        }

        if ($user->hasRole('afebeci')) {
            return redirect()->route('dashboard.femmes');
        }

        if ($user->hasRole('direction_ecodim')) {
            return redirect()->route('dashboard.ecodim');
        }

        if ($user->hasRole('acteur_culte')) {
            $periode = Periode::where('is_active', true)->first()
                ?? Periode::orderByDesc('start_date')->first();

            if ($periode) {
                return redirect()->route('cultes.services', $periode);
            }

            return redirect()->route('dashboard.acteur-culte-empty');
        }

        abort(403, "Aucun rôle valide n'est associé à ce compte.");
    }

    public function admin()
    {
        return view('dashboards.admin', $this->stats());
    }

    public function pasteur()
    {
        return view('dashboards.pasteur', $this->stats());
    }

    /**
     * Dashboard du responsable J-AEBECI (jeunes).
     */
    public function jeunes()
    {
        return view('dashboards.jeunes', $this->teamDashboardData('j-aebeci'));
    }

    /**
     * Dashboard du responsable AFEBECI (femmes).
     */
    public function femmes()
    {
        return view('dashboards.femmes', $this->teamDashboardData('afebeci'));
    }

    /**
     * Prépare les données de synthèse pour le dashboard d'une équipe donnée.
     */
    private function teamDashboardData(string $slug): array
    {
        $team = Team::where('slug', $slug)->firstOrFail();
        $team->load(['leader', 'believers']);

        $activities = $team->activities()->orderBy('date', 'desc')->get();

        $activityStats = [
            'total'         => $activities->count(),
            'realisees'     => $activities->where('status', 'realisee')->count(),
            'non_realisees' => $activities->where('status', 'non_realisee')->count(),
            'en_cours'      => $activities->where('status', 'en_cours')->count(),
            'budget_total'  => $activities->sum('budget'),
        ];
        $activityStats['pct_realisees'] = $activityStats['total']
            ? round($activityStats['realisees'] / $activityStats['total'] * 100) : 0;

        // Les 5 prochaines activités programmées (statut en_cours, date à venir)
        $prochainesActivites = $activities
            ->where('status', 'en_cours')
            ->sortBy('date')
            ->take(5);

        return [
            'team'                 => $team,
            'activityStats'        => $activityStats,
            'prochainesActivites'  => $prochainesActivites,
        ];
    }

    private function stats(): array
    {
        // -------------------------------------------------------
        // Totaux généraux
        // -------------------------------------------------------
        $total       = Believer::count();
        $actifs      = Believer::where('status', 'actif')->count();
        $inactifs    = Believer::where('status', 'inactif')->count();
        $sanctionnes = Believer::where('status', 'sanctionne')->count();
        $partis      = Departure::where('type', 'depart')->count();
        $decedes     = Departure::where('type', 'deces')->count();

        $parGenre = Believer::whereIn('status', ['actif', 'inactif', 'sanctionne'])
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $hommes = $parGenre['M'] ?? 0;
        $femmes = $parGenre['F'] ?? 0;

        $parSituation = Believer::whereNotIn('status', ['parti', 'decede'])
            ->select('marital_status', DB::raw('count(*) as total'))
            ->groupBy('marital_status')
            ->pluck('total', 'marital_status')
            ->toArray();

        $parAge = Believer::whereNotIn('status', ['parti', 'decede'])
            ->whereNotNull('birth_date')
            ->select(DB::raw("
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= 2  THEN 'Nourrisson'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= 4  THEN 'Pré-scolaire'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= 18 THEN 'ECODIM'
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= 40 THEN 'Jeunes'
                    ELSE 'Adultes'
                END as age_group,
                count(*) as total
            "))
            ->groupBy('age_group')
            ->pluck('total', 'age_group')
            ->toArray();

        $ordreAge = ['Nourrisson', 'Pré-scolaire', 'ECODIM', 'Jeunes', 'Adultes'];
        $parAgeTrie = [];
        foreach ($ordreAge as $label) {
            $parAgeTrie[$label] = $parAge[$label] ?? 0;
        }

        $baptises    = Believer::whereHas('churchInformation', fn($q) => $q->where('baptised', true))->count();
        $nonBaptises = $total > 0 ? $total - $baptises : 0;
        $pctBaptises = $total > 0 ? round(($baptises / $total) * 100, 1) : 0;

        $sanctionsActives = Sanction::where('is_active', true)->count();

        $nouveauxParMois = Believer::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get()
            ->mapWithKeys(function ($row) {
                $label = \Carbon\Carbon::createFromDate($row->annee, $row->mois, 1)
                    ->translatedFormat('M Y');
                return [$label => $row->total];
            });

        $nouveauxAnnee   = Believer::whereYear('created_at', now()->year)->count();
        $nouveauxPersonnes = NewComer::whereYear('created_at', now()->year)->count();

        return compact(
            'total', 'actifs', 'inactifs', 'sanctionnes',
            'partis', 'decedes',
            'hommes', 'femmes',
            'parSituation',
            'parAgeTrie',
            'baptises', 'nonBaptises', 'pctBaptises',
            'sanctionsActives',
            'nouveauxParMois',
            'nouveauxAnnee', 'nouveauxPersonnes'
        );
    }
}