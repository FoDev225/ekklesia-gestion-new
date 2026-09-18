<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceAccount extends Model
{
    protected $fillable = ['name', 'type', 'alert_threshold'];

    protected $casts = ['alert_threshold' => 'decimal:2'];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'account_id');
    }

    public function getBalanceAttribute(): float
    {
        $recettes = $this->transactions()->where('type', 'recette')->sum('montant');
        $depenses = $this->transactions()->where('type', 'depense')->sum('montant');

        return (float) $recettes - (float) $depenses;
    }

    public function getIsOverThresholdAttribute(): bool
    {
        return $this->alert_threshold && $this->balance > $this->alert_threshold;
    }
}
