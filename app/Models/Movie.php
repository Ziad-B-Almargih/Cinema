<?php

namespace App\Models;

use App\Enums\MovieType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory;

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
        'end_time'
    ];

    protected $casts = [
        'type' => MovieType::class,
    ];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class)->withTrashed();
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function actors(): HasMany
    {
        return $this->hasMany(Actor::class);
    }

    public function trailers(): HasMany
    {
        return $this->hasMany(Trailer::class);
    }

    public function emptyStandard(): Attribute
    {
        return Attribute::get(fn () => $this->hall->standard_seats - $this->reservations()->sum('standard_seats'));
    }

    public function emptyVIP(): Attribute
    {
        return Attribute::get(fn() => $this->hall->vip_seats - $this->reservations()->sum('vip_seats'));
    }
}
