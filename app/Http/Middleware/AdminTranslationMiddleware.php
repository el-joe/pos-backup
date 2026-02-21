<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\Tenant\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AdminTranslationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(tenantSetting('default_language', 'en'));
        view()->share('__locale', app()->getLocale());
        if(admin()?->id){
            view()->share('__unreaded_notifications', admin()->unreadNotifications);
            $currentSubscription = Subscription::currentTenantSubscriptions()->first();
            view()->share('__current_subscription', $currentSubscription);
        }

        return $next($request);
    }
}
