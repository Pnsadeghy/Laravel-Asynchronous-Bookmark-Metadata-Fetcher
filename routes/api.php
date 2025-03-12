<?php

use \App\Http\Controllers\AuthController;
use App\Http\Controllers\User\ArticlesController;
use App\Http\Controllers\User\AuthorsController;
use App\Http\Controllers\User\UserFeedsController;
use App\Http\Controllers\User\NewsSourcesController;
use App\Http\Controllers\User\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});

Route::prefix('user')
    ->name('user.')
    ->middleware(['auth:sanctum'])->group(function () {

});
