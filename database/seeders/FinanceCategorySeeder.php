<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use App\Models\Team;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════
        // RECETTES
        // ══════════════════════════════════════
        foreach (['Dîmes', 'Offrandes'] as $name) {
            FinanceCategory::firstOrCreate(
                ['type' => 'recette', 'groupe' => 'recettes', 'name' => $name]
            );
        }

        $autresRecettes = [
            'Cotisations exceptionnelles (Construction)',
            'Cotisation spéciale',
            'Dons (journée Actions de grâce, etc.)',
        ];
        foreach ($autresRecettes as $name) {
            FinanceCategory::firstOrCreate(
                ['type' => 'recette', 'groupe' => 'autres_recettes', 'name' => $name]
            );
        }

        // ══════════════════════════════════════
        // CHARGES EXTERNES — calcul automatique en % des recettes
        // ══════════════════════════════════════
        FinanceCategory::firstOrCreate(
            ['type' => 'depense', 'groupe' => 'charges_externes', 'name' => 'Part AEBECI (10% des recettes)'],
            ['is_statutory' => true, 'statutory_rate' => 10.00]
        );
        FinanceCategory::firstOrCreate(
            ['type' => 'depense', 'groupe' => 'charges_externes', 'name' => 'Part Vision (40% après déduction 10% AEBECI)'],
            ['is_statutory' => true, 'statutory_rate' => 40.00]
        );

        // ══════════════════════════════════════
        // CHARGES INTERNES
        // ══════════════════════════════════════

        // ── Dépenses de fonctionnement ──
        $fonctionnement = [
            'Eau', 'Électricité', 'Entretien du temple', 'Entretien matériel (électricité, plomberie)',
            'Fourniture et matériel de bureau', 'Entretien matériel de musique',
            'Entretien splits et installations', 'Communication église',
            'Entretien du véhicule du pasteur', 'Frais bancaires',
        ];
        foreach ($fonctionnement as $name) {
            FinanceCategory::firstOrCreate([
                'type' => 'depense', 'groupe' => 'charges_internes',
                'sous_groupe' => 'fonctionnement', 'name' => $name,
            ]);
        }

        // ── Activité du comité ──
        $activiteComite = [
            'Cène', 'Transport de pasteurs visiteurs',
            "Repas et collations à l'Église (AG, pré-AG, réception, etc.)",
            'Formation', 'Activité sociale', 'Mission',
            'Part assistance et complément loyer pasteur',
            'Mobilier et matériel de bureau', 'Cotisation FEECI',
            'Visite bureau national', 'Investissement',
        ];
        foreach ($activiteComite as $name) {
            FinanceCategory::firstOrCreate([
                'type' => 'depense', 'groupe' => 'charges_internes',
                'sous_groupe' => 'activite_comite', 'name' => $name,
            ]);
        }

        // ── Activité des groupes constitués — liées aux équipes existantes ──
        $groupes = [
            'Ecodim'              => 'ecodim',
            'Afebeci'             => 'afebeci',
            'J-Aebeci'            => 'j-aebeci',
            'Évangélisation'      => 'evangelisation',
            'Dexy'                => 'dexy',
            'Cellule matrimoniale'=> null,
            'Groupe musical'      => null,
        ];
        foreach ($groupes as $name => $slug) {
            $team = $slug ? Team::where('slug', $slug)->first() : null;
            FinanceCategory::firstOrCreate(
                ['type' => 'depense', 'groupe' => 'charges_internes', 'sous_groupe' => 'activite_groupes', 'name' => $name],
                ['team_id' => $team?->id]
            );
        }

        // ── Épargne et autres dépenses ──
        $epargneAutres = [
            'Cotisation spéciale (FID, journée pastorale, etc.)',
            'PEI (Épargne)',
        ];
        foreach ($epargneAutres as $name) {
            FinanceCategory::firstOrCreate([
                'type' => 'depense', 'groupe' => 'charges_internes',
                'sous_groupe' => 'epargne_autres', 'name' => $name,
            ]);
        }
    }
}