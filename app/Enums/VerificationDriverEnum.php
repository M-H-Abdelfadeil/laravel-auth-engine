<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum VerificationDriverEnum: string
{
    use EnumHelpers;

    case SMTP = 'smtp';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';

}
