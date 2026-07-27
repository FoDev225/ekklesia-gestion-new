<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Exclure les routes de changement de mot de passe et de déconnexion
        $excluded = ['password.change', 'password.update', 'logout'];

        if ($user->must_change_password && !in_array($request->route()?->getName(), $excluded)) {
            return redirect()->route('password.change')
                ->with('warning', 'Vous devez modifier votre mot de passe avant de continuer.');
        }

        return $next($request);
    }
}