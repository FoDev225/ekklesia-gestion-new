<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required'         => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
            'password.min'              => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Vérifier que le nouveau est différent de l'ancien
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Le nouveau mot de passe doit être différent de l\'ancien.']);
        }

        $user->update([
            'password'             => bcrypt($request->password),
            'must_change_password' => false,
            'password_changed_at'  => now(),
        ]);

        return redirect()->route($user->dashboardRoute())
            ->with('success', 'Mot de passe modifié avec succès. Bienvenue !');
    }
}