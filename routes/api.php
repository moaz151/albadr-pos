<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiJsonResponse;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'middleware' => ApiJsonResponse::class], function(){
    Route::group(['prefix' => 'auth'], function(){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('signup', [AuthController::class, 'signup']);
        Route::get('get-profile', [AuthController::class, 'getProfile'])->middleware('auth:api');
        Route::post('update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:api');
    });
    Route::get('items', [ItemController::class, 'index']);
    Route::get('items/{id}', [ItemController::class, 'show']);
});


