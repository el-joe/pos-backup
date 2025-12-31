<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToSecure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $intoCentralDomains = in_array($request->getHost(), config('tenancy.central_domains'));
        if ($intoCentralDomains && !$request->isSecure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        // if route is index.html or index.php or index.* file, redirect to secure
        $path = $request->path();
        if (preg_match('/^index\.(html|php)$/', $path) && !$request->isSecure()) {
            return redirect('/', 301);
        }

        return $next($request);
    }
}
