<?php

use App\Http\Controllers\Central\Site\HomeController;
use App\Http\Controllers\Central\Site\PaymentController;
use App\Http\Controllers\Central\Tenant\RegisterController;
use App\Http\Middleware\SiteTranslationMiddleware;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> '/','middleware' => [SiteTranslationMiddleware::class]],function () {

    Route::get('register',[RegisterController::class,'register']);
    Route::post('register',[RegisterController::class,'postRegister'])->name('register-domain')->middleware('throttle:5,10');
    Route::get('register/accept/{id}', [RegisterController::class, 'acceptRegistration'])->name('register-accept');

    Route::post('contact-us',[HomeController::class,'contactUs'])->name('contact-us');

    Route::get('checkout', [HomeController::class,'checkout'])->name('tenant-checkout');
    Route::get('pricing/compare',[HomeController::class,'pricingCompare'])->name('pricing-compare');
    Route::get('pricing', [HomeController::class,'pricing'])->name('pricing');
    Route::group(['prefix'=>'{lang?}','where' => ['lang' => 'en|ar']],function(){
        Route::get('blogs', [HomeController::class, 'blogs'])->name('blogs.index');
        Route::get('blogs/{slug}', [HomeController::class, 'blogDetails'])->name('blogs.show');
        Route::get('/',[HomeController::class,'index'])->name('central-home');
    });

    Route::get('lang/{locale}', function (string $locale) {
        if (!in_array($locale, ['en', 'ar'], true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        // redirect to same route but with new lang parameter
        $previousUrl = url()->previous();
        if(str_contains($previousUrl, '/en/') || str_contains($previousUrl, '/ar/')){
            $newUrl = preg_replace('/\/(en|ar)\//', '/' . $locale . '/', $previousUrl);
        }else{
            return redirect()->back();
        }

        return redirect($newUrl);
    })->name('site.lang');

    Route::get('payment/callback/{type}', [PaymentController::class,'callback'])->name('payment.callback');
    Route::get('{type}/payment', [PaymentController::class,'paymentCallbackPage'])->name('payment-callback');

});

// add central_panel routes here
require __DIR__.'/central_panel.php';
