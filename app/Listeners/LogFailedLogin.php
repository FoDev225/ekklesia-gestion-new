<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $attemptedLogin = $event->credentials['username'] ?? $event->credentials['email'] ?? 'inconnu';

        ActivityLog::create([
            'user_id'     => $event->user?->id,
            'username'    => $attemptedLogin,
            'type'        => 'failed_login',
            'description' => 'Tentative de connexion échouée (mauvais identifiant ou mot de passe)',
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}