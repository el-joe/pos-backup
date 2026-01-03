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

        $countryCode = $request->header('CF-IPCountry') ?? Location::get($request->ip())?->countryCode;
        if($request->has('dd')){
            dd($countryCode);
        }

        if(in_array($langAndCountryFromRequest[0], ['en', 'ar'])){
            $lang = $langAndCountryFromRequest[0];
            $country = $langAndCountryFromRequest[1] ?? null;
        }else{
            $lang = session('locale','en');
            $country = session('country','eg');
        }
        session(['locale' => $lang]);
        session(['country' => $country]);
        app()->setLocale($lang);
        $seoData = SeoHelper::render('home');

        view()->share('__locale', $lang);
        view()->share('__country', strtolower($country));
        view()->share('__currentLang', SeoHelper::UrlLang());
        view()->share('seoData', $seoData);

        return $next($request);
    }
}
