<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth(EMPLOYEES_GUARD)->check()) {
            return redirect()->route('employee.login');
        }

        $employee = employee();
        if (($employee->status ?? null) !== 'active') {
            auth(EMPLOYEES_GUARD)->logout();
            session()->flush();

            return redirect()->route('employee.login')->with('error', 'Your account is not active. Please contact HR.');
        }

        return $next($request);
    }
}
