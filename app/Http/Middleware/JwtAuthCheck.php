<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->check($request->bearerToken())) {
            return $next($request);
        }
        abort(401, 'Unauthenticated');
    }

    protected function check(string $token): bool
    {
        $jwtService = App::make(JwtService::class);
        $now = new \DateTimeImmutable();

        if (!$jwtService->verifyToken($token)) {
            return false;
        }

        $parsedToken = $jwtService->parseToken($token);

        if (
            !$this->isTokenExisting($parsedToken)
            || $parsedToken->claims()->get('grant_type') != 'access_token'
            || $now > $parsedToken->claims()->get('exp')
        ) {
            return false;
        }

        $this->persistedToken($parsedToken)->updateLastUsage();

        return true;
    }

    protected function persistedToken(UnencryptedToken $token): ?JwtToken
    {
        $tokenUniqueId = $token->claims()->get('unique_id');
        return JwtToken::where('unique_id', $tokenUniqueId)->first();
    }

    protected function isTokenExisting(UnencryptedToken $token): bool
    {
        return !is_null($this->persistedToken($token));
    }
}
