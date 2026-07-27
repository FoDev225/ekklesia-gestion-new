<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    protected $fillable = [
        'believer_id',
        'start_date',
        'end_date',
        'reason',
        'decided_by',
        'is_active',
        'lifted_at',
        'lift_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'lifted_at'  => 'date',
        'is_active'  => 'boolean',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }
}