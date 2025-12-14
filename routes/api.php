<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiJsonResponse;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;

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
    
    // Item routes
    Route::group(['prefix' => 'items', 'middleware' => 'auth:api'], function(){
        // Route::get('items', [ItemController::class, 'index']);
        // Route::get('items/{id}', [ItemController::class, 'show']);
        // Route::get('items/category/{categoryId}', [ItemController::class, 'byCategory']);
        // Route::get('items/search', [ItemController::class, 'search']);
    // AI Recommendations
        Route::get('/', [ItemController::class, 'index']);
        Route::get('/search', [ItemController::class, 'search']);
        Route::get('/category/{categoryId}', [ItemController::class, 'byCategory']);
        Route::get('/{id}', [ItemController::class, 'show']);
    });
    
    // Cart routes (protected)
    Route::group(['prefix' => 'cart', 'middleware' => 'auth:api'], function(){
        Route::get('/', [CartController::class, 'index']);
        Route::post('/items', [CartController::class, 'addItem']);
        Route::put('/items/{itemId}', [CartController::class, 'updateItem']);
        Route::delete('/items/{itemId}', [CartController::class, 'removeItem']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::get('/total', [CartController::class, 'getTotal']);
    });
    
    // Order routes (protected)
    Route::group(['prefix' => 'orders', 'middleware' => 'auth:api'], function(){
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
    });
});


