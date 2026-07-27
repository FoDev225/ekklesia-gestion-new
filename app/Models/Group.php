<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use SoftDeletes;

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
        return $this->belongsToMany(Believer::class, 'believer_group')
            ->withPivot('joined_at');
    }
}