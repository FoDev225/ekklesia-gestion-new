<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipeConstruction extends Model
{
    protected $fillable = [
        'believer_id',
        'role',
        'contact',
        'joined_at',
        'left_at',
        'is_active',
    ];

    public const ROLES = [
        'Responsable',
        'Superviseur',
        'Membre',
        'Conseiller technique',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'left_at' => 'date',
        'is_active' => 'boolean',
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
