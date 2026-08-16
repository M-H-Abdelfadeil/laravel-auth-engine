<?php

namespace App\Otp\Senders;

use App\Otp\OtpData;
use App\Otp\OtpInterface;

class WhatsappSender implements OtpInterface
{
    public function send(OtpData $data): void
    {
        //   TODO: send otp to whatsapp

    }
}
