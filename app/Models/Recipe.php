<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory, Filterable;
    protected $fillable = [
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
        'user_id',
        'created_by',
    ];
    private static array $whiteListFilter = ['*'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'recipe_to_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
