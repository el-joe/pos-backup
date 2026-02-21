<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmClaimsPermissionCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!adminCan('hrm_claims.list') && !adminCan('hrm_claims.show') && !adminCan('hrm_claims.create') && !adminCan('hrm_claims.update') && !adminCan('hrm_claims.delete') && !adminCan('hrm_claims.approve') && !adminCan('hrm_claims.reject') && !adminCan('hrm_claims.pay')) {
            abort(403);
        }

        return $next($request);
    }
}
