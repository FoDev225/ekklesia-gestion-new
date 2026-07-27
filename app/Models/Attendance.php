<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'attendee_id',
        'attendee_type',
        'attendable_id',
        'attendable_type',
        'attendance_date',
        'status',
    ];
}
