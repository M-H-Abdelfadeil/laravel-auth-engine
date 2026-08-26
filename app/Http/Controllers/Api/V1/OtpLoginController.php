<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OtpLoginRequestedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OtpLoginRequest;
use App\Http\Requests\Api\V1\OtpLoginVerifyRequest;
use App\Http\Resources\V1\AuthenticatedUserResource;
use App\Http\Services\GeneralService;
use App\Http\Services\ResponseService;
use App\Repositories\Services\UserService;
use Illuminate\Http\Response;

class OtpLoginController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function send(OtpLoginRequest $request)
    {
        return GeneralService::safeTransaction(function () use ($request) {
            $user = $this->userService->findUserForLogin($request->validated());
            if (! $user) {
                return ResponseService::sendBadRequest('An error occurred or the identifier may not exist in our records. Please make sure you have an account.');
            }
            event(new OtpLoginRequestedEvent($user));

            return ResponseService::sendResponseSuccess(null, Response::HTTP_OK, 'Code sent successfully. Please verify it.');
        });
    }

    public function verify(OtpLoginVerifyRequest $request)
    {
        return GeneralService::safeTransaction(function () use ($request) {
            $user = $this->userService->loginByOtp($request->validated());
            if (! $user) {
                return ResponseService::sendBadRequest('invalid code or code has expired. Please try again.');
            }
            return ResponseService::sendResponseSuccess(new AuthenticatedUserResource($user), Response::HTTP_OK, 'Login successful. Welcome back!');
        });
    }
}
