<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewComer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lastname',
        'firstname',
        'gender',
        'birth_date',
        'phone',
        'whatsapp',
        'category',
        'is_recommended',
        'recommended_by',
        'first_visit_date',
        'notes',
        'believer_id',
        'converted_to_believer_at',
    ];

    protected $casts = [
        'birth_date'               => 'date',
        'first_visit_date'         => 'date',
        'converted_to_believer_at' => 'date',
        'is_recommended'           => 'boolean',
    ];

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    public function getFullNameAttribute(): string
    {
        return "{$this->lastname} {$this->firstname}";
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'passage'          => 'De passage',
            'court_sejour'     => 'Court séjour',
            'demeurant'        => 'Demeurant',
            'nouveau_converti' => 'Nouveau converti',
            default            => '—',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'passage'          => 'bg-gray-100 text-gray-600',
            'court_sejour'     => 'bg-yellow-100 text-yellow-700',
            'demeurant'        => 'bg-blue-100 text-blue-700',
            'nouveau_converti' => 'bg-green-100 text-green-700',
            default            => 'bg-gray-100 text-gray-500',
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return match($this->gender) {
            'M' => 'Homme',
            'F' => 'Femme',
            default => '—',
        };
    }

    public function getIsConvertedAttribute(): bool
    {
        return $this->believer_id !== null;
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function believer()
    {
        return $this->belongsTo(Believer::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('lastname', 'like', "%{$search}%")
              ->orWhere('firstname', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    public function scopeByCategory($query, ?string $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeByGender($query, ?string $gender)
    {
        return $gender ? $query->where('gender', $gender) : $query;
    }

    public function scopeByYear($query, ?int $year)
    {
        return $year ? $query->whereYear('first_visit_date', $year) : $query;
    }

    public function scopeNotConverted($query)
    {
        return $query->whereNull('believer_id');
    }
}