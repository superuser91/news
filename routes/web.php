<?php

use Illuminate\Support\Facades\Route;
use Vgplay\News\Controllers\CategoryController;
use Vgplay\News\Controllers\PostController;

Route::middleware('web')->group(function () {
    Route::group([
        'prefix' => config('vgplay.news.prefix'),
        'middleware' => config('vgplay.news.middleware')
    ], function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('posts', PostController::class)->except('show');
    });
});
