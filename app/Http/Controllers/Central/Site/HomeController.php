<?php

namespace App\Http\Controllers\Central\Site;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\Slider;
use App\Services\PlanFeaturePresentationService;
use App\Services\PlanPricingService;
use App\Services\SeoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use RalphJSmit\Laravel\SEO\Support\ImageMeta;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomeController extends Controller
{
    function index($lang = null)
    {
        $sliders = Slider::where('active', true)->orderBy('number', 'asc')->get();
        $blogs = Blog::published()
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $locale = app()->getLocale();

        $seoHtml = SeoService::page([
            'title' => 'Mohaaseb — Cloud ERP, POS & Accounting Software',
            'description' => 'Manage your business with Mohaaseb. Complete POS, accounting, HRM, and contracting ERP system for Arabic and English businesses.',
            'canonical' => url('/' . $locale),
            'og_type' => 'website',
            'locale' => $locale === 'ar' ? 'ar_EG' : 'en_US',
            'hreflang' => [
                ['lang' => 'en', 'url' => url('/en')],
                ['lang' => 'ar', 'url' => url('/ar')],
            ],
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Mohaaseb',
                'url' => url('/'),
                'logo' => asset('light-logo.svg'),
                // No social links config found in the codebase (grepped config/*.php,
                // Setting model) — leave sameAs empty until such a config exists.
                'sameAs' => [],
            ],
        ]);

        return landingLayoutView('home',get_defined_vars());
    }

    function blogs($lang = null)
    {
        $seoData = SeoHelper::render('blogs');

        $locale = app()->getLocale();
        $seoHtml = SeoService::page([
            'title' => 'Blog — Mohaaseb',
            'description' => 'Tips, guides, and updates about business management, accounting, and ERP software.',
            'canonical' => url($locale . '/blogs'),
            'og_type' => 'website',
            'locale' => $locale === 'ar' ? 'ar_EG' : 'en_US',
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Blog',
                'name' => 'Blog — Mohaaseb',
                'url' => url($locale . '/blogs'),
            ],
        ]);

        return landingLayoutView('blogs', get_defined_vars());
    }

    function blogDetailsNoLang($slug)
    {
        return $this->blogDetails(app()->getLocale(), $slug);
    }

    function blogDetails($lang, $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        $imageUrl = $blog->og_image_path;
        $publishedAt = $blog->published_at ?: $blog->created_at;

        $readNextBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $seoData = SeoHelper::render('blog-details', [
            'title' => $blog->title,
            'description' => $blog->excerpt,
            'image' => $imageUrl,
            'published_time' => $publishedAt,
            'modified_time' => $blog->updated_at,
            'slug' => $slug,
            'canonical_url' => url("/{$lang}/blogs/{$slug}"),
            'content' => $blog->content,
        ]);

        $canonical = route('lang.blogs.show', ['lang' => app()->getLocale(), 'slug' => $blog->slug]);
        $ogImage = url($blog->image_path);

        $seoHtml = SeoService::page([
            'title' => $blog->title . ' | Mohaaseb Blog',
            'description' => strip_tags((string) $blog->excerpt),
            'canonical' => $canonical,
            'og_image' => $ogImage,
            'og_type' => 'article',
            'locale' => app()->getLocale() === 'ar' ? 'ar_EG' : 'en_US',
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $blog->title,
                'datePublished' => optional($blog->created_at)->toIso8601String(),
                'dateModified' => optional($blog->updated_at)->toIso8601String(),
                'image' => $ogImage,
                'author' => [
                    '@type' => 'Organization',
                    'name' => 'Mohaaseb',
                ],
            ],
        ]);

        return landingLayoutView('blog-details', get_defined_vars());
    }

    function faqs($lang = null)
    {
        if($lang == null){
            $lang = app()->getLocale() . '-' . session('country', 'eg');
        }
        $faqs = Faq::published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $seoData = SeoHelper::render('faqs', [
            'canonical_url' => url("/{$lang}/faqs"),
            'faq_items' => $faqs->map(fn (Faq $faq) => [
                'question' => $faq->question,
                'answer' => strip_tags($faq->answer),
            ])->all(),
        ]);

        $canonical = url("/{$lang}/faqs");
        $mainEntity = $faqs->map(fn (Faq $faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags((string) $faq->answer),
            ],
        ])->values()->all();

        $seoHtml = SeoService::page([
            'title' => 'Frequently Asked Questions — Mohaaseb',
            'description' => 'Find answers to common questions about Mohaaseb ERP and billing.',
            'canonical' => $canonical,
            'locale' => app()->getLocale() === 'ar' ? 'ar_EG' : 'en_US',
            'schema' => $mainEntity ? [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $mainEntity,
            ] : [],
        ]);

        return landingLayoutView('faqs', get_defined_vars());
    }

    function contactUsView()
    {
        $seoHtml = SeoService::page([
            'title' => 'Contact Us — Mohaaseb',
            'description' => 'Get in touch with the Mohaaseb team for support or inquiries.',
            'canonical' => url('/contact'),
            'locale' => app()->getLocale() === 'ar' ? 'ar_EG' : 'en_US',
        ]);

        return landingLayoutView('contact', get_defined_vars());
    }

    function contactUs(Request $request)
    {
        $request->validate([
            'fname'=>'required|string|max:255',
            'lname'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'phone'=>'required|string|max:50',
            'message'=>'required|string|max:2000',
        ]);

        // You can add logic here to handle the contact form submission,
        // such as sending an email or storing the message in the database.
        $contact = Contact::create([
            'name'=>$request->fname . ' ' . $request->lname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'message'=>$request->message,
        ]);

        Mail::to(env('ADMIN_EMAIL','eljoe1717@gmail.com'))->send(new \App\Mail\ContactUsMail($contact));

        return redirect()->back()->with('success','Your message has been sent successfully.');
    }

    function checkout(Request $request, ?string $token = null)
    {
        $moduleTitles = [
            'pos' => 'POS & ERP System',
            'hrm' => 'HRM System',
            'booking' => 'Booking & Reservations',
        ];

        $decodedToken = is_string($token) && trim($token) !== '' ? decodedData($token) : null;
        if (!is_array($decodedToken)) {
            $decodedToken = [];
        }

        $period = ($decodedToken['period'] ?? $request->query('period')) === 'year' ? 'year' : 'month';
        $requestedSlug = trim((string) ($decodedToken['slug'] ?? $request->query('plan', '')));
        $requestedSystems = collect($decodedToken['systems'] ?? [])->filter(fn ($item) => is_array($item))->values();

        $selectedSystemsSummary = [];
        $selectedFeatureNames = [];
        $selectedPlans = collect();

        if ($requestedSystems->isNotEmpty()) {
            foreach ($requestedSystems as $requestedSystem) {
                $module = (string) ($requestedSystem['module'] ?? '');
                $planSlug = trim((string) ($requestedSystem['slug'] ?? ''));

                if (!in_array($module, ['pos', 'hrm', 'booking'], true) || $planSlug === '') {
                    continue;
                }

                $plan = Plan::query()
                    ->active()
                    ->where('module_name', $module)
                    ->where('slug', $planSlug)
                    ->with(['plan_features.feature' => function ($query) {
                        $query->where('active', true);
                    }])
                    ->first();

                if (!$plan) {
                    continue;
                }

                $selectedPlans->push($plan);
            }
        }

        if ($selectedPlans->isEmpty() && $requestedSlug !== '') {
            $fallbackPlan = Plan::query()
                ->active()
                ->where('slug', $requestedSlug)
                ->with(['plan_features.feature' => function ($query) {
                    $query->where('active', true);
                }])
                ->first();

            if ($fallbackPlan) {
                $selectedPlans->push($fallbackPlan);
            }
        }

        if ($selectedPlans->isEmpty()) {
            $fallbackPlan = Plan::query()
                ->active()
                ->with(['plan_features.feature' => function ($query) {
                    $query->where('active', true);
                }])
                ->orderByDesc('recommended')
                ->orderBy('price_month')
                ->first();

            if ($fallbackPlan) {
                $selectedPlans->push($fallbackPlan);
            }
        }

        $selectedSystemsCount = max(1, $selectedPlans->count());
        foreach ($selectedPlans as $plan) {
            $pricing = app(PlanPricingService::class)->calculate($plan, $period, $selectedSystemsCount);
            $price = (float) ($pricing['final_price'] ?? 0);
            $freeTrialMonths = (int) ($pricing['free_trial_months'] ?? 0);

            $selectedSystemsSummary[] = [
                'module' => is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name,
                'module_title' => $moduleTitles[is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name] ?? ucfirst((string) $plan->module_name),
                'plan_name' => $plan->name,
                'price' => $price,
                'free_trial_months' => $freeTrialMonths,
                'payable_now' => $freeTrialMonths > 0 ? 0.0 : $price,
            ];

            $featureNames = $plan->plan_features
                ->filter(function ($planFeature) {
                    if (!$planFeature->feature) {
                        return false;
                    }

                    if ($planFeature->feature->type === 'boolean') {
                        return (int) $planFeature->value === 1;
                    }

                    return ((int) $planFeature->value > 0)
                        || (is_string($planFeature->content_en) && trim($planFeature->content_en) !== '')
                        || (is_string($planFeature->content_ar) && trim($planFeature->content_ar) !== '');
                })
                ->sortBy('feature_id')
                ->map(function ($planFeature) {
                    $feature = $planFeature->feature;
                    $name = app()->getLocale() === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                    return $name ?: ($feature->name_en ?: $feature->code);
                })
                ->unique()
                ->values()
                ->take(4)
                ->all();

            $selectedFeatureNames = array_values(array_unique(array_merge($selectedFeatureNames, $featureNames)));
        }

        $selectedPlan = $selectedPlans->first();
        $selectedPricing = $selectedPlan ? app(PlanPricingService::class)->calculate($selectedPlan, $period, $selectedSystemsCount) : null;
        $selectedPrice = (float) collect($selectedSystemsSummary)->sum('price');
        $selectedDueNow = (float) collect($selectedSystemsSummary)->sum('payable_now');
        $hasAnyFreeTrial = collect($selectedSystemsSummary)->contains(fn ($item) => (int) ($item['free_trial_months'] ?? 0) > 0);

        return landingLayoutView('checkout',get_defined_vars());
    }

    function pricingCompare()
    {
        // The 2-static-plan pricing model has no per-module comparison table anymore.
        return redirect()->route('pricing');
    }

    function pricing()
    {
        $plans = Plan::query()
            ->active()
            ->with(['plan_features.feature' => function ($query) {
                $query->where('active', true);
            }])
            ->orderBy('price_month')
            ->orderBy('id')
            ->get();

        $plansByModule = $plans->groupBy(fn (Plan $plan) => $plan->module_name->value);

        $features = Feature::query()
            ->where('active', true)
            ->orderBy('id')
            ->get();

        $featuresByModule = $features->groupBy('module_name');

        $locale = app()->getLocale();
        $cardFeaturesByModule = collect(['pos', 'hrm', 'booking'])->mapWithKeys(function (string $module) use ($featuresByModule, $locale) {
            $items = ($featuresByModule[$module] ?? collect())
                ->take(2)
                ->map(function (Feature $feature) use ($locale) {
                    $name = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                    return $name ?: ($feature->name_en ?: $feature->code);
                })
                ->values()
                ->all();

            return [$module => $items];
        })->all();

        $pricingPlanFeaturesByPlanId = $plans->mapWithKeys(function (Plan $plan) use ($locale, $featuresByModule) {
            $module = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
            $moduleFeatures = ($featuresByModule[$module] ?? collect())->values();

            $featureNames = app(PlanFeaturePresentationService::class)
                ->resolveDisplayPlanFeatureNames($plan, $moduleFeatures, $locale, 3);

            return [$plan->id => $featureNames];
        })->all();

        $seoData = SeoHelper::render('pricing');
        return landingLayoutView('pricing',get_defined_vars());
    }

    function changeLanguage($locale) {
        if (!in_array($locale, ['en', 'ar'], true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        // redirect to same route but with new lang parameter
        $previousUrl = url()->previous();
        // refactor this to be like en-us or ar-eg
        $newUrl = preg_replace('/\/(en|ar)(-[a-zA-Z]{2})?/', '/' . $locale, $previousUrl);

        // if(str_contains($previousUrl, '/en/') || str_contains($previousUrl, '/ar/')){
        //     $newUrl = preg_replace('/\/(en|ar)\//', '/' . $locale . '/', $previousUrl);
        // }else{
        //     if($previousUrl == url('/')){
        //         $newUrl = url('/' . $locale);
        //     }else{
        //         $newUrl = $previousUrl;
        //     }
        // }
        return redirect($newUrl);
    }
}
