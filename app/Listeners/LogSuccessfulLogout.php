<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        ActivityLog::create([
            'user_id'     => $event->user->id,
            'username'    => $event->user->username ?? $event->user->email,
            'type'        => 'logout',
            'description' => 'Déconnexion',
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}