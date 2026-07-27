<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = [
        'believer_id',
        'profession',
        'function',
        'company',
        'professional_contact',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class, 'believer_id');
    }
}
