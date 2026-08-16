<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\V1\AuthenticatedUserResource;
use App\Http\Services\GeneralService;
use App\Http\Services\ResponseService;
use App\Repositories\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterController extends Controller
{

    public function __construct(
        private UserService $userService,
    ){}
     public function register(RegisterRequest $request)
    {

        return GeneralService::safeTransaction(function () use ($request) {
            $data = $request->validated();
            $user = $this->userService->create($data);
            event(new UserRegistered($user));
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;
            return ResponseService::sendResponseSuccess(new AuthenticatedUserResource($user), Response::HTTP_OK, 'Account created successfully');
        });
    }
}
