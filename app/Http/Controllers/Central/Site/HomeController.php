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
use App\Services\PlanPricingService;
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

        return landingLayoutView('home',get_defined_vars());
    }

    function blogs($lang = null)
    {
        $seoData = SeoHelper::render('blogs');

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

        return landingLayoutView('faqs', get_defined_vars());
    }

    function contactUsView()
    {
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
        $modules = ['pos', 'hrm', 'booking'];
        $systemData = [];

        foreach ($modules as $module) {
            $modulePlans = ($plansByModule[$module] ?? collect())->values();
            $moduleFeatures = ($featuresByModule[$module] ?? collect())->values();

            $systemData[$module] = [
                'title' => match ($module) {
                    'pos' => 'POS & ERP Systems',
                    'hrm' => 'HRM & Payroll',
                    'booking' => 'Reservation System',
                    default => ucfirst($module),
                },
                'plans' => $modulePlans->map(function (Plan $plan) use ($locale, $moduleFeatures) {
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
                        ->map(function ($planFeature) use ($locale) {
                            $feature = $planFeature->feature;
                            $name = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                            return $name ?: ($feature->name_en ?: $feature->code);
                        })
                        ->unique()
                        ->values()
                        ->take(3)
                        ->all();

                    // If a plan has no enabled/non-empty features yet, fall back to first features of the module.
                    if (count($featureNames) === 0) {
                        $featureNames = $moduleFeatures
                            ->take(3)
                            ->map(function (Feature $feature) use ($locale) {
                                $name = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                                return $name ?: ($feature->name_en ?: $feature->code);
                            })
                            ->values()
                            ->all();
                    }

                    return [
                        'id' => $plan->id,
                        'slug' => $plan->slug,
                        'name' => $plan->name,
                        'desc' => '',
                        'price' => (float) (app(PlanPricingService::class)->calculate($plan, 'month', 1)['final_price'] ?? 0),
                        'yearly' => (float) (app(PlanPricingService::class)->calculate($plan, 'year', 1)['final_price'] ?? 0),
                        'three_months_free' => (bool) ($plan->three_months_free ?? false),
                        'free_trial_months' => (int) (($plan->three_months_free ?? false) ? 3 : 0),
                        'features' => $featureNames,
                        'popular' => (bool) $plan->recommended,
                    ];
                })->all(),
                'table' => $moduleFeatures->map(function (Feature $feature) use ($modulePlans, $locale) {
                    $label = $locale === 'ar' ? ($feature->name_ar ?? null) : ($feature->name_en ?? null);
                    $label = $label ?: ($feature->name_en ?: $feature->code);

                    $values = $modulePlans->map(function (Plan $plan) use ($feature, $locale) {
                        $planFeature = $plan->plan_features->firstWhere('feature_id', $feature->id);
                        if (!$planFeature) {
                            return $feature->type === 'boolean' ? '×' : '—';
                        }

                        if ($feature->type === 'boolean') {
                            return (int) $planFeature->value === 1 ? '✓' : '×';
                        }

                        $content = $locale === 'ar' ? $planFeature->content_ar : $planFeature->content_en;
                        if (is_string($content) && trim($content) !== '') {
                            return trim($content);
                        }

                        $value = $planFeature->value;
                        return is_numeric($value) && (int) $value !== 0 ? (string) (int) $value : '—';
                    })->values()->all();

                    return array_merge([$label], $values);
                })->values()->all(),
            ];
        }

        $seoData = SeoHelper::render('pricing-compare');
        return landingLayoutView('pricing-compare',get_defined_vars());
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
