<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmAttendancePermissionCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!adminCan('hrm_attendance.list') && !adminCan('hrm_attendance.create') && !adminCan('hrm_attendance.update') && !adminCan('hrm_attendance.delete') && !adminCan('hrm_attendance.approve')) {
            abort(403);
        }

        return $next($request);
    }
}
