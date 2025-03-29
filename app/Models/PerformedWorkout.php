<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformedWorkout extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'workout_id', 'performed_date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function performedExercises(): HasMany
    {
        return $this->hasMany(PerformedExercise::class);
    }
}
