<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierFoncier extends Model
{
    protected $fillable = [
        'libelle',
        'localisation',
        'superficie',
        'statut',
        'cout',
        'date_debut',
        'date_acquisition',
        'notes',
        'document_path',
    ];

    protected $casts = [
        'date_debut'       => 'date',
        'date_acquisition' => 'date',
        'superficie'       => 'decimal:2',
        'cout'             => 'decimal:2',
    ];

    public const STATUTS = [
        'recherche'    => 'En recherche',
        'negociation'  => 'En négociation',
        'acquis'       => 'Acquis',
        'titre_obtenu' => 'Titre obtenu',
        'abandonne'    => 'Abandonné',
    ];

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document_path ? \Storage::url($this->document_path) : null;
    }

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'recherche'    => 'bg-gray-100 text-gray-700',
            'negociation'  => 'bg-yellow-100 text-yellow-700',
            'acquis'       => 'bg-blue-100 text-blue-700',
            'titre_obtenu' => 'bg-green-100 text-green-700',
            'abandonne'    => 'bg-red-100 text-red-700',
            default        => 'bg-gray-100 text-gray-700',
        };
    }
}
