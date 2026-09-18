<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceBudgetLine extends Model
{
    protected $fillable = ['period_id', 'category_id', 'montant_alloue'];

    protected $casts = ['montant_alloue' => 'decimal:2'];

    public function period()
    {
        return $this->belongsTo(FinancePeriod::class, 'period_id');
    }

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function getRealiseAttribute(): float
    {
        return (float) FinanceTransaction::where('category_id', $this->category_id)
            ->where('period_id', $this->period_id)
            ->where('type', 'depense')
            ->sum('montant');
    }

    public function getEcartAttribute(): float
    {
        return (float) $this->montant_alloue - $this->realise;
    }

    public function getPourcentageAttribute(): float
    {
        return $this->montant_alloue > 0
            ? round(($this->realise / $this->montant_alloue) * 100, 1)
            : 0;
    }

    public function getIsOverBudgetAttribute(): bool
    {
        return $this->realise > $this->montant_alloue;
    }
}
