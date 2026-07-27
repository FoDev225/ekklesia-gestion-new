<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'date',
        'theme',
        'location',
        'moderator',
        'preacher',
        'attendance_list_path',
        'report_path',
        'budget',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'budget' => 'decimal:2',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function isTerminable(): bool
    {
        return $this->status !== 'realisee';
    }
}
