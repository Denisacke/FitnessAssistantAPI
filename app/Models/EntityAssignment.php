<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityAssignment extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'entity_id',
        'entity_type',
        'trainer_id',
        'client_id',
    ];
}
