<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // LoginRequest::authenticate() gère maintenant username + is_active
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Rediriger vers changement mot de passe si première connexion
        if ($user->must_change_password) {
            return redirect()->route('password.change')
                ->with('warning', 'Bienvenue ! Vous devez modifier votre mot de passe avant de continuer.');
        }
        
        return redirect()->route($user->dashboardRoute());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}