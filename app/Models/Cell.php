<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    protected $fillable = [
        'name',
        'quartier',
        'sous_quartier',
        'leader_id',
        'description',
    ];

    public function leader()
    {
        return $this->belongsTo(Believer::class, 'leader_id');
    }

    public function believers()
    {
        return $this->belongsToMany(Believer::class, 'believer_cell', 'cell_id', 'believer_id');
    }
}
