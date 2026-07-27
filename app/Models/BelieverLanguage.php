<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelieverLanguage extends Model
{
    protected $table = 'believer_language';

    protected $fillable = [
        'believer_id',
        'language_id',
    ];
}
