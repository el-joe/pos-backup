<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmMasterDataPermissionCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!adminCan('hrm_master_data.list,hrm_master_data.create,hrm_master_data.update,hrm_master_data.delete')) {
            abort(403);
        }

        return $next($request);
    }
}
