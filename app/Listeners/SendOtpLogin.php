<?php

namespace App\Listeners;

use App\Enums\VerificationPurposeEnum;
use App\Events\OtpLoginRequestedEvent;
use App\Repositories\Services\VerificationCodeService;

class SendOtpLogin
{
    /**
     * Create the event listener.
     */
    public function __construct(private VerificationCodeService $verificationCodeService){ }

    /**
     * Handle the event.
     */
    public function handle(OtpLoginRequestedEvent $event): void
    {
        $this->verificationCodeService->deleteByUserAndPurpose($event->user->id, VerificationPurposeEnum::OTP_LOGIN->value);
        $this->verificationCodeService->create([
            'user_id' => $event->user->id,
            'purpose' => VerificationPurposeEnum::OTP_LOGIN,
            'mobile' => $event->user->mobile,
            'mobile_country_code' => $event->user->mobile_country_code,
            'email' => $event->user->email,
        ]);
    }
}
