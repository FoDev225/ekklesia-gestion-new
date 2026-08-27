<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'username',
        'type',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (ActivityLog $log) {
            $log->created_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'login' => 'Connexion réussie',
            'logout' => 'Déconnexion',
            'failed_login' => 'Échec de connexion',
            'lockout' => 'Compte bloqué',
            'action' => 'Action utilisateur',
            default => $this->type,
        };
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'login'        => 'bg-green-100 text-green-700',
            'logout'       => 'bg-gray-100 text-gray-700',
            'failed_login' => 'bg-yellow-100 text-yellow-700',
            'lockout'      => 'bg-red-100 text-red-700',
            'action'       => 'bg-blue-100 text-blue-700',
            default        => 'bg-gray-100 text-gray-700',
        };
    }
}
