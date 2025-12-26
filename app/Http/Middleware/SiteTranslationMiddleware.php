<?php

namespace App\Http\Middleware;

use App\Helpers\SeoHelper;
use Closure;
use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use Symfony\Component\HttpFoundation\Response;

class SiteTranslationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $lang = in_array($request->lang, ['en', 'ar']) ? $request->lang : session('locale',config('app.locale'));
        session(['locale' => $lang]);
        app()->setLocale($lang);

        $seoData = SeoHelper::render('home');

        view()->share('__locale', app()->getLocale());
        view()->share('seoData', $seoData);

        return $next($request);
    }
}
