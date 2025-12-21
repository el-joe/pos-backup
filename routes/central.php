<?php

use App\Http\Controllers\Central\Site\HomeController;
use App\Http\Controllers\Central\Site\PaymentController;
use App\Http\Controllers\Central\Tenant\RegisterController;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> '/'],function () {
    Route::get('register',[RegisterController::class,'register']);
    Route::post('register',[RegisterController::class,'postRegister'])->name('register-domain')->middleware('throttle:5,10');
    Route::get('register/accept/{id}', [RegisterController::class, 'acceptRegistration'])->name('register-accept');

    Route::get('/',[HomeController::class,'index'])->name('central-home');
    Route::post('contact-us',[HomeController::class,'contactUs'])->name('contact-us');

    Route::get('checkout', [HomeController::class,'checkout'])->name('tenant-checkout');
    Route::get('pricing/compare',[HomeController::class,'pricingCompare'])->name('pricing-compare');

    Route::get('payment/callback/{type}', [PaymentController::class,'callback'])->name('payment.callback');
    Route::get('{type}/payment', [PaymentController::class,'paymentCallbackPage'])->name('payment-callback');
});

Route::get('test', function () {
    PaymentMethod::whereProvider('Paypal')->update([
        'required_fields' => ['client_id','secret'],
    ]);
});

// add central_panel routes here
require __DIR__.'/central_panel.php';
