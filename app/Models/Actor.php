<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Actor
 *
 * @property int $id           // The primary key (auto-incrementing ID)
 * @property int $movie_id     // The foreign key referencing the movie this actor belongs to
 * @property string $name      // The name of the actor
 *
 * @property-read Movie $movie // The related Movie instance
 */
class Actor extends Model
{
    protected $fillable = [
        'movie_id',
        'name',
    ];

    /**
     * Define a relationship to the Movie model.
     *
     * @return BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
