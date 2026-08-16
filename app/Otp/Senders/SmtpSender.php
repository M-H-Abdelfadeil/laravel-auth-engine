<?php

namespace App\Otp\Senders;

use App\Mail\VerificationCodeMail;
use App\Otp\OtpData;
use App\Otp\OtpInterface;
use Illuminate\Support\Facades\Mail;

class SmtpSender implements OtpInterface
{
    public function send(OtpData $data): void
    {
        if ($data->isSendRealOTP) {
            Mail::to($data->recipient)->send(new VerificationCodeMail((array) $data));
        }
    }
}
