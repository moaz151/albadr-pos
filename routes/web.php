<?php

use Illuminate\Support\Facades\Route;
use Illuminate\support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;

Route::redirect('/', 'admin/home');



Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('items', ItemController::class);
    });
});