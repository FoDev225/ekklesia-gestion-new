<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['periode_id', 'service_date', 'service_theme', 'service_type'];
 
    protected $casts = ['service_date' => 'date'];
 
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
 
    public function assignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }
 
    public function getServiceTypeLabelAttribute(): string
    {
        return match($this->service_type) {
            'commun'   => 'Culte commun',
            'francais' => 'Français',
            'senoufo'  => 'Sénoufo',
            'special'  => 'Spécial',
            default    => '—',
        };
    }
 
    // Récupérer les assignments par rôle
    public function assignmentsByRole(string $roleSlug, bool $backup = false)
    {
        return $this->assignments()
            ->whereHas('role', fn($q) => $q->where('slug', $roleSlug))
            ->where('is_backup', $backup)
            ->with(['believer', 'group'])
            ->get();
    }

    public function titulaireFor(string $roleSlug, bool $backup = false)
    {
        $roleSlug = strtolower(trim($roleSlug));

        return $this->assignments->first(function ($assignment) use ($roleSlug, $backup) {
            return $assignment->role
                && strtolower(trim($assignment->role->slug)) === $roleSlug
                && (bool) $assignment->is_backup === $backup;
        });
    }

    /**
     * Retourne tous les groupes de louange programmés pour ce culte.
     */
    public function worshipGroupsAssigned()
    {
        return $this->assignments
            ->filter(fn($a) => $a->role?->slug === 'louange' && $a->worshipGroup)
            ->pluck('worshipGroup');
    }

    /**
     * Retourne la date du dimanche de la semaine ISO en cours
     * (lundi = début de semaine), quel que soit le jour actuel.
     */
    public static function currentWeekSunday(): \Carbon\Carbon
    {
        return now()->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(6);
    }
}
