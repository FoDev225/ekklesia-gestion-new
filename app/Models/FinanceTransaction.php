<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'category_id', 'account_id', 'period_id', 'montant', 'date',
        'description', 'piece_justificative_path', 'fleche_type', 'fleche_id', 'created_by',
        'numero_document', 'mode_paiement', 'numero_cheque',
        'beneficiaire', 'emetteur', 'verificateur', 'recepteur',
    ];

    protected $casts = [
        'date'    => 'date',
        'montant' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function (FinanceTransaction $t) {
            if (empty($t->period_id) && $t->date) {
                $period = FinancePeriod::forDate($t->date);
                $t->period_id = $period?->id;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function account()
    {
        return $this->belongsTo(FinanceAccount::class, 'account_id');
    }

    public function period()
    {
        return $this->belongsTo(FinancePeriod::class, 'period_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPieceUrlAttribute(): ?string
    {
        return $this->piece_justificative_path ? \Storage::url($this->piece_justificative_path) : null;
    }

    public function getFlecheLabelAttribute(): ?string
    {
        if (!$this->fleche_type || !$this->fleche_id) {
            return null;
        }

        return match ($this->fleche_type) {
            'construction_project' => ProjetConstruction::find($this->fleche_id)?->libelle,
            'dossier_foncier'      => DossierFoncier::find($this->fleche_id)?->libelle,
            default                 => null,
        };
    }
}
