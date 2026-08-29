<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()?->getName();

        $exemptRoutes = [
            'admin.subscriptions.list',
            'admin.login',
            'admin.logout',
            'admin.postLogin',
        ];

        if (in_array($currentRoute, $exemptRoutes, true)) {
            return $next($request);
        }

        if (!Subscription::currentTenantSubscriptions()->first()) {
            return redirect()->route('admin.subscriptions.list')
                ->with('warning', 'Your subscription/trial has ended. Please renew or choose a plan to continue.');
        }

        return $next($request);
    }
}
