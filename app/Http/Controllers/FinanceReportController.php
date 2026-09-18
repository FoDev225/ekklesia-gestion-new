<?php

namespace App\Http\Controllers;

use App\Models\FinancePeriod;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;

class FinanceReportController extends Controller
{
    public function resultat(Request $request)
    {
        $annee = (int) ($request->annee ?? now()->year);

        $periodeS1 = FinancePeriod::where('type', 'S1')->where('annee', $annee)->first();
        $periodeS2 = FinancePeriod::where('type', 'S2')->where('annee', $annee)->first();

        $dataS1 = $periodeS1 ? $this->periodData($periodeS1) : $this->emptyData();
        $dataS2 = $periodeS2 ? $this->periodData($periodeS2) : $this->emptyData();

        // Report : trésorerie nette cumulée AVANT le début de l'année sélectionnée
        $debutAnnee = $periodeS1?->date_debut ?? "{$annee}-01-01";
        $report = $this->cumulAvant($debutAnnee);

        $anneeData = [
            'total_recettes' => $dataS1['total_recettes'] + $dataS2['total_recettes'],
            'total_depenses' => $dataS1['total_depenses'] + $dataS2['total_depenses'],
        ];
        $anneeData['marge'] = $anneeData['total_recettes'] - $anneeData['total_depenses'];

        $availableYears = FinancePeriod::selectRaw('DISTINCT annee')->orderByDesc('annee')->pluck('annee');

        return view('finances.reports.resultat', compact(
            'annee', 'periodeS1', 'periodeS2', 'dataS1', 'dataS2', 'anneeData', 'report', 'availableYears'
        ));
    }

    private function periodData(FinancePeriod $period): array
    {
        $recettes = FinanceTransaction::where('type', 'recette')
            ->whereBetween('date', [$period->date_debut, $period->date_fin])
            ->get();

        $depenses = FinanceTransaction::with('category')
            ->where('type', 'depense')
            ->whereBetween('date', [$period->date_debut, $period->date_fin])
            ->get();

        $totalRecettes = (float) $recettes->sum('montant');
        $totalDepenses = (float) $depenses->sum('montant');

        $dimesOffrandesIds = $this->categoryIdsByName(['Dîmes', 'Offrandes']);

        $depensesExternes = (float) $depenses->filter(fn($t) => $t->category?->is_statutory)->sum('montant');

        return [
            'recettes_dimes_offrandes' => (float) $recettes->whereIn('category_id', $dimesOffrandesIds)->sum('montant'),
            'recettes_autres'          => $totalRecettes - (float) $recettes->whereIn('category_id', $dimesOffrandesIds)->sum('montant'),
            'depenses_externes'        => $depensesExternes,
            'depenses_internes'        => $totalDepenses - $depensesExternes,
            'total_recettes'           => $totalRecettes,
            'total_depenses'           => $totalDepenses,
            'marge'                    => $totalRecettes - $totalDepenses,
        ];
    }

    private function categoryIdsByName(array $names): array
    {
        return \App\Models\FinanceCategory::whereIn('name', $names)->pluck('id')->toArray();
    }

    private function cumulAvant(string $date): float
    {
        $recettes = (float) FinanceTransaction::where('type', 'recette')->where('date', '<', $date)->sum('montant');
        $depenses = (float) FinanceTransaction::where('type', 'depense')->where('date', '<', $date)->sum('montant');

        return $recettes - $depenses;
    }

    private function emptyData(): array
    {
        return [
            'recettes_dimes_offrandes' => 0, 'recettes_autres' => 0,
            'depenses_externes' => 0, 'depenses_internes' => 0,
            'total_recettes' => 0, 'total_depenses' => 0, 'marge' => 0,
        ];
    }
}