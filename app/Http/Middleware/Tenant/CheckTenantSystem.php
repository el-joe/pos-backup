<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantSystem
{
    public function handle(Request $request, Closure $next, string $system): Response
    {
        $cacheKey = 'tenant_system_' . tenant('id') . '_' . $system;

        $hasAccess = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($system) {
            return Subscription::where('tenant_id', tenant('id'))
                ->where('status', 'paid')
                ->whereHas('systems', fn ($q) => $q->where('system_slug', $system))
                ->exists();
        });

        if (!$hasAccess) {
            return response()->json(['message' => 'Subscription required for: ' . $system], 403);
        }

        return $next($request);
    }
}
