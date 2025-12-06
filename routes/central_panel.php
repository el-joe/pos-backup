<?php

use App\Http\Controllers\Central\CPanel\AuthController;
use App\Http\Middleware\AdminTranslationMiddleware;
use App\Livewire\Central\CPanel\Admins\AdminsList;
use App\Livewire\Central\CPanel\HomePage;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'cpanel','as' => 'cpanel.','middleware'=> [AdminTranslationMiddleware::class]],function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('postLogin');

    Route::group(['middleware' => ['auth:' . CPANEL_ADMINS_GUARD]], function () {
        Route::get('/', HomePage::class)->name('dashboard');
        Route::get('admins',AdminsList::class)->name('admins.list');
    });
});
