<?php

namespace App\Enums;

use App\Traits\IsEnum;

/**
 * Enum used to map project error slugs.
 */
enum Error: string
{
    use IsEnum;

    case REQUEST_VALIDATION_ERROR = 'REQUEST_VALIDATION_ERROR';
    case REQUEST_ACTION_FORBIDDEN = 'REQUEST_ACTION_FORBIDDEN';
}
