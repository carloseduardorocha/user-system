<?php

namespace App\Enums;

use App\Traits\IsEnum;

enum TTL: int
{
    use IsEnum;

    case JWT_TOKEN = 120;
}
