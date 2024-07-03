<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Services\AccountService;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if(
            $accountData = $this->accountService->authenticate(
                $request->input('email'),
                $request->input('password')
            )
        ) {
            return response()->json($accountData);
        }
        abort(400, 'Invalid credentials');

    }

    public function create(CreateUserRequest $request)
    {
        try {
            $accountData = $this->accountService->create($request->validated());
            return response()->json($accountData, 201);
        } catch (HttpException) {
            abort(401, 'Invalid credentials');
        }
    }
}
