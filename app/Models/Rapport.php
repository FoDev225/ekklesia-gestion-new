<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $fillable = [
        'group_id',
        'titre',
        'description',
        'fichier',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getFichierUrlAttribute()
    {
        return $this->fichier ? asset('storage/' . $this->fichier) : null;
    }
}
