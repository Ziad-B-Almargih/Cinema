<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * Class Trailer
 *
 * @property int $id                    // The primary key (auto-incrementing ID)
 * @property int $movie_id              // Foreign key to the movie associated with the trailer
 * @property string $video              // Path or URL to the trailer video
 * @property-read Movie $movie // The movie associated with the trailer
 */
class Trailer extends Model
{
    protected $fillable = [
        'movie_id',
        'video',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
