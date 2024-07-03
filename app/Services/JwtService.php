<?php

namespace App\Services;

use App\Models\JwtToken;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use App\Models\User;
use App\Services\BaseService as Service;

class JwtService extends Service
{
    protected Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(storage_path('keys/private_key.pem')),
            InMemory::file(storage_path('keys/public_key.pem'))
        );
    }

    /**
     * @param array<string, mixed> $accessTokenClaims
     * @param array<string, mixed> $refreshTokenClaims
     * @return array<string, string>
     */
    public function createTokenPair(array $accessTokenClaims, array $refreshTokenClaims): array
    {
        $accessToken = $this->createAccessToken($accessTokenClaims);
        $refreshToken = $this->createRefreshToken($refreshTokenClaims);
        $persistedAccessToken = $this->persistToken($accessToken);
        $this->persistToken($refreshToken)->fillPermittedTokens($persistedAccessToken->id);

        return [
            'access_token' => $accessToken->toString(),
            'refresh_token' => $refreshToken->toString()
        ];
    }

    /**
     * @param array<string, mixed> $claims
     * @return UnencryptedToken
     */
    public function createAccessToken(array $claims): UnencryptedToken
    {
        return $this->createToken(['grant_type' => 'access_token', ...$claims]);
    }

    /**
     * @param array<string, mixed> $claims
     * @return UnencryptedToken
     */
    public function createRefreshToken(array $claims): UnencryptedToken
    {
        return $this->createToken(['grant_type' => 'refresh_token', ...$claims], '+1 month');
    }


    /**
     * @param array<string, mixed> $claims
     * @param string $expiresAt
     * @return UnencryptedToken
     */
    protected function createToken(array $claims, string $expiresAt = '+1 hour'): UnencryptedToken
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->issuedAt($now)
            ->expiresAt($now->modify($expiresAt))
            ->withClaim('unique_id', Str::uuid())
            ->withClaim('user_uuid', $claims['user_uuid'])
            ->withClaim('uid', $claims['uid']);

        if (isset($claims['grant_type'])) {
            $token = $token->withClaim('grant_type', $claims['grant_type']);
        }

        return $token->getToken(
            $this->config->signer(),
            $this->config->signingKey()
        );
    }

    /**
     * @param string $token
     * @return bool
     */
    public function verifyToken(string $token): bool
    {
        try {
            return $this->config->validator()->validate(
                $this->parseToken($token),
                new SignedWith(
                    $this->config->signer(),
                    $this->config->verificationKey()
                )
            );
        } catch (CannotDecodeContent) {
            return false;
        }
    }

    /**
     * @param string $token
     * @return UnencryptedToken|Token|null
     */
    public function parseToken(string $token): UnencryptedToken|Token|null
    {
        return $this->config->parser()->parse($token);
    }

    protected function persistToken(UnencryptedToken $token): JwtToken
    {
        $tokenClaims = $token->claims()->all();
        $user = User::where('uuid', $tokenClaims['user_uuid'])->first();

        $tokenData = [
            'unique_id' => $tokenClaims['unique_id'],
            'token_title' => sprintf(
                'user-%s-%s',
                $user->id,
                $tokenClaims['iat']->getTimestamp()
            ),
            'expires_at' => $tokenClaims['exp']->format('Y-m-d H:i:s'),
        ];

        return $user->jwtTokens()->create($tokenData);
    }
}
