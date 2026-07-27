<?php
// ═══════════════════════════════════════════════
// app/Models/Periode.php
// ═══════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = ['name', 'general_theme', 'start_date', 'end_date', 'is_active', 'is_archive'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'is_archive' => 'boolean',
    ];

    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('service_date');
    }

    public function getServiceCountAttribute(): int
    {
        return $this->services()->count();
    }

    // Activer cette période et désactiver les autres
    public function activate(): void
    {
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }
}