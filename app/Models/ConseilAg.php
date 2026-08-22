<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConseilAg extends Model
{
    protected $fillable = [
        'ag_type',
        'ag_date',
        'ag_time',
        'ag_objective',
        'rapport_path',
    ];

    protected $casts = [
        'ag_date' => 'date',
    ];

    public function getAgTimeAttribute($value): ?\Carbon\Carbon
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getRapportUrlAttribute(): ?string
    {
        return $this->rapport_path ? \Storage::url($this->rapport_path) : null;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->ag_type === 'extraordinaire' ? 'Extraordinaire' : 'Ordinaire';
    }
}
