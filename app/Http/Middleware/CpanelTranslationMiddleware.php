<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CpanelTranslationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(session('locale', config('app.locale')));
        view()->share('__locale', app()->getLocale());

        return $next($request);
    }

}
