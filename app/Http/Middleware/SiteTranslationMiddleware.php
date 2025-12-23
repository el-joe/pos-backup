<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use RalphJSmit\Laravel\SEO\Support\ImageMeta;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Symfony\Component\HttpFoundation\Response;

class SiteTranslationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(session('locale', config('app.locale')));
        view()->share('__locale', app()->getLocale());

        $alternates = [
            new AlternateTag('en', url('/en')),
            new AlternateTag('ar', url('/ar')),
        ];

        $seoData = new SEOData(
            title : __('website.titles.home'),
            description : __('website.meta_description'),
            author: 'codefanz.com',
            image : asset('favicon_io/apple-touch-icon.png'),
            url : url()->current(),
            robots : 'index, follow',
            canonical_url : url('/'),
            enableTitleSuffix: true,
            type: "website",
            site_name: "Mohaaseb",
            favicon: asset('favicon_io/favicon.ico'),
            locale: app()->getLocale() == 'en' ? 'en_US' : 'ar_AR',

            openGraphTitle: __('website.titles.home'),
            imageMeta: new ImageMeta(asset('favicon_io/apple-touch-icon.png')),

            twitter_username: "@mohaaseb",

            // المقالات/الصفحات الديناميكية
            published_time: Carbon::now(), // مثال للصفحات الجديدة
            modified_time: Carbon::now(),
            articleBody: null,
            section: null,
            tags: ['ERP', 'POS', 'Accounting', 'Inventory', 'Business Management'],

            // Schema (JSON-LD)
            schema: new SchemaCollection([
                [
                    "@context" => "https://schema.org",
                    "@type" => "SoftwareApplication",
                    "name" => "Mohaaseb ERP",
                    "applicationCategory" => "BusinessApplication",
                    "operatingSystem" => "Web",
                    "url" => url('/'),
                    "offers" => [
                        "@type" => "Offer",
                        "price" => "0",
                        "priceCurrency" => "USD"
                    ],
                    'image' => asset('favicon_io/apple-touch-icon.png'),
                    "description" => __('website.meta_description'),
                ]
            ]),

            alternates: $alternates
        );

        view()->share('seoData', $seoData);

        return $next($request);
    }
}
