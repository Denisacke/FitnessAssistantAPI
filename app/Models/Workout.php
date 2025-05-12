<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Workout extends Model
{
    use HasFactory, Filterable;

    private static array $whiteListFilter = ['*'];
    protected $fillable = ['name', 'user_id', 'created_by'];

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class)
            ->withPivot(['sets', 'reps'])
            ->withTimestamps();
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }
}
