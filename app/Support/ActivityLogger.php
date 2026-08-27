<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Enregistre une action utilisateur (à appeler manuellement dans les contrôleurs
     * pour les actions importantes : création, modification, suppression...).
     *
     * Exemple : ActivityLogger::log("A créé le fidèle {$believer->full_name}");
     */

    public static function log(string $description): void
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'username'    => Auth::user()?->username ?? 'Invité',
            'type'        => 'action',
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}