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
        'birth_date',
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
