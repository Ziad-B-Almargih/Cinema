<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time'
    ];

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class);
    }
}
