<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelieverCell extends Model
{
    protected $table = 'believer_cell';

    protected $fillable = [
        'believer_id',
        'cell_id',
    ];
}
