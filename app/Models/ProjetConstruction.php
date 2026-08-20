<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjetConstruction extends Model
{
    protected $fillable = [
        'libelle',
        'date_lancement',
        'date_fin',
        'cout',
        'status',
        'rapport_path',
    ];

    protected $casts = [
        'date_lancement' => 'date',
        'date_fin' => 'date',
        'cout' => 'decimal:2',
    ];

    public function getRapportUrlAttribute(): ?string
    {
        return $this->rapport_path ? \Storage::url($this->rapport_path) : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'realise' ? 'Réalisé' : 'En cours';
    }
}
