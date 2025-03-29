<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformedExercise extends Model
{
    use HasFactory;

    protected $fillable = ['exercise_id', 'performed_workout_id', 'sets', 'reps', 'weight', 'performed_date'];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function performedWorkout(): BelongsTo
    {
        return $this->belongsTo(PerformedWorkout::class);
    }
}
