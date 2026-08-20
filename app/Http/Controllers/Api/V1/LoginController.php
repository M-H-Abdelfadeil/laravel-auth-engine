<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\V1\AuthenticatedUserResource;
use App\Http\Services\ResponseService;
use App\Repositories\Services\UserService;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function login(LoginRequest $request)
    {
        $user = $this->userService->loginBySanctum($request->validated());
        if (! $user) {
            return ResponseService::sendBadRequest('Invalid credentials');
        }
        return ResponseService::sendResponseSuccess(new AuthenticatedUserResource($user), Response::HTTP_OK, 'log in succssfully');
    }
}
