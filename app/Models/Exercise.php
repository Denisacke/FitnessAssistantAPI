<?php

namespace App\Models;

use App\Http\Enums\ExerciseCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'met_coefficient', 'category'];

    protected $casts = [
        'met_coefficient' => 'double',
        'category' => ExerciseCategory::class, // Cast category to Enum
    ];

    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class);
    }
}
