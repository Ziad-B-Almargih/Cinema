<?php

namespace App\Traits;

trait HasValues
{
    public static function values(): array
    {
        return collect(self::cases())->pluck('value')->toArray();
    }
}
