<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AccountService;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    protected JwtService $jwtService;
    protected AccountService $accountService;

    public function __construct()
    {
        $this->accountService = App::make(AccountService::class);
        $this->jwtService = App::make(JwtService::class);
    }

    /**
     * @param AuthenticateUserRequest $request
     * @return JsonResponse
     */
    public function login(AuthenticateUserRequest $request): JsonResponse
    {
        if (
            $accountData = $this->accountService->authenticate(
                $request->input('email'),
                $request->input('password')
            )
        ) {
            return response()->json([
                'user' => new UserResource($accountData['user']),
                'access_token' => $accountData['access_token'],
                'refresh_token' => $accountData['refresh_token'],
            ]);
        }

        abort(400, "Invalid credentials");
    }
}
