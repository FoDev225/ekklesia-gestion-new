<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuneralRegister extends Model
{
    protected $fillable = [
        'believer_id',
        'parent_firstname',
        'parent_lastname',
        'death_date',
        'burial_place',
        'family_relationship',
        'cause_of_death',
        'funeral_date',
        'funeral_place',
        'loincloths_number',
        'amount_paid',
        'nbre_pagne',
        'cash_amount',
    ];

    protected $casts = [
        'death_date'   => 'date',
        'funeral_date' => 'date',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    public function getDeceasedFullNameAttribute(): string
    {
        return "{$this->parent_lastname} {$this->parent_firstname}";
    }

    public function getFamilyRelationshipLabelAttribute(): string
    {
        return match($this->family_relationship) {
            'pere'    => 'Père',
            'mere'    => 'Mère',
            'enfant'  => 'Enfant biologique',
            default   => $this->family_relationship,
        };
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('parent_lastname', 'like', "%{$search}%")
              ->orWhere('parent_firstname', 'like', "%{$search}%")
              ->orWhereHas('believer', fn($q2) =>
                  $q2->where('lastname', 'like', "%{$search}%")
                     ->orWhere('firstname', 'like', "%{$search}%")
              );
        });
    }

    public function scopeByRelationship($query, ?string $relationship)
    {
        return $relationship ? $query->where('family_relationship', $relationship) : $query;
    }

    public function scopeByYear($query, ?int $year)
    {
        return $year ? $query->whereYear('funeral_date', $year) : $query;
    }
}