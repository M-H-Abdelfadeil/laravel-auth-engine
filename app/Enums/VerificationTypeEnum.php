<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum VerificationTypeEnum: string
{
    use EnumHelpers;
    case EMAIL = 'email';
    case MOBILE = 'mobile';


}
