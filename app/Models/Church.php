<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $fillable = [
        'organisation',
        'organisation_name',
        'district',
        'church_name',
        'authorization',
        
        'address',
        'pastor_phone_number',
        'secretary_phone_number',
        'church_email',
        'pastor_email',
        'localisation',

        'photo_path',
    ];

    /**
     * Retourne l'instance unique de l'église (singleton).
     * Utilisé dans toutes les vues PDF.
     */
    public static function instance(): ?self
    {
        return static::first();
    }

    /**
     * Nom complet affiché en en-tête.
     */
    public function getFullNameAttribute(): string
    {
        return "EGLISE LOCALE {$this->church_name}";
    }

    /**
     * Ligne de contact pied de page.
     */
    public function getContactLineAttribute(): string
    {
        $parts = [];
        if ($this->address)              $parts[] = "Adresse : {$this->address}";
        if ($this->pastor_phone_number)  $parts[] = "Tél Pasteur : {$this->pastor_phone_number}";
        if ($this->secretary_phone_number) $parts[] = "Tél Secrétariat : {$this->secretary_phone_number}";
        if ($this->church_email)         $parts[] = "Email : {$this->church_email}";
        if ($this->pastor_email)         $parts[] = $this->pastor_email;
        return implode(' - ', $parts);
    }
}