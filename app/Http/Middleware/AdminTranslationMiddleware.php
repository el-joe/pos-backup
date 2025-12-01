<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        app()->setLocale(session('locale', config('app.locale')));
        if(admin()?->id){
            view()->share('__locale', app()->getLocale());
            view()->share('__unreaded_notifications', admin()->unreadNotifications);
        }
        return $next($request);
    }
}
