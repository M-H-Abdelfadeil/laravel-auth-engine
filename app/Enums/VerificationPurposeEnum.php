<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum VerificationPurposeEnum: string
{
    use EnumHelpers;
    case VERIFY = 'verify';
    case OTP_LOGIN = 'otp_login';
    case RESET_PASSWORD = 'reset_password';
    case CHANGE_EMAIL = 'change_email';
    case CONFIRM_CHANGE_EMAIL = 'confirm_change_email';
    case CHANGE_PHONE = 'change_phone';
    case CONFIRM_CHANGE_PHONE = 'confirm_change_phone';


}
