<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $fillable = [
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
