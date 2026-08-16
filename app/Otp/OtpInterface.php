<?php
namespace App\Otp;

interface OtpInterface
{
    public function send(OtpData $data);
}
