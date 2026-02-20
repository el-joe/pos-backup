<?php

use App\Http\Controllers\Central\Site\HomeController;
use App\Http\Controllers\Central\Site\PageController;
use App\Http\Controllers\Central\Site\PaymentController;
use App\Http\Controllers\Central\Tenant\RegisterController;
use App\Http\Middleware\CentralWebsitesetLocal;
use App\Http\Middleware\RedirectToSecure;
use App\Http\Middleware\SiteTranslationMiddleware;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/', 'middleware' => [SiteTranslationMiddleware::class, RedirectToSecure::class]], function () {

    Route::get('register', [RegisterController::class, 'register']);
    Route::post('register', [RegisterController::class, 'postRegister'])->name('register-domain')->middleware('throttle:5,10');
    Route::get('register/accept/{id}', [RegisterController::class, 'acceptRegistration'])->name('register-accept');

    Route::get('contact', [HomeController::class, 'contactUsView'])->name('contact');
    Route::post('contact-us', [HomeController::class, 'contactUs'])->name('contact-us');

    Route::get('checkout/{token?}', [HomeController::class, 'checkout'])->name('tenant-checkout');
    Route::get('pricing/compare', [HomeController::class, 'pricingCompare'])->name('pricing-compare');
    Route::get('pricing', [HomeController::class, 'pricing'])->name('pricing');
    // lang in ar|en or null
    Route::get('faqs', [HomeController::class, 'faqs'])->name('faqs');
    Route::get('blogs', [HomeController::class, 'blogs'])->name('blogs.index');
    Route::get('blogs/{slug}', [HomeController::class, 'blogDetailsNoLang'])->name('blogs.show');

    // lang: en|ar with optional BCP47-style subtags, e.g. ar-eg, ar-sa, en-us
    Route::group(['prefix' => '{lang?}', 'as' => 'lang.', 'where' => ['lang' => '(?:en|ar)(?:-[A-Za-z]{2,8})*'], 'middleware' => [CentralWebsitesetLocal::class]], function () {
        Route::get('blogs', [HomeController::class, 'blogs'])->name('blogs.index');
        Route::get('blogs/{slug}', [HomeController::class, 'blogDetails'])->name('blogs.show');
        Route::get('faqs', [HomeController::class, 'faqs'])->name('faqs.index');

        Route::get('{slug}', [PageController::class, 'renderPageWithLang'])->name('static-page.localized');

        Route::get('/', [HomeController::class, 'index'])->name('central-home');
    });

    Route::get('lang/{locale}', [HomeController::class, 'changeLanguage'])->name('site.lang');

    Route::group(['prefix' => 'payment/{type}', 'where' => ['type' => 'check|success|failed']], function () {
        Route::get('/', [PaymentController::class, 'callback'])->name('payment.callback');
        Route::get('/callback', [PaymentController::class, 'paymentCallbackPage'])->name('payment-callback');
    });
    // static pages where not in ar|en group
    Route::get('{slug}', [PageController::class, 'renderPage'])->where('slug', '^(?!cpanel|faqs|blogs|pricing|register|payment).*$')->name('static-page');
});

// add central_panel routes here
require __DIR__ . '/central_panel.php';
