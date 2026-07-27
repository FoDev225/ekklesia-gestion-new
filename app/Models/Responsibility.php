<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responsibility extends Model
{
    protected $fillable = [
        'believer_id',
        'old',
        'current',
        'desire',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class, 'believer_id');
    }
}
