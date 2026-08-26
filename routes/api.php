<?php

use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\OtpLoginController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('login', [LoginController::class, 'login']);

        // One-Time Password (OTP) authentication
        Route::group(['prefix' => 'otp-login'], function () {
            Route::post('send', [OtpLoginController::class, 'send']);
            Route::post('verify', [OtpLoginController::class, 'verify']);
        });

    });

});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
