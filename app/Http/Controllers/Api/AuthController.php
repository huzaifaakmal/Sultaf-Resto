<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\JwtAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(protected JwtAuthService $jwtAuthService) {}

    /**
     * Authenticate and issue signed JWT with role and permission scopes.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'error'   => 'invalid_credentials',
                'message' => 'Invalid email or password.',
            ], 401);
        }

        try {
            $token = $this->jwtAuthService->createTokenForUser($user);
            $ttlMinutes = (int) config('jwt.ttl', 60);

            return response()->json([
                'success'      => true,
                'token_type'   => 'bearer',
                'access_token' => $token,
                'expires_in'   => $ttlMinutes * 60,
                'user'         => [
                    'id'        => $user->id_user,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'role'      => $user->role,
                    'scopes'    => $this->jwtAuthService->resolveRoleScopes($user->role),
                ],
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'token_creation_failed',
                'message' => 'Could not create authorization token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh an existing valid JWT token.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = $this->jwtAuthService->refreshToken();
            $ttlMinutes = (int) config('jwt.ttl', 60);

            return response()->json([
                'success'      => true,
                'token_type'   => 'bearer',
                'access_token' => $newToken,
                'expires_in'   => $ttlMinutes * 60,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'refresh_failed',
                'message' => 'Token refresh failed or token blacklisted: ' . $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Invalidate (blacklist) the incoming JWT token.
     */
    public function logout(): JsonResponse
    {
        try {
            $this->jwtAuthService->invalidateToken();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out and token invalidated.',
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'logout_failed',
                'message' => 'Failed to logout and invalidate token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authenticated user profile with claims.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in context.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => [
                'id'        => $user->id_user,
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'phone'     => $user->no_hp,
                'points'    => $user->points,
                'scopes'    => $this->jwtAuthService->resolveRoleScopes($user->role),
            ],
        ], 200);
    }
}
