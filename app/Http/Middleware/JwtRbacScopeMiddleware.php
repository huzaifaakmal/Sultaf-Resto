<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtRbacScopeMiddleware
{
    public function handle(Request $request, Closure $next, string ...$requiredScopes): Response
    {
        try {
            // 1. Authenticate user from Bearer Token
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'error'   => 'user_not_found',
                    'message' => 'User belonging to token could not be found.',
                ], 401);
            }

            // 2. Extract Claims & Tenant ID
            $payload = JWTAuth::getPayload();
            $tenantId = $payload->get('tenant_id') ?? $user->tenant_id ?? 1;
            $userScopes = (array) ($payload->get('scopes') ?? []);

            // Bind current tenant to application container for TenantScope
            app()->instance('current_tenant_id', (int) $tenantId);

            // 3. Verify Required Permission Scopes
            if (! empty($requiredScopes)) {
                $hasAdminBypass = in_array('admin:all', $userScopes, true) || $user->role === 'admin';

                if (! $hasAdminBypass) {
                    foreach ($requiredScopes as $scope) {
                        if (! in_array($scope, $userScopes, true)) {
                            return response()->json([
                                'success'        => false,
                                'error'          => 'forbidden_insufficient_scope',
                                'message'        => "Forbidden: Missing required permission scope [{$scope}].",
                                'required_scope' => $scope,
                                'granted_scopes' => $userScopes,
                            ], 403);
                        }
                    }
                }
            }

            // Bind authenticated user to request
            $request->setUserResolver(fn () => $user);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'token_expired',
                'message' => 'JWT Token has expired. Please refresh your token.',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'token_invalid',
                'message' => 'JWT Token is invalid or signature verification failed.',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'error'   => 'token_absent',
                'message' => 'Authorization Bearer Token is missing or unparseable.',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'unauthorized',
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
