<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    protected $fillable = ['type', 'groupe', 'sous_groupe', 'name', 'team_id', 'is_active', 'is_statutory', 'statutory_rate'];

    protected $casts = ['is_active' => 'boolean', 'is_statutory' => 'boolean', 'statutory_rate' => 'decimal:2'];

    public const GROUPES_DEPENSE = [
        'charges_externes' => 'Charges externes',
        'charges_internes'  => 'Charges internes',
    ];

    public const SOUS_GROUPES_DEPENSE = [
        'fonctionnement'    => 'Dépenses de fonctionnement',
        'activite_comite'   => 'Activité du comité',
        'activite_groupes'  => 'Activité des groupes constitués',
        'epargne_autres'    => 'Épargne et autres dépenses',
    ];

    public const GROUPES_RECETTE = [
        'recettes'         => 'Recettes',
        'autres_recettes'  => 'Autres recettes',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'category_id');
    }

    public function budgetLines()
    {
        return $this->hasMany(FinanceBudgetLine::class, 'category_id');
    }

    public function scopeRecette($query)
    {
        return $query->where('type', 'recette');
    }

    public function scopeDepense($query)
    {
        return $query->where('type', 'depense');
    }

    public function getGroupeLabelAttribute(): ?string
    {
        return $this->groupe ? (self::GROUPES_DEPENSE[$this->groupe] ?? $this->groupe) : null;
    }
}
