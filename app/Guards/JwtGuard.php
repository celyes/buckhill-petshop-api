<?php

namespace App\Guards;

use App\Services\JwtService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    public function __construct(
        protected UserProvider $provider,
        protected JwtService $jwtService,
        protected ?Authenticatable $user = null
    ) {
    }

    public function check(): bool
    {
        return !is_null($this->user());
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = request()->bearerToken();

        if ($token && $this->jwtService->verifyToken($token)) {
            $parsedToken = $this->jwtService->parseToken($token);
            $this->user = $this->provider->retrieveById($parsedToken->claims()->all()['uid']);

            return $this->user;
        }

        return null;
    }

    public function id(): string|int|null
    {
        if ($user = $this->user()) {
            return $user->getAuthIdentifier();
        }

        return null;
    }

    /**
     * @param array<string, string> $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return false;
        }

        // Retrieve user by credentials (usually email)
        $user = $this->provider->retrieveByCredentials($credentials);

        // Check if the user exists and the password matches
        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            return true;
        }

        return false;
    }

    public function setUser(Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function hasUser(): bool
    {
        return !is_null($this->user);
    }
}
