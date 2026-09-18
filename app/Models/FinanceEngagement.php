<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceEngagement extends Model
{
    protected $fillable = [
        'believer_id', 'motif', 'fleche_type', 'fleche_id',
        'montant_engage', 'date_engagement', 'observations',
    ];

    protected $casts = [
        'montant_engage'  => 'decimal:2',
        'date_engagement' => 'date',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    public function payments()
    {
        return $this->hasMany(FinanceEngagementPayment::class, 'engagement_id');
    }

    public function getMontantPayeAttribute(): float
    {
        return (float) $this->payments()->sum('montant');
    }

    public function getResteAPayerAttribute(): float
    {
        return (float) $this->montant_engage - $this->montant_paye;
    }

    public function getIsSoldeAttribute(): bool
    {
        return $this->reste_a_payer <= 0;
    }

    public function getFlecheLabelAttribute(): ?string
    {
        return match ($this->fleche_type) {
            'construction_project' => ProjetConstruction::find($this->fleche_id)?->libelle,
            'dossier_foncier'      => DossierFoncier::find($this->fleche_id)?->libelle,
            default                 => null,
        };
    }
}