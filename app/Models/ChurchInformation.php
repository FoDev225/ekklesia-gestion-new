<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchInformation extends Model
{
    protected $table = 'church_information';

    protected $fillable = [
        'believer_id',
        'connaissance_eglise',
        'original_church',
        'arrival_year',
        'conversion_date',
        'conversion_place',
        'baptised',
        'baptism_date',
        'baptism_place',
        'baptism_pastor',
        'baptism_card_number',
    ];

    protected $casts = [
        'conversion_date' => 'date',
        'baptism_date' => 'date',
        'baptised' => 'boolean',
    ];

    public function believer()
    {
        return $this->belongsTo(Believer::class, 'believer_id');
    }
}
