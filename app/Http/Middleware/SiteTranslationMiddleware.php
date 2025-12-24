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

        $seoImage = asset('mohaaseb_en_dark_2.webp');

        $seoData = new SEOData(
            title: __('website.titles.home'),
            description: __('website.meta_description'),
            author: 'codefanz.com',

            // ⭐ الصورة الأساسية
            image: $seoImage,

            url: url()->current(),
            robots: 'index, follow',
            canonical_url: url('/'),
            enableTitleSuffix: true,
            type: "website",
            site_name: "Mohaaseb",

            favicon: asset('favicon_io/favicon.ico'),

            locale: app()->getLocale() === 'en' ? 'en_US' : 'ar_AR',

            // ⭐ OpenGraph
            openGraphTitle: __('website.titles.home'),
            imageMeta: new ImageMeta($seoImage),

            // ⭐ Twitter
            twitter_username: "@mohaaseb",

            published_time: Carbon::now(),
            modified_time: Carbon::now(),

            tags: ['ERP', 'POS', 'Accounting', 'Inventory', 'Business Management'],

            // ⭐ Schema
            schema: new SchemaCollection([
                [
                    "@context" => "https://schema.org",
                    "@type" => "SoftwareApplication",
                    "name" => "Mohaaseb ERP",
                    "applicationCategory" => "BusinessApplication",
                    "operatingSystem" => "Web",
                    "url" => url('/'),
                    "image" => $seoImage,
                    "description" => __('website.meta_description'),
                    "offers" => [
                        "@type" => "Offer",
                        "price" => "0",
                        "priceCurrency" => "USD",
                        'priceValidUntil' => Carbon::now()->addYear()->toDateString(),
                    ],
                    "aggregateRating"=> [
                        "@type" => "AggregateRating",
                        "ratingValue" => "4.9",
                        "ratingCount" => "1202"
                    ],
                    "review"=> [
                        "@type" => "Review",
                        "reviewRating" => [
                            "@type" => "Rating",
                            "ratingValue" => "5"
                        ],
                        "author" => [
                                "@type" => "Person",
                                "name" => "John Doe"
                        ],
                        "reviewBody" => __("website.seo.review_body")
                    ]
                ]
            ]),

            alternates: $alternates
        );

        view()->share('seoData', $seoData);

        return $next($request);
    }
}
