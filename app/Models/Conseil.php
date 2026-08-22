<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conseil extends Model
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

    public const ROLES_GESTION = [
        'Président du Conseil',
        'Membre du Comité de Gestion',
        'Responsable de la Jeunesse',
        'Responsable de l\'ECODIM',
        'Responsable des Femmes',
        'Conseiller',
        'Responsable Groupe Musical',
        'Responsable Chorale Eden',
        'Responsable Chorale Horeb',
        'Responsable DEXY',
        'Responsaable Chorale Sainte Cohorte',
        'Responsable patrimoine',
        'Responsable de la Communication',
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
