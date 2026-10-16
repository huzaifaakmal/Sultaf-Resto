<?php

namespace App\Services;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtAuthService
{
    /**
     * Permission scopes granted per role. `admin:all` bypasses every scope check.
     */
    private const ROLE_SCOPES = [
        'admin'    => ['admin:all'],
        'owner'    => ['orders:read', 'reports:read'],
        'kasir'    => ['orders:read', 'orders:write', 'payments:write'],
        'dapur'    => ['orders:read'],
        'customer' => ['orders:read'],
    ];

    public function resolveRoleScopes(?string $role): array
    {
        return self::ROLE_SCOPES[$role] ?? [];
    }

    public function createTokenForUser(User $user): string
    {
        return JWTAuth::claims(['scopes' => $this->resolveRoleScopes($user->role)])->fromUser($user);
    }

    public function refreshToken(): string
    {
        return JWTAuth::parseToken()->refresh();
    }

    public function invalidateToken(): void
    {
        JWTAuth::parseToken()->invalidate();
    }
}
