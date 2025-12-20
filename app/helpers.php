<?php

use App\Models\Currency;
use App\Models\Tenant\Admin;
use App\Models\Tenant\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

const TENANT_ADMINS_GUARD = 'tenant_admin';
const CPANEL_ADMINS_GUARD = 'cpanel_admin';

function cacheKey($key){
    return tenant()->id . '_' . $key;
}

function defaultLayout(){
    return 'hud';
}

function layoutView($pageName,$with = [],$isSubPage = false){
    $defaultView = "livewire." . defaultLayout();
    $defaultLayout = 'layouts.' . defaultLayout();
    $layoutData = isset($with['withoutSidebar']) ? ['withoutSidebar' => $with['withoutSidebar']] : [];
    return view("$defaultView.$pageName", $with)
            ->layout($isSubPage ? null : $defaultLayout, $layoutData);
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

function sidebarHud($data){
    return view('layouts.hud.partials.sidebar-ul',get_defined_vars())->render();
}

function sidebarCpanel($data){
    return view('layouts.cpanel.partials.sidebar-ul',get_defined_vars())->render();
}

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


function checkRouteParams($routeParams = []){
    foreach ($routeParams as $key => $value) {
        $routeCheck = request()->route($key);

        if(!empty($routeCheck)){
            return $routeCheck == $value;
        }
    }

    return false;
}

function checkRequestParams($requestParams = []){
    foreach ($requestParams as $key => $value) {
        $paramCheck = request()->has($key) ?? false;
        if($paramCheck){
            return request($key) == $value;
        }
    }

    return false;
}

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

function superAdmins(){
    return Admin::where('type', 'super_admin')->get();
}

function encodedData($data)
{
    $newSlug = base64_encode(json_encode($data));

    return $newSlug;
}

function decodedData($encoded)
{
    return json_decode(base64_decode($encoded), true);
}

function lang(){
    return app()->getLocale();
}

function currency(){
    $key = cacheKey('currency');
    return Cache::driver('file')->remember($key, 60*60, function() {
        return Currency::find(tenantSetting('currency_id',1));
    });
}

function currencyFormat($amount, $withComma = false){
    $currency = currency();
    $currencyPercision = tenantSetting('currency_precision',2);
    return "$currency->symbol" . number_format((float)$amount, $currencyPercision, '.',$withComma ? ',' : '');
}

function dateTimeFormat($date,$dateFormat = true, $timeFormat = true){
    $dateFormat = $dateFormat ? tenantSetting('date_format','Y-m-d') : '';
    $timeFormat = $timeFormat ? tenantSetting('time_format','H:i:s') : '';
    return carbon($date)->translatedFormat(trim("$dateFormat $timeFormat"));
}

function tenantSetting($key, $default = null) {
    return Cache::driver('file')->remember(cacheKey('setting'), 60 * 60 * 24, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
    })[$key] ?? $default;
}
