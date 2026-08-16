<?php

namespace App\Otp;

use App\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OtpManager
{

    public static function make(string $sender):OtpInterface
    {
        $class = __NAMESPACE__ . '\\Senders\\' . ucfirst($sender) . 'Sender';
        if (!class_exists($class)) {
            throw new InvalidArgumentException("Gateway [{$sender}] notfound");
        }
        return app($class);
    }


    public static function generateOTP($length = 6)
    {

        if (!config('otp.use_random_otp')) {
            return config('otp.static_otp_code');
        }

        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }


    public static function isSendRealOTP()
    {
        return config('otp.is_send_real_otp');
    }


    public static function expiresAt($minutes = 10){
        return now()->addMinutes($minutes);
    }






}
