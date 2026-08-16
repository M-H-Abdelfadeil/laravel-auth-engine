<?php

namespace App\Otp;

class OtpData
{
    public function __construct(
        public readonly string  $code,
        public readonly string  $recipient,
        public readonly string  $purpose,
        public readonly bool  $isSendRealOTP,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            recipient: $data['recipient'],
            purpose: $data['purpose'],
            isSendRealOTP: $data['isSendRealOTP'],
        );
    }
}
