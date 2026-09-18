<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceEngagementPayment extends Model
{
    protected $fillable = [
        'engagement_id', 'numero_recu', 'montant', 'date', 'encaisseur', 'account_id', 'created_by',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date'    => 'date',
    ];

    public function engagement()
    {
        return $this->belongsTo(FinanceEngagement::class, 'engagement_id');
    }

    public function account()
    {
        return $this->belongsTo(FinanceAccount::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}