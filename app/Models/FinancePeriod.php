<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancePeriod extends Model
{
    protected $fillable = ['type', 'annee', 'date_debut', 'date_fin', 'date_ag_prevue', 'is_closed'];

    protected $casts = [
        'date_debut'     => 'date',
        'date_fin'       => 'date',
        'date_ag_prevue' => 'date',
        'is_closed'      => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'period_id');
    }

    public function budgetLines()
    {
        return $this->hasMany(FinanceBudgetLine::class, 'period_id');
    }

    public function getLabelAttribute(): string
    {
        $range = $this->type === 'S1' ? 'Janvier - Juin' : 'Juillet - Décembre';
        return "{$this->type} {$this->annee} ({$range})";
    }

    /**
     * Trouve la période comptable qui contient une date donnée.
     */
    public static function forDate($date): ?self
    {
        $date = \Carbon\Carbon::parse($date)->toDateString();

        return self::where('date_debut', '<=', $date)
            ->where('date_fin', '>=', $date)
            ->first();
    }

    /**
     * Période comptable actuelle (celle qui contient aujourd'hui).
     */
    public static function current(): ?self
    {
        return self::forDate(now());
    }

    public function getTotalRecettesAttribute(): float
    {
        return (float) $this->transactions()->where('type', 'recette')->sum('montant');
    }

    public function getMontantAttenduAebeciAttribute(): float
    {
        return round($this->total_recettes * 0.10, 2);
    }

    public function getMontantAttenduVisionAttribute(): float
    {
        // 40% après déduction de la part AEBECI, exactement comme dans le fichier Excel
        return round(($this->total_recettes - $this->montant_attendu_aebeci) * 0.40, 2);
    }

    public function getMontantPayeAebeciAttribute(): float
    {
        $cat = FinanceCategory::where('is_statutory', true)->where('statutory_rate', 10)->first();
        return $cat ? (float) $this->transactions()->where('category_id', $cat->id)->where('type', 'depense')->sum('montant') : 0;
    }

    public function getMontantPayeVisionAttribute(): float
    {
        $cat = FinanceCategory::where('is_statutory', true)->where('statutory_rate', 40)->first();
        return $cat ? (float) $this->transactions()->where('category_id', $cat->id)->where('type', 'depense')->sum('montant') : 0;
    }
}
