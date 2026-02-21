<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HrmPayrollPermissionCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!adminCan('hrm_payroll.list') && !adminCan('hrm_payroll.create') && !adminCan('hrm_payroll.update') && !adminCan('hrm_payroll.delete') && !adminCan('hrm_payroll.approve') && !adminCan('hrm_payroll.pay')) {
            abort(403);
        }

        return $next($request);
    }
}
