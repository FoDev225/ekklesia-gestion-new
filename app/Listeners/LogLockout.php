<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Lockout;

class LogLockout
{
    public function handle(Lockout $event): void
    {
        $attemptedLogin = $event->request->input('username') ?? $event->request->input('email') ?? 'inconnu';

        ActivityLog::create([
            'user_id'     => null,
            'username'    => $attemptedLogin,
            'type'        => 'lockout',
            'description' => 'Compte temporairement bloqué après plusieurs tentatives échouées',
            'ip_address'  => $event->request->ip(),
            'user_agent'  => $event->request->userAgent(),
        ]);
    }
}