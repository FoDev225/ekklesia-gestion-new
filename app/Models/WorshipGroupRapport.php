<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorshipGroupRapport extends Model
{
    protected $fillable = [
        'worship_group_id',
        'titre',
        'date',
        'description',
        'fichier',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function worshipGroup()
    {
        return $this->belongsTo(WorshipGroup::class);
    }

    public function getFichierUrlAttribute(): ?string
    {
        return $this->fichier ? \Storage::url($this->fichier) : null;
    }
}