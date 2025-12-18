<?php

use App\Http\Controllers\Central\CPanel\AuthController;
use App\Http\Middleware\CpanelTranslationMiddleware;
use App\Livewire\Central\CPanel\Admins\AdminsList;
use App\Livewire\Central\CPanel\Contacts\ContactsList;
use App\Livewire\Central\CPanel\Countries\CountriesList;
use App\Livewire\Central\CPanel\Currencies\CurrenciesList;
use App\Livewire\Central\CPanel\Customers\CustomersList;
use App\Livewire\Central\CPanel\HomePage;
use App\Livewire\Central\CPanel\Languages\LanguagesList;
use App\Livewire\Central\CPanel\Plans\CpanelPlansList;
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
        Route::get('contacts', ContactsList::class)->name('contacts.list');
        Route::get('countries', CountriesList::class)->name('countries.list');
        Route::get('currencies', CurrenciesList::class)->name('currencies.list');
        Route::get('languages', LanguagesList::class)->name('languages.list');
        Route::get('subscriptions', SubscriptionsList::class)->name('subscriptions.list');
        Route::get('register-requests', RegisterRequestsList::class)->name('register-requests.list');
        Route::get('customers', CustomersList::class)->name('customers.list');
        Route::get('plans', CpanelPlansList::class)->name('plans.list');
        Route::get('sliders', SliderList::class)->name('sliders.list');
    });
});
