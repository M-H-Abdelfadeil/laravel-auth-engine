<?php

namespace App\Listeners;

use App\Enums\VerificationPurposeEnum;
use App\Events\UserRegistered;
use App\Repositories\Services\VerificationCodeService;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendVerificationOtp  implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(private VerificationCodeService $verificationCodeService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {

        $this->verificationCodeService->create([
            'user_id' => $event->user->id,
            'purpose' => VerificationPurposeEnum::VERIFY,
            'mobile' => $event->user->mobile,
            'mobile_country_code' => $event->user->mobile_country_code,
            'email' => $event->user->email,
        ]);
    }
}
