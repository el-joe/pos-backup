<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant\CashRegister;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $cashRegister = CashRegister::where('status', 'open')
            ->where('branch_id', admin()->branch_id ?? null)
            ->where('admin_id', admin()->id ?? null)
            ->first();

        $currentRoute = $request->route()->getName();
        $isOnOpenRoute = $currentRoute === 'admin.cash.register.open';

        if (!$cashRegister && !$isOnOpenRoute) {
            return redirect()->route('admin.cash.register.open')->with('warning', 'You must open a cash register before proceeding.');
        }

        return $next($request);
    }
}
