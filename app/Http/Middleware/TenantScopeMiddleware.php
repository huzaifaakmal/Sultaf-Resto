<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantScopeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID') 
            ?? $request->user()?->tenant_id 
            ?? 1;

        app()->instance('current_tenant_id', (int) $tenantId);

        return $next($request);
    }
}
