<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'calories',
        'name',
        'fat',
        'protein',
        'carbs',
        'fibre',
    ];
}
