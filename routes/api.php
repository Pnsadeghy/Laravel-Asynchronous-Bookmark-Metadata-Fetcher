<?php

use \App\Http\Controllers\AuthController;
use App\Http\Controllers\User\BookmarksController;
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

    Route::post('bookmarks/{bookmark}/restore', [BookmarksController::class, 'restore'])->name('bookmarks.restore');
    Route::apiResource("bookmarks", BookmarksController::class)
        ->only(['index', 'store', 'destroy']);
});
