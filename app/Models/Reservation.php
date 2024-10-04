<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * Class Reservation
 *
 * @property int $id                    // The primary key (auto-incrementing ID)
 * @property int $user_id               // Foreign key to the user who made the reservation
 * @property int $movie_id              // Foreign key to the movie associated with the reservation
 * @property int $standard_seats        // Number of standard seats reserved
 * @property int $vip_seats             // Number of VIP seats reserved
 * @property-read Movie $movie      // The movie associated with the reservation
 * @property-read User $user        // The user who made the reservation
 * @property-read Consumable[] $consumables // Consumables associated with the reservation
 * @property float $totalPrice          // The total price for the reservation, including seats and consumables
 */
class Reservation extends Model
{

    protected $fillable = [
        'user_id',
        'movie_id',
        'standard_seats',
        'vip_seats',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function consumables(): BelongsToMany
    {
        return $this->belongsToMany(Consumable::class)->withPivot(['price', 'quantity'])->withTrashed();
    }

    public function totalPrice(): Attribute
    {
        return Attribute::get(function (){
            $price = $this->standard_seats * $this->movie->standard_price + $this->vip_seats * $this->movie->vip_price;
            foreach ($this->consumables as $consumable) {
                $price += $consumable->pivot->price * $consumable->pivot->quantity;
            }
            return $price;
        });
    }
}
