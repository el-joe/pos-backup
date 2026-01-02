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
        $langAndCountryFromRequest = explode('-', $request->lang);
        $lang = in_array($langAndCountryFromRequest[0], ['en', 'ar']) ? $langAndCountryFromRequest[0] : session('locale','en');
        session(['locale' => $lang]);
        session(['country' => strtolower($langAndCountryFromRequest[1] ?? 'eg')]);
        app()->setLocale($lang);

        $seoData = SeoHelper::render('home');

        view()->share('__locale', app()->getLocale());
        view()->share('__country', session('country', 'eg'));
        view()->share('seoData', $seoData);

        return $next($request);
    }
}
