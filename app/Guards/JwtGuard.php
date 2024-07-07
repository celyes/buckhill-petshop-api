<?php

namespace App\Guards;

use App\Services\JwtService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class JwtGuard implements Guard
{
    public function __construct(
        protected UserProvider $provider,
        protected JwtService $jwtService,
        protected ?Authenticatable $user = null
    ) {}

    public function check(): bool
    {
        return ! is_null($this->user());
    }

    public function guest(): bool
    {
        return ! $this->check();
    }

    public function user(): ?Authenticatable
    {
        if ($this->user) {
            return $this->user;
        }

        $token = request()->bearerToken();

        if (! $token || ! $this->jwtService->verifyToken($token)) {
            return null;
        }
        $parsedToken = $this->jwtService->parseToken($token);
        $this->user = $this->provider->retrieveById($parsedToken->claims()->all()['uid']);

        return $this->user;
    }

    public function id(): string|int|null
    {
        if ($user = $this->user()) {
            return $user->getAuthIdentifier();
        }

        return null;
    }

    /**
     * @param  array<string, string>  $credentials
     */
    public function validate(array $credentials = []): bool
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

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
        return ! is_null($this->user);
    }
}
