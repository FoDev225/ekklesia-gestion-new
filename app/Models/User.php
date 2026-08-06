<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'believer_id',
        'name',
        'username',
        'email',
        'password',
        'is_active',
        'must_change_password',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password_changed_at'  => 'datetime',
            'password'             => 'hashed',
            'is_active'            => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    // public function getAuthIdentifierName(): string
    // {
    //     return 'username'; // Laravel utilisera username au lieu de email
    // }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    // -------------------------------------------------------
    // Génération automatique du username
    // format : prenom.nom (ex: toure.boribaga)
    // -------------------------------------------------------

    public static function generateUsername(string $firstname, string $lastname): string
    {
        // On ne garde que le DERNIER mot du prénom (ex: "Nanourgo Pierre" → "Pierre")
        $firstWord = Str::of($firstname)->trim()->explode(' ')->last();

        $first = Str::slug(self::removeAccents($firstWord));
        $last  = Str::slug(self::removeAccents($lastname));

        $base     = "{$first}.{$last}";
        $username = $base;
        $counter  = 1;

        // S'assurer de l'unicité (y compris parmi les comptes archivés)
        while (static::withTrashed()->where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }

    private static function removeAccents(string $str): string
    {
        $accents = [
            'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a',
            'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
            'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i',
            'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
            'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u',
            'ý'=>'y','ÿ'=>'y',
            'ñ'=>'n','ç'=>'c',
        ];
        return strtr(mb_strtolower($str), $accents);
    }

    // -------------------------------------------------------
    // Génération d'un mot de passe temporaire
    // -------------------------------------------------------

    public static function generateTempPassword(?string $username = null): string
    {
        $base = 'Ekklesia' . now()->format('Y');

        if ($username) {
            // Ajoute un suffixe dérivé du username pour que chaque mot de passe soit unique
            $suffix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $username), 0, 3));
            return "{$base}@{$suffix}";
        }

        return "{$base}@" . strtoupper(Str::random(3));
    }

    // -------------------------------------------------------
    // Helpers de rôle
    // -------------------------------------------------------

    public function dashboardRoute(): string
    {
        return 'dashboard';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->getRoleNames()->first()) {
            'admin'         => 'Administrateur',
            'pasteur'       => 'Pasteur',
            'secretariat'   => 'Secrétariat',
            'direction_culte'    => 'Direction Culte',
            'jaebeci' => 'J-AEBECI',
            'afebeci'  => 'AFEBECI',
            'direction_ecodim'   => 'Direction ECODIM',
            default          => '—',
        };
    }

}