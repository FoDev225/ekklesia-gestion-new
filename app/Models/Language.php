<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function believers()
    {
        return $this->belongsToMany(Believer::class, 'believer_language');
    }
}
