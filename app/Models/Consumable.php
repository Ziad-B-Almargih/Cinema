<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Consumable
 *
 * @property int $id           // The primary key (auto-incrementing ID)
 * @property string $name      // The name of the consumable (e.g., food, drink)
 * @property string $type      // The type of consumable (e.g., 'food', 'drink')
 * @property float $price      // The price of the consumable
 * @property Carbon|null $deleted_at // Timestamp for soft deletion, if applicable
 */
class Consumable extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'price',
    ];
}
