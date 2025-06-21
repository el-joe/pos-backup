<?php

use App\Http\Controllers\Central\Tenant\RegisterController;
use Illuminate\Support\Facades\Route;

Route::redirect('/','register');
Route::get('register',[RegisterController::class,'register']);
Route::post('register',[RegisterController::class,'postRegister'])->name('register-domain');
