<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

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
