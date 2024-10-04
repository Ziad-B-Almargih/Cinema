<?php

namespace App\Models;

use App\Enums\MovieType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Movie
 *
 * @property int $id                    // The primary key (auto-incrementing ID)
 * @property int $hall_id               // Foreign key to the hall this movie is shown in
 * @property string $name               // The name of the movie
 * @property string $description        // A description of the movie's plot
 * @property string $thumbnail          // Path to the movie's thumbnail image
 * @property float $standard_price      // Price for a standard seat
 * @property float $vip_price           // Price for a VIP seat
 * @property MovieType $type            // Enum value indicating the type of movie (e.g., 'action', 'comedy')
 * @property Carbon $showing_date // The date the movie is showing
 * @property Carbon $start_time   // The start time of the movie
 * @property Carbon $end_time     // The end time of the movie
 * @property-read Hall $hall              // The hall the movie is shown in
 * @property-read Reservation[] $reservations // Collection of reservations for the movie
 * @property-read Actor[] $actors // Collection of actors associated with the movie
 * @property-read Trailer[] $trailers // Collection of trailers for the movie
 * @property int $emptyStandard         // The number of available standard seats for this movie
 * @property int $emptyVIP              // The number of available VIP seats for this movie
 */
class Movie extends Model
{
    protected $fillable = [
        'hall_id',
        'name',
        'description',
        'thumbnail',
        'standard_price',
        'vip_price',
        'type',
        'showing_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'type' => MovieType::class,
    ];

    /**
     * Get the hall where the movie is being shown.
     *
     * @return BelongsTo
     */
    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class)->withTrashed();
    }

    /**
     * Get the reservations for the movie.
     *
     * @return HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the actors associated with the movie.
     *
     * @return HasMany
     */
    public function actors(): HasMany
    {
        return $this->hasMany(Actor::class);
    }

    /**
     * Get the trailers for the movie.
     *
     * @return HasMany
     */
    public function trailers(): HasMany
    {
        return $this->hasMany(Trailer::class);
    }

    /**
     * Get the number of available standard seats.
     *
     * @return Attribute
     */
    public function emptyStandard(): Attribute
    {
        return Attribute::get(fn () => $this->hall->standard_seats - $this->reservations()->sum('standard_seats'));
    }

    /**
     * Get the number of available VIP seats.
     *
     * @return Attribute
     */
    public function emptyVIP(): Attribute
    {
        return Attribute::get(fn () => $this->hall->vip_seats - $this->reservations()->sum('vip_seats'));
    }
}
