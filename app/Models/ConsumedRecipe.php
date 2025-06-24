<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumedRecipe extends Model
{
    protected $fillable = [
        'recipe_id',
        'user_id',
        'quantity',
        'consumed_at',
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
