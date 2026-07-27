<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'transaction_date',
        'service_id',
        'activity_id',
        'description',
        'budget_id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
