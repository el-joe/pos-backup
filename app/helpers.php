<?php

use App\Models\Tenant\Admin;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

const TENANT_ADMINS_GUARD = 'tenant_admin';
const CPANEL_ADMINS_GUARD = 'cpanel_admin';

if(!function_exists('settings')) {
    function settings($key) {
        return null;
    }
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

if(!function_exists('formattedDate')) {
    function formattedDate($date): string {
        return carbon($date)->translatedFormat('l , d-M-Y');
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
            "stock_transfers"=> ["list","show", "create", "update", "delete","export"],
            "stock_adjustments"=> ["list","show", "create", "update", "delete",'export'],
            "sales"=> ["list", "show","update", "delete","pay",'export'],
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
            'subscriptions' => ['list','cancel'],
            "discounts"=> ["list", "create", "update", "delete",'export'],
            "taxes"=> ["list", "create", "update", "delete",'export'],
            "general_settings"=> ["update"]
        ];

    }
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
            $routes[] = $item['route'];
        }

        if (!empty($item['children'])) {
            $routes = array_merge($routes, extractRoutes($item['children']));
        }
    }

    return $routes;
}


function checkRouteParams($routeParams = []){
    foreach ($routeParams as $key => $value) {
        $route = request()->route($key);
        if($route == null){
            return true;
        }

        if($route == $value) {
            return true;
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

function encodedSlug($data)
{
    $newSlug = base64_encode(json_encode($data));

    return $newSlug;
}

function decodedSlug($encoded)
{
    return json_decode(base64_decode($encoded), true);
}
