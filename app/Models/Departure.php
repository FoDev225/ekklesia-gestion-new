<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    protected $fillable = [
        'believer_id',
        'type',
        'departure_date',
        'destination',
        'reason',
        'recorded_by',
    ];

    protected $casts = [
        'departure_date' => 'date',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'depart' => 'Départ',
            'deces'  => 'Décès',
            default  => '—',
        };
    }
}