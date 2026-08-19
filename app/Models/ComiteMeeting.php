<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComiteMeeting extends Model
{
    protected $fillable = [
        'meeting_type',
        'meeting_date',
        'meeting_time',
        'meeting_objective',
        'rapport_path',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function getMeetingTimeAttribute($value): ?\Carbon\Carbon
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getRapportUrlAttribute(): ?string
    {
        return $this->rapport_path ? \Storage::url($this->rapport_path) : null;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->meeting_type === 'extraordinaire' ? 'Extraordinaire' : 'Ordinaire';
    }
}
