<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';

    protected $fillable = [
        'believer_id',
        'niveau_etude',
        'diploma',
        'qualification',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class, 'believer_id');
    }
}
