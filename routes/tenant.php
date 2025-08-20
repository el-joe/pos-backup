<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\GeneralController;
use App\Livewire\Admin\Branches\BranchesList;
use App\Livewire\Admin\Statistics;
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
        Route::get('/',Statistics::class)->name('statistics');
        Route::get('branches',BranchesList::class)->name('branches.list');
    });
});
