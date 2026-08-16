<?php

return [

    'activation_via' => env('OTP_ACTIVATION_VIA', 'email'),

    'send_via' => env('OTP_SEND_VIA', 'smtp'),

    'expires_in' => (int) env('OTP_EXPIRES_IN', 5),

    'length' => (int) env('OTP_LENGTH', 6),

    'is_send_real_otp' => filter_var(
        env('SEND_REAL_OTP', true),
        FILTER_VALIDATE_BOOLEAN
    ),

    'use_random_otp' => filter_var(
        env('USE_RANDOM_OTP', true),
        FILTER_VALIDATE_BOOLEAN
    ),

    'static_otp_code' => env('STATIC_OTP_CODE', '123456'),

];
