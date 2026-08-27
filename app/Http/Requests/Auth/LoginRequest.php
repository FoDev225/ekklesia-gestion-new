<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Nombre max de tentatives de connexion avant le blocage.
     */
    protected int $maxAttempts = 3;

    /**
     * Durée du blocage en secondes
     */
    protected int $lockoutSeconds = 300; // 5 minutes

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],  // ← username au lieu de email
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Le nom d\'utilisateur est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // ← Authentification par username au lieu de email
        if (! Auth::attempt(
            $this->only('username', 'password'),
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey(), $this->lockoutSeconds);

            throw ValidationException::withMessages([
                'username' => 'Identifiants incorrects. Vérifiez votre nom d\'utilisateur et mot de passe.',
            ]);
        }

        $user = Auth::user();

        // Vérifier que le compte est actif
        if (! $user->is_active) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey(), $this->lockoutSeconds);

            throw ValidationException::withMessages([
                'username' => 'Votre compte est désactivé. Contactez l\'administrateur.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    // ← Throttle basé sur username au lieu de email
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')) . '|' . $this->ip());
    }
}