<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-Token') ?? $request->query('api_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 401,
            ], 401);
        }

        // The token stored on the admin record IS the plain value shown to the
        // user (see Admin::generateApiToken()) — compare it directly, do not re-hash.
        $admin = Admin::where('api_token', $token)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 401,
            ], 401);
        }

        Auth::guard(TENANT_ADMINS_GUARD)->setUser($admin);

        return $next($request);
    }
}
