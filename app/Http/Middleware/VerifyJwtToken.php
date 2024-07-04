<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App\Models\JwtToken;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Component\HttpFoundation\Response;

class VerifyJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();
        if ($bearerToken && $this->check($bearerToken)) {
            return $next($request);
        }
        abort(401, 'Unauthenticated');
    }

    protected function check(string $token): bool
    {

        $jwtService = App::make(JwtService::class);

        if (!$jwtService->verifyToken($token)) {
            return false;
        }
        $parsedToken = $jwtService->parseToken($token);

        // refresh tokens shouldn't be used to access resources...
        if ($this->isTokenValid($parsedToken)) {
            auth()->setUser(User::where('uuid', $parsedToken->claims()->get('user_uuid'))->first());
            $this->persistedToken($parsedToken)->updateLastUsage();
            return true;
        }
        return false;
    }

    protected function isTokenValid(UnencryptedToken $token): bool
    {
        $claims = $token->claims()->all();
        return $this->isTokenExisting($token)
            && $claims['grant_type'] == 'access_token'
            && new \DateTimeImmutable() < $claims['exp'];
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
