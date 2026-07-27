<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildDedication extends Model
{
    protected $fillable = [
        'father_id',
        'mother_id',
        'father_name',
        'mother_name',
        'demande_date',
        'dedication_date',
        'child_lastname',
        'child_firstname',
        'gender',
        'child_birthdate',
        'child_birthplace',
    ];

    protected $casts = [
        'demande_date'    => 'date',
        'dedication_date' => 'date',
        'child_birthdate' => 'date',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function father()
    {
        return $this->belongsTo(Believer::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Believer::class, 'mother_id');
    }

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    public function getChildFullNameAttribute(): string
    {
        return "{$this->child_lastname} {$this->child_firstname}";
    }

    public function getFatherDisplayNameAttribute(): string
    {
        return $this->father?->full_name ?? $this->father_name ?? '—';
    }

    public function getMotherDisplayNameAttribute(): string
    {
        return $this->mother?->full_name ?? $this->mother_name ?? '—';
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('child_lastname', 'like', "%{$search}%")
              ->orWhere('child_firstname', 'like', "%{$search}%")
              ->orWhere('father_name', 'like', "%{$search}%")
              ->orWhere('mother_name', 'like', "%{$search}%")
              ->orWhereHas('father', fn($q2) => $q2->where('lastname', 'like', "%{$search}%")->orWhere('firstname', 'like', "%{$search}%"))
              ->orWhereHas('mother', fn($q2) => $q2->where('lastname', 'like', "%{$search}%")->orWhere('firstname', 'like', "%{$search}%"));
        });
    }

    public function scopeByGender($query, ?string $gender)
    {
        return $gender ? $query->where('gender', $gender) : $query;
    }

    public function scopeByYear($query, ?int $year)
    {
        return $year ? $query->whereYear('dedication_date', $year) : $query;
    }
}