<?php

use App\Http\Controllers\Central\Tenant\RegisterController;
use App\Livewire\Site\RegisterPage;
use Illuminate\Support\Facades\Route;

foreach(config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::redirect('/','register');
        Route::get('register',[RegisterController::class,'register']);
        Route::post('register',[RegisterController::class,'postRegister'])->name('register-domain')->middleware('throttle:5,60');
        Route::get('register/accept/{id}', [RegisterController::class, 'acceptRegistration'])->name('register-accept');
    });
}
