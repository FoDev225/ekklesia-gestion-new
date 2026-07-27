<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Vérification compte actif
                if (! $user->is_active) {
                    Auth::guard($guard)->logout();
                    return redirect()->route('login')
                        ->withErrors(['username' => 'Votre compte est désactivé. Contactez l\'administrateur.']);
                }

                return redirect()->route($user->dashboardRoute());
            }
        }

        return $next($request);
    }
}