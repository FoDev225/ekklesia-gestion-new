<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelieverWorshipGroup extends Model
{
    protected $fillable = [
        'believer_id',
        'worship_group_id',
    ];
}
