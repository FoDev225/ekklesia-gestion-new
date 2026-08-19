<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comite extends Model
{
    protected $fillable = [
        'believer_id',
        'role',
        'contact',
        'joined_at',
        'left_at',
        'is_active',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'left_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Fonctions suggérées pour le Comité de Gestion.
     */
    public const ROLES_GESTION = [
        'Président',
        'Vice-président',
        'Secrétaire Général',
        'Secrétaire Général Adjoint',
        'Trésorier',
        'Trésorier Adjoint',
        'Responsable Formation',
        'Responsable Formation Adjoint',
        'Comité de Contrôle et de Gestion',
        'Représentant de la Jeunesse',
        'Représentant de l\'ECODIM',
        'Représentant des femmes',
        'Conseiller',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
