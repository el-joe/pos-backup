<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant\CashRegister;
use App\Services\BranchService;
use App\Services\CashRegisterService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
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
        if(adminCan('cash_register.create')){
            if (!$cashRegister && !$isOnOpenRoute && count($branches) > 0 && admin()->type == 'admin') {
                return redirect()->route('admin.cash.register.open')->with('warning', 'You must open a cash register before proceeding.');
            }
        }

        Blade::directive('adminCan', function ($expression) {
            return "<?php if(adminCan($expression)): ?>";
        });

        Blade::directive('endadminCan', function () {
            return "<?php endif; ?>";
        });


        return $next($request);
    }
}
