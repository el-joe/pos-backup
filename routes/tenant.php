<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\GeneralController;
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
    Route::get('/',[AuthController::class,'login'])->name('login');
    Route::post('/',[AuthController::class,'postLogin'])->name('postLogin');

    Route::group(['prefix'=>'admin','as'=>'admin.'],function () {
        Route::redirect('/','/admin/statistics');
        Route::view('/statistics','admin.index');

        Route::group(['as'=>'accounting.'],function () {
            Route::get('account-type-groups',[GeneralController::class,'accountTypeGroups'])->name('account-type-groups');
            Route::get('account-types',[GeneralController::class,'accountTypes'])->name('account-types');
            Route::get('accounts',[GeneralController::class,'accounts'])->name('accounts');
        });

        Route::group(['as'=>'contacts.'],function () {
            Route::get('contacts/{id}',[GeneralController::class,'contacts'])->name('list');
        });

        Route::group(['as'=>'administration.'],function () {
            Route::get('branches',[GeneralController::class,'branches'])->name('branches');
            Route::get('admins',[GeneralController::class,'admins'])->name('admins');
        });

        Route::group(['as'=>'inventory.'],function () {
            Route::get('categories',[GeneralController::class,'categories'])->name('categories');
            Route::get('brands',[GeneralController::class,'brands'])->name('brands');
            Route::get('units',[GeneralController::class,'units'])->name('units');
        });

        Route::group(['as'=>'products.'],function () {
            Route::get('products',[GeneralController::class,'products'])->name('index');
            Route::get('products/{id?}',[GeneralController::class,'addEditProduct'])->name('addEditProduct');
        });

        Route::group(['as'=>'purchases.'],function () {
            Route::get('purchases',[GeneralController::class,'purchases'])->name('index');
            Route::get('purchases/{id?}',[GeneralController::class,'addEditPurchase'])->name('addEditPurchase');
        });

        Route::group(['as'=>'sales.'],function () {
            Route::get('sales',[GeneralController::class,'sales'])->name('index');
            Route::get('sales/{id?}',[GeneralController::class,'addEditSale'])->name('addEditSale');
        });

    });
});
