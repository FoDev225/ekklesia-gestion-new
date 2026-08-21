<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipeFonciere extends Model
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
        'left_at'   => 'date',
        'is_active' => 'boolean',
    ];

    public const ROLES = [
        'Responsable',
        'Conseiller juridique',
        'Négociateur',
        'Membre',
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
