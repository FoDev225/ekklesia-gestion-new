<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'leader_id',
    ];

    public function leader()
    {
        return $this->belongsTo(Believer::class, 'leader_id');
    }

    public function believers()
    {
        return $this->belongsToMany(Believer::class, 'believer_team')
            ->withPivot('joined_at');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->orderBy('date', 'desc');
    }
}
