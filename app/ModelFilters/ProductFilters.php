<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait ProductFilters
{
    /**
     * @param Builder $builder
     * @param         $value
     *
     * @return Builder
     */
    public function filterCustomName(Builder $builder, $value): Builder
    {
        return $builder->where('name', 'like', '%' . $value . '%');
    }
}
