<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class);
    }
}
