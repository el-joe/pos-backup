<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\GeneralController;
use App\Http\Middleware\Tenant\AdminAuthMiddleware;
use App\Livewire\Admin\Branches\BranchesList;
use App\Livewire\Admin\Brands\BrandsList;
use App\Livewire\Admin\Categories\CategoriesList;
use App\Livewire\Admin\PosPage;
use App\Livewire\Admin\Statistics;
use App\Livewire\Admin\Taxes\TaxesList;
use App\Livewire\Admin\Units\UnitsList;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::redirect('/','admin/login');

    Route::group(['prefix'=>'admin','as'=>'admin.'],function () {
        Route::get('login',[AuthController::class,'login'])->name('login');
        Route::post('login',[AuthController::class,'postLogin'])->name('postLogin');

        Route::middleware([AdminAuthMiddleware::class])->group(function () {
            Route::get('/',Statistics::class)->name('statistics');
            Route::get('branches',BranchesList::class)->name('branches.list');
            Route::get('categories', CategoriesList::class)->name('categories.list');
            Route::get('brands', BrandsList::class)->name('brands.list');
            Route::get('units',UnitsList::class)->name('units.list');
            Route::get('taxes',TaxesList::class)->name('taxes.list');
            Route::get('pos',PosPage::class)->name('pos.list');
        });
    });
});
