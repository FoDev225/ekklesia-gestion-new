<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MariageRegister extends Model
{
    protected $fillable = [
        // Époux
        'groom_id',
        'groom_name',
        'groom_birthdate',
        'groom_birth_place',
        'groom_bapistism_date',
        'groom_bapistism_place',
        'baptism_officer_groom',
        'groom_profession',
        'groom_photo',
        // Épouse
        'bride_id',
        'bride_name',
        'bride_birthdate',
        'bride_birth_place',
        'bride_bapistism_date',
        'bride_bapistism_place',
        'baptism_officer_bride',
        'bride_profession',
        'bride_photo',
        // Mariage civil
        'civil_marriage_date',
        'civil_marriage_place',
        // Mariage religieux
        'religious_marriage_date',
        'religious_marriage_place',
        'wedding_mc',
        'wedding_preacher',
        'hand_bible',
        'officiant',
        // Témoins
        'groom_witness',
        'groom_witness_profession',
        'bride_witness',
        'bride_witness_profession',
    ];

    protected $casts = [
        'groom_birthdate'       => 'date',
        'bride_birthdate'       => 'date',
        'groom_bapistism_date'  => 'date',
        'bride_bapistism_date'  => 'date',
        'civil_marriage_date'   => 'date',
        'religious_marriage_date' => 'date',
    ];

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function groom()
    {
        return $this->belongsTo(Believer::class, 'groom_id');
    }

    public function bride()
    {
        return $this->belongsTo(Believer::class, 'bride_id');
    }

    // -------------------------------------------------------
    // Accessors — Nom affiché (fidèle ou saisie libre)
    // -------------------------------------------------------

    public function getGroomDisplayNameAttribute(): string
    {
        return $this->groom?->full_name ?? $this->groom_name ?? '—';
    }

    public function getBrideDisplayNameAttribute(): string
    {
        return $this->bride?->full_name ?? $this->bride_name ?? '—';
    }

    public function getGroomBirthdateDisplayAttribute(): ?string
    {
        if ($this->groom_id && $this->groom?->birth_date) {
            return $this->groom->birth_date->format('d/m/Y');
        }
        return $this->groom_birthdate?->format('d/m/Y');
    }

    public function getBrideBirthdateDisplayAttribute(): ?string
    {
        if ($this->bride_id && $this->bride?->birth_date) {
            return $this->bride->birth_date->format('d/m/Y');
        }
        return $this->bride_birthdate?->format('d/m/Y');
    }

    public function getGroomBirthPlaceDisplayAttribute(): ?string
    {
        if ($this->groom_id && $this->groom?->birth_place) {
            return $this->groom->birth_place;
        }
        return $this->groom_birth_place;
    }

    public function getBrideBirthPlaceDisplayAttribute(): ?string
    {
        if ($this->bride_id && $this->bride?->birth_place) {
            return $this->bride->birth_place;
        }
        return $this->bride_birth_place;
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('groom_name', 'like', "%{$search}%")
              ->orWhere('bride_name', 'like', "%{$search}%")
              ->orWhereHas('groom', fn($q2) => $q2->where('lastname', 'like', "%{$search}%")->orWhere('firstname', 'like', "%{$search}%"))
              ->orWhereHas('bride', fn($q2) => $q2->where('lastname', 'like', "%{$search}%")->orWhere('firstname', 'like', "%{$search}%"));
        });
    }

    public function scopeByYear($query, ?int $year)
    {
        return $year ? $query->whereYear('religious_marriage_date', $year) : $query;
    }
}