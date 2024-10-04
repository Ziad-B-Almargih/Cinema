<?php

namespace App\Enums;

use App\Traits\HasValues;

enum Role: string
{
    use HasValues;
    case USER = 'user';
    case ADMIN = 'admin';
}
