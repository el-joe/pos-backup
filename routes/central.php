<?php

use App\Http\Controllers\Central\Site\HomeController;
use App\Http\Controllers\Central\Tenant\RegisterController;
use App\Livewire\Site\RegisterPage;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> '/'],function () {
    Route::get('register',[RegisterController::class,'register']);
    Route::post('register',[RegisterController::class,'postRegister'])->name('register-domain')->middleware('throttle:5,10');
    Route::get('register/accept/{id}', [RegisterController::class, 'acceptRegistration'])->name('register-accept');

    Route::get('/',[HomeController::class,'index'])->name('central-home');
    Route::post('contact-us',[HomeController::class,'contactUs'])->name('contact-us');
});
