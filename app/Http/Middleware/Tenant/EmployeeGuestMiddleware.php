<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeGuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth(EMPLOYEES_GUARD)->check()) {
            return redirect()->route('employee.dashboard');
        }

        return $next($request);
    }
}
