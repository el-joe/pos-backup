<?php

namespace App\Http\Middleware;

use App\Helpers\SeoHelper;
use Closure;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\Response;

class SiteTranslationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $langAndCountryFromRequest = explode('-', $request->lang);

        $countryCode = $request->header('CF-IPCountry') ?? Location::get($request->ip())?->countryCode ?? 'EG';

        if(in_array($langAndCountryFromRequest[0], ['en', 'ar'])){
            $lang = $langAndCountryFromRequest[0];
        }else{
            $lang = session('locale','en');
        }
        session(['locale' => $lang]);
        session(['country' => $countryCode]);
        app()->setLocale($lang);
        $seoData = SeoHelper::render('home');

        view()->share('__locale', $lang);
        view()->share('__country', strtolower($countryCode));
        // view()->share('__currentLang', SeoHelper::UrlLang());
        view()->share('__currentLang', $lang);
        view()->share('seoData', $seoData);

        return $next($request);
    }
}
