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

    protected $fillable = [
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
    ];
}
