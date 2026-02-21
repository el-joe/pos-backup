<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmLeavesPermissionCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!adminCan('hrm_leaves.list') && !adminCan('hrm_leaves.create') && !adminCan('hrm_leaves.update') && !adminCan('hrm_leaves.approve') && !adminCan('hrm_leaves.reject') && !adminCan('hrm_leaves.delete')) {
            abort(403);
        }

        return $next($request);
    }
}
