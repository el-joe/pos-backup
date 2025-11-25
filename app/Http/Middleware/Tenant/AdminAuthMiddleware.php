<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant\CashRegister;
use App\Services\BranchService;
use App\Services\CashRegisterService;
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

        $cashRegisterService = app()->make(CashRegisterService::class);

        $cashRegister = $cashRegisterService->getOpenedCashRegister();

        $currentRoute = $request->route()->getName();
        $isOnOpenRoute = $currentRoute === 'admin.cash.register.open';
        $isOnOpenRoute = ($isOnOpenRoute || $currentRoute === 'admin.switch.branch');

        if($request->is('admin/reports*')){
            $isOnOpenRoute = true;
        }

        $branches = app(BranchService::class)->activeList();

        if (!$cashRegister && !$isOnOpenRoute && count($branches) > 0) {
            return redirect()->route('admin.cash.register.open')->with('warning', 'You must open a cash register before proceeding.');
        }

        return $next($request);
    }
}
