<?php

namespace App\Enums;

use App\Traits\HasValues;

enum MovieType: string
{
    use HasValues;
    case ACTION = 'action';
    case COMEDY = 'comedy';
    case DRAMA = 'drama';
    case HORROR = 'horror';
//    case THRILLER = 'thriller';
//    case ROMANCE = 'romance';
//    case FANTASY = 'fantasy';
//    case ANIMATION = 'animation';
//    case DOCUMENTARY = 'documentary';
//    case ADVENTURE = 'adventure';
//    case MYSTERY = 'mystery';
//    case CRIME = 'crime';
//    case BIOGRAPHY = 'biography';
//    case FAMILY = 'family';
}
