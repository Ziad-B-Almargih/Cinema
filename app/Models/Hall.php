<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hall
 *
 * @property int $id                    // The primary key (auto-incrementing ID)
 * @property string $name               // The name of the hall
 * @property int $standard_seats        // The number of standard seats in the hall
 * @property int $vip_seats             // The number of VIP seats in the hall
 *
 * @property-read Movie[] $movies // Collection of movies associated with the hall
 */
class Hall extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'standard_seats',
        'vip_seats',
    ];

    /**
     * Get the movies associated with the hall.
     *
     * @return HasMany
     */
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class, 'hall_id');
    }
}
