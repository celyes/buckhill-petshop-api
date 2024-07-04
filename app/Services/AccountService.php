<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\JwtToken;
use App\Models\User;
use App\Services\BaseService as Service;
use Illuminate\Contracts\Auth\Authenticatable;
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

        if (!Hash::check($password, $user->password)) {
            return false;
        }

        return $this->createUserTokenPair($user);
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $user = User::create($data);

        // In a real-world application, an Email/SMS is sent to the user...
        $user->markEmailAsVerified();

        return $this->createUserTokenPair($user);
    }

    /**
     * @param User $user
     * @return array
     */
    protected function createUserTokenPair(User $user): array
    {
        $additionalClaims = ['user_uuid' => $user->uuid, 'uid' => $user->id];
        return [
            'user'  => new UserResource($user),
            ...$this->jwtService->createTokenPair(
                $additionalClaims,
                $additionalClaims
            )
        ];
    }

    public function logout(string $token): bool
    {
        $token = $this->jwtService->parseToken($token);
        $token = JwtToken::where('unique_id', $token->claims()->get('unique_id'))->first();
        return $token->revoke();
    }
}
