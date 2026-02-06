<?php


use App\Models\Currency;
use App\Models\Tenant\Admin;
use App\Models\Tenant\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use RalphJSmit\Laravel\SEO\Support\ImageMeta;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\TagManager;

if(!defined('TENANT_ADMINS_GUARD')){
    define('TENANT_ADMINS_GUARD','tenant_admin');
}
if(!defined('CPANEL_ADMINS_GUARD')){
    define('CPANEL_ADMINS_GUARD','cpanel_admin');
}

if(!function_exists('cacheKey')) {
    function cacheKey($key){
        return tenant()->id . '_' . $key;
    }
}

if(!function_exists('defaultLayout')) {
    function defaultLayout(){
        return 'hud';
    }
}

if(!function_exists('defaultLandingLayout')) {
    function defaultLandingLayout(){
        // Central (marketing) landing pages layout.
        // Folder name under: resources/views/central/{layout}/
        return 'gemini-pages';
    }
}

if(!function_exists('layoutView')) {
    function layoutView($pageName,$with = [],$isSubPage = false){
        $defaultView = "livewire." . defaultLayout();
        $defaultLayout = 'layouts.' . defaultLayout();
        $layoutData = isset($with['withoutSidebar']) ? ['withoutSidebar' => $with['withoutSidebar']] : [];
        return view("$defaultView.$pageName", $with)
                ->layout($isSubPage ? null : $defaultLayout, $layoutData);
    }
}

if(!function_exists('landingLayoutView')) {
    function landingLayoutView($pageName,$with = []){
        $layout = defaultLandingLayout();

        // Allow passing either "home" or a fully qualified view name.
        if (is_string($pageName) && str_contains($pageName, '.')) {
            return view($pageName, $with);
        }

        return view("central.{$layout}.{$pageName}", $with);
    }
}


if(!function_exists('admin')) {
    function admin() {
        return auth(TENANT_ADMINS_GUARD)->user();
    }
}

if(!function_exists('cpanelAdmin')) {
    function cpanelAdmin() {
        return auth(CPANEL_ADMINS_GUARD)->user();
    }
}

if(!function_exists('numFormat')) {
    function numFormat($number, $decimals = 2) {
        return number_format((float)$number, $decimals, '.', '');

    }
}

if(!function_exists('branch')) {
    function branch() {
        return auth(TENANT_ADMINS_GUARD)->user()->branch;
    }
}

if(!function_exists('recursiveChildrenForOptions')) {
    function recursiveChildrenForOptions($model,$relationName,$key,$value,$number,$isLastChildCheck = true,$selectedId = null) {
        $arrayOfDashes = array_fill(0,$number,'-- ');
        $dashes = implode('',$arrayOfDashes);

        if(method_exists($model,'isLastChild')){
            $isLastChild = $model->isLastChild();
        }else{
            $isLastChild = $model->{$relationName}->count() == 0;
        }
        // add
        echo '<option value="'. $model->{$key} .'" '. ($isLastChildCheck ? ($isLastChild ? '' : 'disabled') : '') . ($selectedId == $model->{$key} ? 'selected' : '') .'>'. $dashes . $model->{$value} .'</option>';

        $model->{$relationName}->map(fn($model1)=> recursiveChildrenForOptions($model1,$relationName,$key,$value,$number+1,$isLastChildCheck));
    }
}

if(!function_exists('carbon')) {
    function carbon($date) {
        return Carbon::parse($date);
    }
}


if(!function_exists('formattedDateTime')) {
    function formattedDateTime($date): string {
        return carbon($date)->translatedFormat('l , d-M-Y h:i A');
    }
}

if(!function_exists('getMonthsBetween')) {
    function getMonthsBetween($from, $to)
    {
        $from = Carbon::parse($from)->startOfMonth();
        $to = Carbon::parse($to)->startOfMonth();

        $months = [];

        while ($from <= $to) {
            $months[] = $from->copy();
            $from->addMonth();
        }

        return $months;
    }
}
if(!function_exists('defaultPermissionsList')) {
    function defaultPermissionsList() {
        return [
            "statistics"=> ["show"],
            "cash_register"=> ["create"],
            "pos"=> ["create"],
            "branches"=> ["list", "create", "update", "delete",'export',"switch"],
            "products"=> ["list", "show", "create", "update", "delete",'export'],
            "categories"=> ["list", "create", "update", "delete",'export'],
            "brands"=> ["list", "create", "update", "delete",'export'],
            "units"=> ["list", "create", "update", "delete",'export'],
            "stock_transfers"=> ["list","show", "create","export"],
            "stock_adjustments"=> ["list","show", "create",'export'],
            "sales"=> ["list", "show","update", "delete","pay",'show-invoice','export'],
            "customers"=> ["list","show", "create", "update", "delete",'export'],
            "purchases"=> ["list","show", "create", "delete","pay",'export'],
            "suppliers"=> ["list", "show", "create", "update", "delete",'export'],
            "expense_categories"=> ["list", "create", "update", "delete",'export'],
            "expenses"=> ["list", "create", "update", "delete",'export'],
            "payment_methods"=> ["list", "create", "update", "delete",'export'],
            "transactions"=> ["list",'export'],
            "fixed_assets"=> ["list", "show", "create", "update", "delete", "export"],
            "depreciation_expenses"=> ["list", "show", "create", "update", "delete", "export"],
            "user_management"=> ["list", "create", "update", "delete",'export'],
            "role_management"=> ["list", "create", "update", "delete",'export'],
            "reports"=> ["list",'export'],
            'plans' => ['list','assign'],
            'subscriptions' => ['list','cancel','renew'],
            "discounts"=> ["list", "create", "update", "delete",'export'],
            "taxes"=> ["list", "create", "update", "delete",'export'],
            "general_settings"=> ["update"]
        ];

    }
}

if(!function_exists('generateSlug')) {
    function generateSlug($model,$slug) {
        if(!$slug){
            $slug = uniqid();
        }

        $slug = Str::slug(trim($slug), '-');

        $modelObj = $model::whereSlug($slug)->first();
        if($modelObj){
            return generateSlug($model,$slug.'-'.time());
        }

        return $slug;
    }
}

if(!function_exists('adminCan')) {
    function adminCan($permission) {
        if(admin()->type == 'super_admin'){
            return true;
        }

        $permissions = session()->get('admin.permissions');
        if(!$permissions){
            $permissions = admin()->getPermissionsViaRoles()->pluck('name')->toArray();
            session()->put('admin.permissions',$permissions);
        }

        // set permissions values as keys too
        $permissions = array_flip($permissions);

        $permissionArr = explode(',',$permission);

        foreach ($permissionArr as $p) {
            if(isset($permissions[$p])){
                return true;
            }
        }
        return false;
    }
}

if(!function_exists('sidebarHud')) {
    function sidebarHud($data){
        return view('layouts.hud.partials.sidebar-ul',get_defined_vars())->render();
    }
}

if(!function_exists('sidebarCpanel')) {
    function sidebarCpanel($data){
        return view('layouts.cpanel.partials.sidebar-ul',get_defined_vars())->render();
    }
}

if(!function_exists('extractRoutes')) {
    function extractRoutes(array $items): array
    {
        $routes = [];

        foreach ($items as $item) {
            if (!empty($item['route']) && $item['route'] !== '#') {
                $routes[] = $item;
            }

            if (!empty($item['children'])) {
                $routes = array_merge($routes, extractRoutes($item['children']));
            }
        }

        return $routes;
    }
}


if(!function_exists('checkRouteParams')) {
    function checkRouteParams($routeParams = []){
        foreach ($routeParams as $key => $value) {
            $routeCheck = request()->route($key);

            if(!empty($routeCheck)){
                return $routeCheck == $value;
            }
        }

        return false;
    }
}

if(!function_exists('checkRequestParams')) {
    function checkRequestParams($requestParams = []){
        foreach ($requestParams as $key => $value) {
            $paramCheck = request()->has($key) ?? false;
            if($paramCheck){
                return request($key) == $value;
            }
        }

        return false;
    }
}

if(!function_exists('exportToExcel')) {
    function exportToExcel($data, $columns, $headers, $fileName) {
        $filePath = "exports/{$fileName}-" . now()->format('Y-m-d_H-i-s') . ".xlsx";
        Excel::store(
            new \App\Exports\GeneralExport(
                data: $data,
                columns: $columns,
                headers: $headers
            ),
            $filePath,
            'public'
        );

        return public_path("storage/{$filePath}");
    }
}

if(!function_exists('superAdmins')) {
    function superAdmins(){
        return Admin::where('type', 'super_admin')->get();
    }
}

if(!function_exists('encodedData')) {
    function encodedData($data)
    {
        $newSlug = base64_encode(json_encode($data));

        return $newSlug;
    }
}

if(!function_exists('decodedData')) {
    function decodedData($encoded)
    {
        return json_decode(base64_decode($encoded), true);
    }
}

if(!function_exists('lang')) {
    function lang(){
        return app()->getLocale();
    }
}

if(!function_exists('currency')) {
    function currency(){
        $key = cacheKey('currency');
        return Cache::driver('file')->remember($key, 60*60, function() {
            return Currency::find(tenantSetting('currency_id',1));
        });
    }
}

if(!function_exists('currencyFormat')) {
    function currencyFormat($amount, $withComma = false){
        $currency = currency();
        $currencyPercision = tenantSetting('currency_precision',2);
        return "$currency->symbol" . number_format((float)$amount, $currencyPercision, '.',$withComma ? ',' : '');
    }
}

if(!function_exists('dateTimeFormat')) {
    function dateTimeFormat($date,$dateFormat = true, $timeFormat = true){
        $dateFormat = $dateFormat ? tenantSetting('date_format','Y-m-d') : '';
        $timeFormat = $timeFormat ? tenantSetting('time_format','H:i:s') : '';
        return carbon($date)->translatedFormat(trim("$dateFormat $timeFormat"));
    }
}

if(!function_exists('tenantSetting')) {
    function tenantSetting($key, $default = null) {
        return Cache::driver('file')->remember(cacheKey('setting'), 60 * 60 * 24, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
        })[$key] ?? $default;
    }
}

if (! function_exists('seo')) {
    function seo(Model | SEOData | null $source = null): TagManager
    {
        $tagManager = app(TagManager::class);

        if ($source) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
    // if (! function_exists('defaultSeoData')) {

    //     function defaultSeoData($newData = []){
    //         $alternates = [
    //             new AlternateTag('en', url('/en')),
    //             new AlternateTag('ar', url('/ar')),
    //         ];

    //         $seoImage = asset('mohaaseb_en_dark_2.webp');

    //         return new SEOData(
    //             title: $newData['title'] ?? __('website.titles.home'),
    //             description: $newData['description'] ?? __('website.meta_description'),
    //             author: $newData['author'] ?? 'codefanz.com',

    //             // ⭐ الصورة الأساسية
    //             image: $newData['image'] ?? $seoImage,

    //             url: url()->current(),
    //             robots: 'index, follow',
    //             canonical_url: url('/'),
    //             enableTitleSuffix: true,
    //             type: "website",
    //             site_name: "Mohaaseb",

    //             favicon: asset('favicon_io/favicon.ico'),

    //             locale: app()->getLocale() === 'en' ? 'en_US' : 'ar_AR',

    //             // ⭐ OpenGraph
    //             openGraphTitle: $newData['title'] ?? __('website.titles.home'),
    //             imageMeta: new ImageMeta($newData['imageMeta'] ?? $seoImage),

    //             // ⭐ Twitter
    //             twitter_username: "@mohaaseb",

    //             published_time: $newData['published_time'] ?? Carbon::now(),
    //             modified_time: $newData['modified_time'] ?? Carbon::now(),

    //             tags: $newData['tags'] ?? ['ERP', 'POS', 'Accounting', 'Inventory', 'Business Management'],

    //             // ⭐ Schema
    //             schema: new SchemaCollection([
    //                 [
    //                     "@context" => "https://schema.org",
    //                     "@type" => "SoftwareApplication",
    //                     "name" => "Mohaaseb ERP",
    //                     "applicationCategory" => "BusinessApplication",
    //                     "operatingSystem" => "Web",
    //                     "url" => url('/'),
    //                     "image" => $newData['image'] ?? $seoImage,
    //                     'logo' => $newData['image'] ?? $seoImage,
    //                     "description" => $newData['description'] ?? __('website.meta_description'),
    //                     "offers" => [
    //                         "@type" => "Offer",
    //                         "price" => "0",
    //                         "priceCurrency" => "USD",
    //                         'priceValidUntil' => Carbon::now()->addYear()->toDateString(),
    //                         'shippingDetails' => null,
    //                         'hasMerchantReturnPolicy' => null,
    //                     ],
    //                     "aggregateRating"=> [
    //                         "@type" => "AggregateRating",
    //                         "ratingValue" => "4.9",
    //                         "ratingCount" => "1202"
    //                     ],
    //                     "review"=> [
    //                         "@type" => "Review",
    //                         "reviewRating" => [
    //                             "@type" => "Rating",
    //                             "ratingValue" => "5"
    //                         ],
    //                         "author" => [
    //                                 "@type" => "Person",
    //                                 "name" => "John Doe"
    //                         ],
    //                         "reviewBody" => __("website.seo.review_body")
    //                     ]
    //                 ]
    //             ]),

    //             alternates: $alternates
    //         );
    //     }
    // }

}

if (! function_exists('currencySymbolPosition')) {
    function currencySymbolPosition($price,$symbol){
        if(app()->getLocale() == 'ar'){
            return $price . ' ' . $symbol;
        }
        else{
            return $symbol . ' ' . $price;
        }
    }
}
