<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'believer_id',
        'commune',
        'quartier',
        'sous_quartier',
        'phone',
        'whatsapp',
        'email',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class, 'believer_id');
    }
}
