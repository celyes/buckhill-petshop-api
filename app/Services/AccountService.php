<?php

namespace App\Services;

use App\Models\User;
use App\Services\BaseService as Service;
use Illuminate\Support\Facades\Hash;

class AccountService extends Service
{
    public function __construct(protected JwtService $jwtService)
    {
    }

    /**
     * @param string $email
     * @param string $password
     * @return array<string, mixed>|bool
     */
    public function authenticate(string $email, string $password): array|bool
    {
        $user = User::where('email', $email)->firstOrFail();
        $additionalClaims = ['user_uuid' => $user->uuid, 'uid' => $user->id];

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        return [
            'user'  => $user,
            ...$this->jwtService->createTokenPair($additionalClaims, $additionalClaims)
        ];
    }
}
