<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['body_part', 'name', 'gif_url', 'muscle_target', 'instructions'];

    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class);
    }
}
