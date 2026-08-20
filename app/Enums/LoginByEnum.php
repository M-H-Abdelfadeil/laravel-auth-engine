<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum LoginByEnum: string
{
    use EnumHelpers;

    case EMAIL  = 'email';
    case MOBILE = 'mobile';
    case ALL    = 'all';

}
