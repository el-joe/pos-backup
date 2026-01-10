<?php

declare(strict_types=1);

use App\Enums\AccountTypeEnum;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Middleware\{AdminTranslationMiddleware,InitializeTenancyByDomain, RedirectFromWWW, Tenant\ReportsPermissionCheck,Tenant\AdminAuthMiddleware};
use App\Livewire\Admin\Accounts\AccountsList;
use App\Livewire\Admin\Admins\{AdminsList,RoleDetails,RolesList};
use App\Livewire\Admin\Branches\BranchesList;
use App\Livewire\Admin\Brands\BrandsList;
use App\Livewire\Admin\CashRegister\CashRegisterPage;
use App\Livewire\Admin\Categories\CategoriesList;
use App\Livewire\Admin\Discounts\DiscountsList;
use App\Livewire\Admin\Expenses\{ExpenseCategoriesList,ExpensesList};
use App\Livewire\Admin\Imports\ImportsPage;
use App\Livewire\Admin\Notifications\NotificationsList;
use App\Livewire\Admin\PaymentMethods\PaymentMethodsList;
use App\Livewire\Admin\Plans\{PlansList,SubscriptionsPage};
use App\Livewire\Admin\PosPage;
use App\Livewire\Admin\Products\{AddEditProduct,ProductsList};
use App\Livewire\Admin\Purchases\{AddPurchase,PurchaseDetails,PurchasesList};
use App\Livewire\Admin\Refunds\AddRefund;
use App\Livewire\Admin\Refunds\RefundsList;
use App\Livewire\Admin\Reports\{
    Admins\CashierReport,
    AuditReport,
    BranchProfitability,CashRegisterReport,
    Financial\BalanceSheetReport,Financial\CashFlowStatementReport,Financial\GeneralLedgerReport,Financial\IncomeStatmentReport,Financial\TrailBalanceReport,
    Inventory\CogsReport,Inventory\ShortageReport,Inventory\StockMovementReport,Inventory\StockValuationReport,
    Performance\CustomerOutstandingReport,Performance\DiscountImpactReport,Performance\ExpenseBreakdownReport,Performance\ProductProfitMarginReport,
    Performance\RevenueBreakdownByBranchReport,Performance\SalesThresholdReport,Performance\SupplierPayableReport,
    Purchases\BranchPurchasesReport,Purchases\PurchasesDiscountReport,Purchases\PurchasesReturnReport,Purchases\PurchasesSummaryReport,Purchases\PurchasesVatReport,
    Purchases\ProductPurchasesReport,Purchases\SupplierPurchasesReport,
    Sales\BranchSalesReport,Sales\CustomerSalesReport,Sales\ProductSalesReport,Sales\SalesProfitReport,Sales\SalesReturnReport,Sales\SalesSummaryReport,Sales\SalesVatReport,
    Tax\VatSummaryReport,Tax\WithholdingTaxReport
};

use App\Livewire\Admin\Sales\{SaleDetails,SalesList};
use App\Livewire\Admin\Settings\SettingsPage;
use App\Livewire\Admin\Statistics;
use App\Livewire\Admin\Stocks\{AddStockTransfer,StockTransferDetails,StockTransferList};

use App\Livewire\Admin\StockTaking\{AddStockTaking,StockTakingDetails,StockTakingList};

use App\Livewire\Admin\Taxes\TaxesList;
use App\Livewire\Admin\Transactions\TransactionList;
use App\Livewire\Admin\Units\{UnitsList};
use App\Livewire\Admin\Users\{UserDetails,UsersList};
use App\Models\Tenant\ExpenseCategory;
use Illuminate\Support\Facades\Route;
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
    AdminTranslationMiddleware::class,
])->group(function () {

    Route::redirect('/','admin/login');

    Route::group(['prefix'=>'admin','as'=>'admin.'],function () {
        Route::get('login',[AuthController::class,'login'])->name('login');
        Route::post('login',[AuthController::class,'postLogin'])->name('postLogin');

        Route::post('notifications/mark-as-read/{id}', [AuthController::class, 'markAsRead'])->name('notifications.markAsRead');

        Route::middleware([AdminAuthMiddleware::class])->group(function () {

            Route::get('logout',[AuthController::class,'logout'])->name('logout');

            Route::get('/',Statistics::class)->name('statistics');
            Route::get('cash-register',CashRegisterPage::class)->name('cash.register.open');

            Route::get('notifications', NotificationsList::class)->name('notifications.list');

            Route::get('switch-branch/{branch?}', [AuthController::class, 'switchBranch'])->name('switch.branch');
            Route::get('branches',BranchesList::class)->name('branches.list');
            Route::get('categories', CategoriesList::class)->name('categories.list');
            Route::get('brands', BrandsList::class)->name('brands.list');
            Route::get('units',UnitsList::class)->name('units.list');
            Route::get('taxes',TaxesList::class)->name('taxes.list');
            Route::get('pos',PosPage::class)->name('pos');
            Route::get('discounts', DiscountsList::class)->name('discounts.list');
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
            // TODO : Shipping Companies

            // Reports
            Route::group([
                'prefix'=> 'reports',
                'as'=> 'reports.',
                'middleware' => [ReportsPermissionCheck::class]
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

                Route::group([
                    'prefix' => 'inventory',
                    'as' => 'inventory.',
                ], function () {
                    Route::get('stock-valuation', StockValuationReport::class)->name('stock-valuation');
                    Route::get('stock-movement', StockMovementReport::class)->name('stock-movement');
                    Route::get('cogs-report', CogsReport::class)->name('cogs-report');
                    Route::get('shortage-report', ShortageReport::class)->name('shortage-report');
                });

                Route::group([
                    'prefix' => 'taxes',
                    'as' => 'taxes.',
                ], function () {
                    Route::get('vat-summary', VatSummaryReport::class)->name('vat-summary');
                    Route::get('withholding-tax', WithholdingTaxReport::class)->name('withholding-tax');
                });

                Route::group([
                    'prefix' => 'performance',
                    'as' => 'performance.',
                ], function () {
                    Route::get('product-profit-margin',ProductProfitMarginReport::class)->name('product-profit-margin');
                    Route::get('customer-outstanding',CustomerOutstandingReport::class)->name('customer-outstanding');
                    Route::get('supplier-payable',SupplierPayableReport::class)->name('supplier-payable');
                    Route::get('expense-breakdown',ExpenseBreakdownReport::class)->name('expense-breakdown');
                    Route::get('revenue-breakdown-by-branch',RevenueBreakdownByBranchReport::class)->name('revenue-breakdown-by-branch');
                    Route::get('discount-impact',DiscountImpactReport::class)->name('discount-impact');
                    Route::get('sales-threshold',SalesThresholdReport::class)->name('sales-threshold');
                });

                Route::get('cashier-report', CashierReport::class)->name('cashier.report');
                Route::get('cash-register-report', CashRegisterReport::class)->name('cash.register.report');
                Route::get('branch-profitability', BranchProfitability::class)->name('branch.profitability');
                Route::get('audit-report', AuditReport::class)->name('audit.report');

            });
            // Stock Adjustments
            Route::get('stock-adjustments', StockTakingList::class)->name('stocks.adjustments.list');
            Route::get('stock-adjustments/create', AddStockTaking::class)->name('stocks.adjustments.create');
            Route::get('stock-adjustments/{id}/details', StockTakingDetails::class)->name('stocks.adjustments.details');
            // Stock Transfers
            Route::get('stock-transfers', StockTransferList::class)->name('stocks.transfers.list');
            Route::get('stock-transfers/create', AddStockTransfer::class)->name('stocks.transfers.create');
            Route::get('stock-transfers/{id}/details', StockTransferDetails::class)->name('stocks.transfers.details');

            Route::get('admins',AdminsList::class)->name('admins.list');
            Route::get('roles',RolesList::class)->name('roles.list');
            Route::get('roles/{id?}',RoleDetails::class)->name('roles.show');

            Route::get('plans', PlansList::class)->name('plans.list');
            Route::get('subscriptions', SubscriptionsPage::class)->name('subscriptions.list');


            Route::get('imports',ImportsPage::class)->name('imports');
            Route::get('settings',SettingsPage::class)->name('settings');

            Route::get('refunds', RefundsList::class)->name('refunds.list');
            Route::get('refunds/create', AddRefund::class)->name('refunds.create');
        });
    });

    //Route::get('admin/invoices/{type}/{id}', [GeneralController::class, 'generateInvoice'])->name('generate.invoice');
    Route::get('sales/invoice/{token}',[InvoiceController::class,'show'])->name('sales.invoice');


    Route::get('change-language/{lang}', function($lang){
        if($lang == 'ar'){
            session()->put('locale','ar');
        } else {
            session()->put('locale','en');
        }
    })->name('change.language');

});

Route::get('download-file', function () {
    $file = request()->get('file');
    if (file_exists($file)) {
        return response()->download($file);
    } else {
        return "File does not exist.";
    }
})->name('admin.export.download');

Route::view('invoice-80mm','invoices.invoice-80mm');
Route::view('invoice-80mm-ar','invoices.invoice-80mm-ar');
Route::view('invoice-a4-ar','invoices.invoice-a4-ar');
Route::view('invoice-a4','invoices.invoice-a4');
Route::view('refund-invoice-a4-ar','invoices.refund-invoice-a4-ar');
Route::view('refund-invoice-a4','invoices.refund-invoice-a4');
Route::view('refund-invoice-80mm','invoices.refund-invoice-80mm');
Route::view('refund-invoice-80mm-ar','invoices.refund-invoice-80mm-ar');

Route::get('test/{tenant}',function($tenant){
    tenancy()->initialize( $tenant);
    // initaialize tenant
    foreach (AccountTypeEnum::defaultExpensesAccounts() as $parentAccountType => $childAccounts) {
            $parentAccount = AccountTypeEnum::from($parentAccountType);
            // create expense category
            $parent = ExpenseCategory::create([
                'name' => $parentAccount->label(),
                'ar_name' => $parentAccount->expensesAccountsTranslation(),
                'parent_id' => null,
                'active' => true,
                'default' => true,
                'key' => $parentAccount->value,
            ]);

            $en = $childAccounts['en'];
            $ar = $childAccounts['ar'];
            foreach ($en as $index => $childAccountName) {
                ExpenseCategory::create([
                    'name' => $childAccountName,
                    'ar_name' => $ar[$index],
                    'parent_id' => $parent->id,
                    'active' => true,
                    'default' => true,
                    'key' => $parentAccount->value,
                ]);
            }
        }
    tenancy()->end();
});


// Import Excel,CSV ---- #DONE  -> Add it into Subscriptions
// Barcode/QR Code Generation
// Prienters Settings

// Before Publishing we need to test domain register not just subdomain

// اداره التصنيع
// TODO : E-Invoice Coming Soon
// Multi Currency Support into sales orders -> EX : customer come to egypt and doesn't have EGP , he pay in USD , we save the exchange rate at that day and save the amount in both currencies
// tenant balance
// static pages into central website

// booking reservation system
// hrm system

// make tenant website & mobile app
