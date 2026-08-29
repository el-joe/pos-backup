<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\ApiDocsController;
use App\Http\Controllers\Api\Tenant\ChecksApiController;
use App\Http\Controllers\Api\Tenant\CustomersApiController;
use App\Http\Controllers\Api\Tenant\ExpensesApiController;
use App\Http\Controllers\Api\Tenant\ProductsApiController;
use App\Http\Controllers\Api\Tenant\PurchaseRequestsApiController;
use App\Http\Controllers\Api\Tenant\PurchasesApiController;
use App\Http\Controllers\Api\Tenant\RefundsApiController;
use App\Http\Controllers\Api\Tenant\SaleRequestsApiController;
use App\Http\Controllers\Api\Tenant\SalesApiController;
use App\Http\Controllers\Api\Tenant\SettingsApiController;
use App\Http\Controllers\Api\Tenant\StatisticsApiController;
use App\Http\Controllers\Api\Tenant\StocksApiController;
use App\Http\Controllers\Api\Tenant\SuppliersApiController;
use App\Http\Middleware\InitializeTenancyByDomain;
use App\Http\Middleware\Tenant\ApiTokenAuth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

// Public API documentation page — no token required, but still tenant-scoped.
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('api/docs', [ApiDocsController::class, 'index'])->name('api.docs');
});

Route::prefix('api/v1')->middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    ApiTokenAuth::class,
])->name('api.v1.')->group(function () {

    Route::apiResource('products', ProductsApiController::class);
    Route::apiResource('customers', CustomersApiController::class)->except(['destroy']);
    Route::post('customers/{id}/payments', [CustomersApiController::class, 'recordPayment'])->name('customers.payments.store');
    Route::apiResource('suppliers', SuppliersApiController::class)->except(['destroy']);
    Route::post('suppliers/{id}/payments', [SuppliersApiController::class, 'recordPayment'])->name('suppliers.payments.store');
    Route::apiResource('expenses', ExpensesApiController::class);

    Route::get('sales', [SalesApiController::class, 'index'])->name('sales.index');
    Route::get('sales/{id}', [SalesApiController::class, 'show'])->name('sales.show');
    Route::post('sales', [SalesApiController::class, 'store'])->name('sales.store');

    Route::get('purchases', [PurchasesApiController::class, 'index'])->name('purchases.index');
    Route::get('purchases/{id}', [PurchasesApiController::class, 'show'])->name('purchases.show');
    Route::post('purchases', [PurchasesApiController::class, 'store'])->name('purchases.store');

    Route::get('statistics/summary', [StatisticsApiController::class, 'summary'])->name('statistics.summary');
    Route::get('statistics/daily', [StatisticsApiController::class, 'daily'])->name('statistics.daily');

    Route::get('settings', [SettingsApiController::class, 'index'])->name('settings.index');
    Route::post('settings/regenerate-token', [SettingsApiController::class, 'regenerateToken'])->name('settings.regenerate-token');

    Route::get('refunds', [RefundsApiController::class, 'index'])->name('refunds.index');
    Route::get('refunds/{id}', [RefundsApiController::class, 'show'])->name('refunds.show');
    Route::post('refunds', [RefundsApiController::class, 'store'])->name('refunds.store');

    Route::get('stocks', [StocksApiController::class, 'index'])->name('stocks.index');
    Route::get('stocks/{id}', [StocksApiController::class, 'show'])->name('stocks.show');

    Route::get('checks', [ChecksApiController::class, 'index'])->name('checks.index');
    Route::get('checks/{id}', [ChecksApiController::class, 'show'])->name('checks.show');

    Route::get('purchase-requests', [PurchaseRequestsApiController::class, 'index'])->name('purchase-requests.index');
    Route::get('purchase-requests/{id}', [PurchaseRequestsApiController::class, 'show'])->name('purchase-requests.show');

    Route::get('sale-requests', [SaleRequestsApiController::class, 'index'])->name('sale-requests.index');
    Route::get('sale-requests/{id}', [SaleRequestsApiController::class, 'show'])->name('sale-requests.show');
});
