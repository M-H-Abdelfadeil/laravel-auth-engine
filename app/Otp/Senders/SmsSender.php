<?php

namespace App\Otp\Senders;
use App\Otp\OtpData;
use App\Otp\OtpInterface;
use Exception;
class SmsSender implements OtpInterface
{
    public function send(OtpData $data): void
    {
        // TODO: Implement send() method.
    }
}
