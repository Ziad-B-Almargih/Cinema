<?php

namespace App\Enums;

use App\Traits\HasValues;

enum ConsumableType: string
{
    use HasValues;
    case FOOD = 'food';
    case DRINK = 'drink';

    public static function getIcon(string $type): string
    {
        return match(ConsumableType::from($type)) {
            ConsumableType::DRINK => '<i class="fa-solid fa-martini-glass-citrus"></i>',
            ConsumableType::FOOD => '<i class="fa-solid fa-bowl-food"></i>',
        };
    }
}
