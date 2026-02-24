<?php

use App\Http\Controllers\Central\CPanel\AuthController;
use App\Http\Middleware\CpanelTranslationMiddleware;
use App\Livewire\Central\CPanel\Admins\AdminsList;
use App\Livewire\Central\CPanel\Contacts\ContactsList;
use App\Livewire\Central\CPanel\Countries\CountriesList;
use App\Livewire\Central\CPanel\Currencies\CurrenciesList;
use App\Livewire\Central\CPanel\Customers\CustomerCreate;
use App\Livewire\Central\CPanel\Customers\CustomerDetails;
use App\Livewire\Central\CPanel\Customers\CustomersList;
use App\Livewire\Central\CPanel\Faqs\FaqForm;
use App\Livewire\Central\CPanel\Faqs\FaqsList;
use App\Livewire\Central\CPanel\Features\FeaturesList;
use App\Livewire\Central\CPanel\Blogs\BlogForm;
use App\Livewire\Central\CPanel\Blogs\BlogsList;
use App\Livewire\Central\CPanel\Pages\PageForm;
use App\Livewire\Central\CPanel\Pages\PagesList;
use App\Livewire\Central\CPanel\Partners\PartnerCommissionsList;
use App\Livewire\Central\CPanel\Partners\PartnerForm;
use App\Livewire\Central\CPanel\Partners\PartnersList;
use App\Livewire\Central\CPanel\Plans\CpanelPlansList;
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
        Route::get('blogs', BlogsList::class)->name('blogs.list');
        Route::get('blogs/create', BlogForm::class)->name('blogs.create');
        Route::get('blogs/{id}', BlogForm::class)->whereNumber('id')->name('blogs.edit');

        Route::get('pages', PagesList::class)->name('pages.list');
        Route::get('pages/create', PageForm::class)->name('pages.create');
        Route::get('pages/{id}', PageForm::class)->whereNumber('id')->name('pages.edit');

        Route::get('faqs', FaqsList::class)->name('faqs.list');
        Route::get('faqs/create', FaqForm::class)->name('faqs.create');
        Route::get('faqs/{id}', FaqForm::class)->whereNumber('id')->name('faqs.edit');
        Route::get('file-manager', FileManagerPage::class)->name('file-manager');
        Route::get('contacts', ContactsList::class)->name('contacts.list');
        Route::get('countries', CountriesList::class)->name('countries.list');
        Route::get('currencies', CurrenciesList::class)->name('currencies.list');
        Route::get('languages', LanguagesList::class)->name('languages.list');
        Route::get('translations', TranslationsEditor::class)->name('translations');
        Route::get('subscriptions', SubscriptionsList::class)->name('subscriptions.list');
        Route::get('plans', CpanelPlansList::class)->name('plans.list');
        Route::get('features', FeaturesList::class)->name('features.list');
        Route::get('register-requests', RegisterRequestsList::class)->name('register-requests.list');
        Route::get('customers', CustomersList::class)->name('customers.list');
        Route::get('customers/create', CustomerCreate::class)->name('customers.create');
        Route::get('customers/{id}', CustomerDetails::class)->name('customers.details');
        Route::get('sliders', SliderList::class)->name('sliders.list');

        Route::get('partners', PartnersList::class)->name('partners.list');
        Route::get('partners/create', PartnerForm::class)->name('partners.create');
        Route::get('partners/{id}', PartnerForm::class)->whereNumber('id')->name('partners.edit');
        Route::get('partner-commissions', PartnerCommissionsList::class)->name('partner-commissions.list');
    });
});
