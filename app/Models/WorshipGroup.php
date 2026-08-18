<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorshipGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'leader_id',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Believer::class, 'leader_id');
    }

    public function believers(): BelongsToMany
    {
        return $this->belongsToMany(Believer::class, 'believer_worship_group')
            ->withPivot('joined_at');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(WorshipGroupRapport::class)->orderByDesc('date');
    }   
}