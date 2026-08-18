<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'leader_id',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Believer::class, 'leader_id');
    }

    public function believers(): BelongsToMany
    {
        return $this->belongsToMany(Believer::class, 'believer_group')
            ->withPivot('joined_at');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class)->orderByDesc('date');
    }
}