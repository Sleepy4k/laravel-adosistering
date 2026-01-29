<?php

namespace App\Enums;

use App\Foundations\Enum;

enum IrrigationType: string
{
    use Enum;

    // Case section started
    case MANUAL = 'manual';
    case AUTOMATIC = 'automatic';
}
