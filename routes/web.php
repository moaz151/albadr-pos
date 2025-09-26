<?php

use Illuminate\Support\Facades\Route;
use Illuminate\support\Facades\Auth;

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
