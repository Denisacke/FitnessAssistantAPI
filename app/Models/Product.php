<?php

namespace App\Models;

use App\ModelFilters\ProductFilters;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Filterable, ProductFilters;
    private static array $whiteListFilter = ['*'];

    public const WATER_PRODUCT_NAME = 'Apa';
    protected $fillable = [
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
    ];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_to_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

}
