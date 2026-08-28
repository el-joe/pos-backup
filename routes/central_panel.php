<?php

use App\Http\Controllers\Central\CPanel\AuthController;
use App\Http\Middleware\CpanelTranslationMiddleware;
use App\Livewire\Central\CPanel\Admins\AdminsList;
use App\Livewire\Central\CPanel\Admins\PermissionsAuditPage;
use App\Livewire\Central\CPanel\Contacts\ContactsList;
use App\Livewire\Central\CPanel\Countries\CountriesList;
use App\Livewire\Central\CPanel\Currencies\CurrenciesList;
use App\Livewire\Central\CPanel\Customers\CustomerCreate;
use App\Livewire\Central\CPanel\Customers\CustomerDetails;
use App\Livewire\Central\CPanel\Customers\CustomersList;
use App\Http\Controllers\Central\CPanel\BlogController;
use App\Http\Controllers\Central\CPanel\PageController;
use App\Http\Controllers\Central\CPanel\FaqController;
use App\Http\Controllers\Central\CPanel\CampaignController;
use App\Livewire\Central\CPanel\Faqs\FaqsList;
use App\Livewire\Central\CPanel\Blogs\BlogsList;
use App\Livewire\Central\CPanel\Newsletter\CampaignsList;
use App\Livewire\Central\CPanel\Newsletter\SubscribersList;
use App\Livewire\Central\CPanel\Pages\PagesList;
use App\Livewire\Central\CPanel\Partners\PartnerCommissionsList;
use App\Livewire\Central\CPanel\Partners\PartnerForm;
use App\Livewire\Central\CPanel\Partners\PartnersList;
use App\Livewire\Central\CPanel\Plans\CpanelPlansList;
use App\Livewire\Central\CPanel\PaymentMethods\PaymentMethodForm;
use App\Livewire\Central\CPanel\PaymentMethods\PaymentMethodsList;
use App\Livewire\Central\CPanel\FileManager\FileManagerPage;
use App\Livewire\Central\CPanel\HomePage;
use App\Livewire\Central\CPanel\Languages\LanguagesList;
use App\Livewire\Central\CPanel\Translations\TranslationsEditor;
use App\Livewire\Central\CPanel\RegisterRequests\RegisterRequestsList;
use App\Livewire\Central\CPanel\Slider\SliderList;
use App\Livewire\Central\CPanel\Subscriptions\SubscriptionsList;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'cpanel','as' => 'cpanel.','middleware'=> [CpanelTranslationMiddleware::class]],function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['auth:' . CPANEL_ADMINS_GUARD]], function () {
        Route::get('/', HomePage::class)->name('dashboard');
        Route::get('admins',AdminsList::class)->name('admins.list');
        Route::get('admins/permissions', PermissionsAuditPage::class)->name('admins.permissions');
        Route::get('blogs', BlogsList::class)->name('blogs.list');
        Route::get('blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('blogs/{id}', [BlogController::class, 'edit'])->whereNumber('id')->name('blogs.edit');
        Route::put('blogs/{id}', [BlogController::class, 'update'])->whereNumber('id')->name('blogs.update');

        Route::get('pages', PagesList::class)->name('pages.list');
        Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('pages/{id}', [PageController::class, 'edit'])->whereNumber('id')->name('pages.edit');
        Route::put('pages/{id}', [PageController::class, 'update'])->whereNumber('id')->name('pages.update');

        Route::get('faqs', FaqsList::class)->name('faqs.list');
        Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::get('faqs/{id}', [FaqController::class, 'edit'])->whereNumber('id')->name('faqs.edit');
        Route::put('faqs/{id}', [FaqController::class, 'update'])->whereNumber('id')->name('faqs.update');

        Route::get('newsletter', SubscribersList::class)->name('newsletter.subscribers');
        Route::get('newsletter/campaigns', CampaignsList::class)->name('newsletter.campaigns');
        Route::get('newsletter/campaigns/create', [CampaignController::class, 'create'])->name('newsletter.campaigns.create');
        Route::post('newsletter/campaigns', [CampaignController::class, 'store'])->name('newsletter.campaigns.store');
        Route::get('newsletter/campaigns/{id}', [CampaignController::class, 'edit'])->whereNumber('id')->name('newsletter.campaigns.edit');
        Route::put('newsletter/campaigns/{id}', [CampaignController::class, 'update'])->whereNumber('id')->name('newsletter.campaigns.update');
        Route::post('newsletter/campaigns/{id}/preview', [CampaignController::class, 'sendPreview'])->whereNumber('id')->name('newsletter.campaigns.preview');
        Route::post('newsletter/campaigns/{id}/send', [CampaignController::class, 'send'])->whereNumber('id')->name('newsletter.campaigns.send');

        Route::get('file-manager', FileManagerPage::class)->name('file-manager');
        Route::get('contacts', ContactsList::class)->name('contacts.list');
        Route::get('countries', CountriesList::class)->name('countries.list');
        Route::get('currencies', CurrenciesList::class)->name('currencies.list');
        Route::get('languages', LanguagesList::class)->name('languages.list');
        Route::get('translations', TranslationsEditor::class)->name('translations');
        Route::get('subscriptions', SubscriptionsList::class)->name('subscriptions.list');
        Route::get('plans', CpanelPlansList::class)->name('plans.list');
        Route::get('register-requests', RegisterRequestsList::class)->name('register-requests.list');
        Route::get('customers', CustomersList::class)->name('customers.list');
        Route::get('customers/create', CustomerCreate::class)->name('customers.create');
        Route::get('customers/{id}', CustomerDetails::class)->name('customers.details');
        Route::get('sliders', SliderList::class)->name('sliders.list');

        Route::get('partners', PartnersList::class)->name('partners.list');
        Route::get('partners/create', PartnerForm::class)->name('partners.create');
        Route::get('partners/{id}', PartnerForm::class)->whereNumber('id')->name('partners.edit');
        Route::get('partner-commissions', PartnerCommissionsList::class)->name('partner-commissions.list');

        Route::get('payment-methods', PaymentMethodsList::class)->name('payment-methods.list');
        Route::get('payment-methods/create', PaymentMethodForm::class)->name('payment-methods.create');
        Route::get('payment-methods/{id}', PaymentMethodForm::class)->whereNumber('id')->name('payment-methods.edit');
    });
});
