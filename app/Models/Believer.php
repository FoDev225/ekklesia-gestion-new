<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Believer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_id',
        'register_number',
        'lastname',
        'firstname',
        'cni_number',
        'birth_date',
        'birth_place',
        'nationality',
        'gender',
        'marital_status',
        'number_of_children',
        'profile_picture',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    protected $appends = ['profile_picture_url'];

    public function getProfilePictureUrlAttribute(): ?string
    {
        return $this->profile_picture
            ? \Storage::url($this->profile_picture)
            : null;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->lastname} {$this->firstname}";
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date
            ? Carbon::parse($this->birth_date)->age
            : null;
    }

    public function getAgeGroupAttribute(): string
    {
        if (! $this->birth_date) return 'Non défini';

        $age = $this->age;

        return match (true) {
            $age <= 2  => 'Nourrisson',
            $age <= 4  => 'Pré-scolaire',
            $age <= 18 => 'ECODIM',
            $age <= 40 => 'Jeunes',
            default    => 'Adultes',
        };
    }

    public function getAgeGroupColorAttribute(): string
    {
        return match ($this->age_group) {
            'Nourrisson'   => 'bg-pink-100 text-pink-700',
            'Pré-scolaire' => 'bg-purple-100 text-purple-700',
            'ECODIM'       => 'bg-blue-100 text-blue-700',
            'Jeunes'       => 'bg-green-100 text-green-700',
            'Adultes'      => 'bg-yellow-100 text-yellow-700',
            default        => 'bg-gray-100 text-gray-700',
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'M' ? 'Homme' : 'Femme';
    }

    public function getMaritalStatusLabelAttribute(): string
    {
        return match ($this->marital_status) {
            'celibataire' => 'Célibataire',
            'marie'       => 'Marié(e)',
            'veuf'        => 'Veuf/Veuve',
            'divorce'     => 'Divorcé(e)',
            default       => 'Non renseigné',
        };
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function churchInformation()
    {
        return $this->hasOne(ChurchInformation::class);
    }

    public function education()
    {
        return $this->hasOne(Education::class);
    }

    public function profession()
    {
        return $this->hasOne(Profession::class);
    }

    public function responsibility()
    {
        return $this->hasOne(Responsibility::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'believer_language')
            ->withPivot(['lu', 'parle', 'ecrit']);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'believer_group')->withPivot('joined_at');
    }
    public function groupsLed(): HasMany
    {
        return $this->hasMany(Group::class, 'leader_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'believer_team')->withPivot('joined_at');
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }
 
    public function departures()
    {
        return $this->hasMany(Departure::class);
    }

    public function worshipGroups()
    {
        return $this->belongsToMany(WorshipGroup::class, 'believer_worship_group')
            ->withPivot('joined_at');
    }
    public function worshipGroupsLed(): HasMany
    {
        return $this->hasMany(WorshipGroup::class, 'leader_id');
    }

    public function cells()
    {
        return $this->belongsToMany(Cell::class, 'believer_cell')->withPivot('joined_at');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function serviceAssignments()
    {
        return $this->hasMany(ServiceAssignment::class);
    }

    public function teamsLed(): HasMany
    {
        return $this->hasMany(Team::class, 'leader_id');
    }
    // Comite Membership relation
    public function comiteMembership()
    {
        return $this->hasMany(Comite::class);
    }
    // Construction Membership relation
    public function constructionMembership()
    {
        return $this->hasMany(EquipeConstruction::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeSearch($query, ?string $search)
    {
        if (! $search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('lastname', 'like', "%{$search}%")
              ->orWhere('firstname', 'like', "%{$search}%")
              ->orWhere('cni_number', 'like', "%{$search}%");
        });
    }

    public function scopeByGender($query, ?string $gender)
    {
        return $gender ? $query->where('gender', $gender) : $query;
    }

    public function scopeByMaritalStatus($query, ?string $status)
    {
        return $status ? $query->where('marital_status', $status) : $query;
    }

    public function scopeByWorshipGroup($query, ?int $worshipGroupId)
    {
        return $worshipGroupId
            ? $query->whereHas('worshipGroups', fn($q) => $q->where('worship_groups.id', $worshipGroupId))
            : $query;
    }

    public function scopeByAgeGroup($query, ?string $group)
    {
        if (! $group) return $query;

        return match ($group) {
            'nourrisson'   => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= 2'),
            'pre_scolaire' => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 3 AND 4'),
            'ecodim'       => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 5 AND 18'),
            'jeunes'       => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 19 AND 40'),
            'adultes'      => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 41'),
            default        => $query,
        };
    }

    public function scopeByTeam($query, ?int $teamId)
    {
        return $teamId
            ? $query->whereHas('teams', fn($q) => $q->where('teams.id', $teamId))
            : $query;
    }

    protected static function booted()
    {
        static::deleting(function (Believer $believer) {
            if (!$believer->isForceDeleting()) {
                return; // soft delete : on garde le fichier
            }
            if ($believer->profile_picture) {
                \Storage::disk('public')->delete($believer->profile_picture);
            }
        });

        static::creating(function (Believer $believer) {
            if (empty($believer->register_number)) {
                $believer->register_number = self::generateRegisterNumber();
            }
        });
    }

    /**
     * GENERATE REGISTER NUMBER
     */
    private const CHURCH_CODE = 'YOPNB';

    /**
     * Génère un matricule unique au format AA-CCCCCNNLL
     * Ex: 26-YOPNB01BS
     * - AA   : 2 derniers chiffres de l'année en cours
     * - CCCC : code de l'église (YOPNB)
     * - NN   : 2 chiffres aléatoires (00-99)
     * - LL   : 2 lettres aléatoires (A-Z)
    */

    public static function generateRegisterNumber(): string
    {
        $year = now()->format('y'); // 2 derniers chiffres de l'année

        do {
            $digits = str_pad((string) random_int(0, 99), 2, '0', STR_PAD_LEFT); // 2 chiffres aléatoires
            $lettres = collect(range('A', 'Z'))->random(2)->implode(''); // 2 lettres aléatoires
            $candidate = "{$year}-" . self::CHURCH_CODE . "{$digits}{$lettres}";
        } while (Believer::where('register_number', $candidate)->exists());

        return $candidate;
    }
}