<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelieverTeam extends Model
{
    protected $fillable = [
        'believer_id',
        'team_id',
        'joined_at',
    ];

    protected $dates = [
        'joined_at',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }
}
