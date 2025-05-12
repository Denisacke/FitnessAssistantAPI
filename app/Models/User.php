<?php

namespace App\Models;

use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Http\Enums\UserRole;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Filterable;
    private static array $whiteListFilter = ['*'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'sex',
        'weight',
        'height',
        'age',
        'activity_level',
        'body_fat',
        'bmi',
        'role',
        'recommended_calories',
        'recommended_water_intake',
        'trainer_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sex' => Sex::class,
            'weight' => 'double',
            'height' => 'integer',
            'body_fat' => 'integer',
            'age' => 'integer',
            'activity_level' => ActivityLevel::class,
            'role' => UserRole::class,
        ];
    }

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saving(function ($user) {
//            $user->recommended_calories = $user->calculateRecommendedCalories();
//            $user->bmi = $user->computeBMI();
//        });
//    }

//    private function computeBMI(): ?float
//    {
//        if (!$this->weight || !$this->height) {
//            return null;
//        }
//        $heightInMeters = $this->height / 100;
//
//        $bmi = $this->weight / ($heightInMeters ** 2);
//
//        return round($bmi, 1);
//    }
//
//    private function calculateRecommendedCalories(): ?float
//    {
//        if (!$this->weight || !$this->age || !$this->activity_level) {
//            return null;
//        }
//
//        // Example Calculation using Mifflin-St Jeor Equation
//        if ($this->sex === 'male') {
//            $bmr = 10 * $this->weight + 6.25 * 175 - 5 * $this->age + 5;
//        } else {
//            $bmr = 10 * $this->weight + 6.25 * 175 - 5 * $this->age - 161;
//        }
//
//        // Activity levels: sedentary (1.2), light (1.375), moderate (1.55), active (1.725), very active (1.9)
//        $activity_multipliers = [
//            'sedentary' => 1.2,
//            'light' => 1.375,
//            'moderate' => 1.55,
//            'active' => 1.725,
//            'very_active' => 1.9
//        ];
//
//        $multiplier = $activity_multipliers[$this->activity_level->value] ?? 1.2; // Default to sedentary
//
//        return round($bmr * $multiplier);
//    }

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function clients(): HasMany
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }

    public function performedWorkouts(): HasMany
    {
        return $this->hasMany(PerformedWorkout::class);
    }
}
