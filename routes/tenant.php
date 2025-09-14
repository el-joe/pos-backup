<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\GeneralController;
use App\Http\Middleware\Tenant\AdminAuthMiddleware;
use App\Livewire\Admin\Accounts\AccountsList;
use App\Livewire\Admin\Branches\BranchesList;
use App\Livewire\Admin\Brands\BrandsList;
use App\Livewire\Admin\Categories\CategoriesList;
use App\Livewire\Admin\Discounts\DiscountsList;
use App\Livewire\Admin\Expenses\ExpenseCategoriesList;
use App\Livewire\Admin\Expenses\ExpensesList;
use App\Livewire\Admin\PaymentMethods\PaymentMethodsList;
use App\Livewire\Admin\PosPage;
use App\Livewire\Admin\Products\AddEditProduct;
use App\Livewire\Admin\Products\ProductsList;
use App\Livewire\Admin\Statistics;
use App\Livewire\Admin\Taxes\TaxesList;
use App\Livewire\Admin\Units\UnitsList;
use App\Livewire\Admin\Users\UserDetails;
use App\Livewire\Admin\Users\UsersList;
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
            Route::get('taxes',TaxesList::class)->name('taxes.list'); /// Need to add Branch Relation
            Route::get('pos',PosPage::class)->name('pos'); // TODO
            Route::get('discounts', DiscountsList::class)->name('discounts.list'); // Need to add Branch Relation
            Route::get('expense-categories',ExpenseCategoriesList::class)->name('expense-categories.list');
            Route::get('expenses',ExpensesList::class)->name('expenses.list'); // TODO -> Add Branch Relation

            Route::get('payment-methods',PaymentMethodsList::class)->name('payment-methods.list');
            Route::get('accounts',AccountsList::class)->name('accounts.list');


            // Users (Customer,Supplier)
            Route::get('users/{type?}', UsersList::class)->name('users.list');
            Route::get('users/{id}/details', UserDetails::class)->name('users.details');

            // Products
            Route::get('products',ProductsList::class)->name('products.list');
            Route::get('products/{id}',AddEditProduct::class)->name('products.add-edit');
            // Purchases
            // Stocks
            // Sales
            // Transactions
            // Transaction Lines
            // Shipping Companies
        });
    });
});

/**
 * Transactions Table :
 * id              (PK)
 * date            (date/datetime)
 * description     (string / text) -- e.g., "Sale Invoice #123"
 * reference_id    (nullable, polymorphic) -- link to sales, purchases, etc.
 * reference_type  (nullable, e.g., 'Sale', 'Purchase', 'Expense')
 * branch_id       (nullable FK -> branches.id)
 * total_amount    (decimal 15,2)
 * created_at
 * updated_at
 */

/**
 * Transaction Lines Table :
 * id              (PK)
 * transaction_id  (FK -> transactions.id)
 * account_id      (FK -> accounts.id)
 * type            (enum: 'debit','credit')
 * amount          (decimal 15,2)
 * created_at
 * updated_at
 */
