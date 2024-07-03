<?php

namespace App\Models;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $unique_id
 * @property string $token_title
 * @property array $restrictions
 * @property array $permissions
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 * @property DateTimeImmutable $expires_at
 * @property DateTimeImmutable $last_used_at
 * @property DateTimeImmutable $refreshed_at
 * @property bool $is_revoked
 */
class JwtToken extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'unique_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
        'is_revoked',
    ];

    /**
     * @var array<string, string|object>
     */
    protected $casts = [
        'restrictions' => 'json',
        'permissions' => 'json',
        'expires_at' => 'timestamp',
        'last_used_at' => 'timestamp',
        'refreshed_at' => 'timestamp',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function updateLastUsage(): bool
    {
        return $this->forceFill([
            'last_used_at' => now()
        ])->save();
    }

    public function fillPermittedTokens(int|string ...$tokens): bool

    {
        return $this->forceFill([
            'permissions' => [
                'refreshes' => $tokens
            ]
        ])->save();
    }
}
