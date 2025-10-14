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
use App\Livewire\Admin\Purchases\AddPurchase;
use App\Livewire\Admin\Purchases\PurchaseDetails;
use App\Livewire\Admin\Purchases\PurchasesList;
use App\Livewire\Admin\Reports\Financial\BalanceSheetReport;
use App\Livewire\Admin\Reports\Financial\CashFlowStatementReport;
use App\Livewire\Admin\Reports\Financial\GeneralLedgerReport;
use App\Livewire\Admin\Reports\Financial\IncomeStatmentReport;
use App\Livewire\Admin\Reports\Financial\TrailBalanceReport;
use App\Livewire\Admin\Reports\Purchases\BranchPurchasesReport;
use App\Livewire\Admin\Reports\Purchases\ProductPurchasesReport;
use App\Livewire\Admin\Reports\Purchases\PurchasesDiscountReport;
use App\Livewire\Admin\Reports\Purchases\PurchasesReturnReport;
use App\Livewire\Admin\Reports\Purchases\PurchasesSummaryReport;
use App\Livewire\Admin\Reports\Purchases\PurchasesVatReport;
use App\Livewire\Admin\Reports\Purchases\SupplierPurchasesReport;
use App\Livewire\Admin\Reports\Sales\BranchSalesReport;
use App\Livewire\Admin\Reports\Sales\CustomerSalesReport;
use App\Livewire\Admin\Reports\Sales\ProductSalesReport;
use App\Livewire\Admin\Reports\Sales\SalesProfitReport;
use App\Livewire\Admin\Reports\Sales\SalesReturnReport;
use App\Livewire\Admin\Reports\Sales\SalesSummaryReport;
use App\Livewire\Admin\Reports\Sales\SalesVatReport;
use App\Livewire\Admin\Sales\SaleDetails;
use App\Livewire\Admin\Sales\SalesList;
use App\Livewire\Admin\Statistics;
use App\Livewire\Admin\Stocks\AddStockTransfer;
use App\Livewire\Admin\Stocks\StockTransferDetails;
use App\Livewire\Admin\Stocks\StockTransferList;
use App\Livewire\Admin\StockTaking\AddStockTaking;
use App\Livewire\Admin\StockTaking\StockTakingDetails;
use App\Livewire\Admin\StockTaking\StockTakingList;
use App\Livewire\Admin\Taxes\TaxesList;
use App\Livewire\Admin\Transactions\TransactionList;
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
            Route::get('taxes',TaxesList::class)->name('taxes.list');
            Route::get('pos',PosPage::class)->name('pos');
            Route::get('discounts', DiscountsList::class)->name('discounts.list'); //TODO : Need to add sales threshold when type is fixed
            Route::get('expense-categories',ExpenseCategoriesList::class)->name('expense-categories.list');
            // TODO : expenses will be saved without payments and we can add payments later
            Route::get('expenses',ExpensesList::class)->name('expenses.list');

            Route::get('payment-methods',PaymentMethodsList::class)->name('payment-methods.list');
            Route::get('accounts',AccountsList::class)->name('accounts.list');


            // Users (Customer,Supplier)
            Route::get('users/{type?}', UsersList::class)->name('users.list');
            Route::get('users/{id}/details', UserDetails::class)->name('users.details');

            // Products
            Route::get('products',ProductsList::class)->name('products.list'); // TODO : -> Add Product Details Page contains Stock info , Purchases , Sales , Stock Transfers , Adjustments
            Route::get('products/{id}',AddEditProduct::class)->name('products.add-edit');
            // Purchases
            Route::get('purchases',PurchasesList::class)->name('purchases.list');
            Route::get('purchases/create',AddPurchase::class)->name('purchases.add');
            Route::get('purchases/{id}',PurchaseDetails::class)->name('purchases.details');
            // Sales
            Route::get('sales',SalesList::class)->name('sales.index');
            Route::get('sales/{id}',SaleDetails::class)->name('sales.details');
            // Transactions
            Route::get('transactions',TransactionList::class)->name('transactions.list');
            // Shipping Companies
            // Admininstrators (User|Role)
            // Opening Balance page

            // Reports
            Route::group([
                'prefix'=> 'reports',
                'as'=> 'reports.',
            ],function () {

                Route::group([
                    'prefix' => 'financial',
                    'as' => 'financial.',
                ], function () {
                    Route::get('trail-balance', TrailBalanceReport::class)->name('trail-balance');
                    Route::get('income-statement', IncomeStatmentReport::class)->name('income-statement');
                    Route::get('balance-sheet', BalanceSheetReport::class)->name('balance-sheet');
                    Route::get('cash-flow-statement', CashFlowStatementReport::class)->name('cash-flow-statement');
                    Route::get('general-ledger', GeneralLedgerReport::class)->name('general-ledger');
                });


                Route::group([
                    'prefix' => 'sales',
                    'as' => 'sales.',
                ], function () {
                    Route::get('sales-summary', SalesSummaryReport::class)->name('sales.summary');
                    Route::get('product-sales', ProductSalesReport::class)->name('sales.product');
                    Route::get('branch-sales', BranchSalesReport::class)->name('sales.branch');
                    Route::get('customer-sales', CustomerSalesReport::class)->name('sales.customer');
                    Route::get('profit-loss', SalesProfitReport::class)->name('sales.profit-loss');
                    Route::get('returns', SalesReturnReport::class)->name('sales.returns');
                    Route::get('vat-report', SalesVatReport::class)->name('sales.vat-report');
                });

                Route::group([
                    'prefix' => 'purchases',
                    'as' => 'purchases.',
                ], function () {
                    Route::get('purchases-summary', PurchasesSummaryReport::class)->name('purchases.summary');
                    Route::get('product-purchases', ProductPurchasesReport::class)->name('purchases.product');
                    Route::get('branch-purchases', BranchPurchasesReport::class)->name('purchases.branch');
                    Route::get('supplier-purchases', SupplierPurchasesReport::class)->name('purchases.supplier');
                    Route::get('returns', PurchasesReturnReport::class)->name('purchases.returns');
                    Route::get('discounts', PurchasesDiscountReport::class)->name('purchases.discounts');
                    Route::get('vat-report', PurchasesVatReport::class)->name('purchases.vat-report');
                });
            });
            // Stock Adjustments
            Route::get('stock-adjustments', StockTakingList::class)->name('stocks.adjustments.list');
            Route::get('stock-adjustments/create', AddStockTaking::class)->name('stocks.adjustments.create');
            Route::get('stock-adjustments/{id}/details', StockTakingDetails::class)->name('stocks.adjustments.details');
            // Stock Transfers
            Route::get('stock-transfers', StockTransferList::class)->name('stocks.transfers.list');
            Route::get('stock-transfers/create', AddStockTransfer::class)->name('stocks.transfers.create');
            Route::get('stock-transfers/{id}/details', StockTransferDetails::class)->name('stocks.transfers.details');
        });
    });
});


// Features to add later
// Export/Import Excel,CSV,PDF
// Invoice Customization (Logo,Color,Text)
// Barcode/QR Code Generation
// Add Currency & City to branch
// Filters into all list pages

// TODO : E-Invoice Coming Soon
// Email & Notification system
// whatsapp notification
// SMS notification
// tenant balance
