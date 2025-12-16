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

Route::group([
    'prefix' => 'v1',
    'middleware' => [
        ApiJsonResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ],
], function(){

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
    
    // Cart routes (public for local cart support)
    Route::group(['prefix' => 'cart'], function(){
        // These endpoints will return either real cart data (for authenticated users)
        // or empty/validation-only responses for guests. The actual storage for
        // unauthenticated users is handled client-side (localStorage).
        Route::get('/', [CartController::class, 'index']);
        Route::post('/items', [CartController::class, 'addItem']);
        Route::put('/items/{itemId}', [CartController::class, 'updateItem']);
        Route::delete('/items/{itemId}', [CartController::class, 'removeItem']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::get('/total', [CartController::class, 'getTotal']);

        // Sync local cart to online cart (requires authentication)
        Route::post('/sync', [CartController::class, 'syncCart'])->middleware('auth:api');
    });
    
    // Order routes
    Route::group(['prefix' => 'orders'], function(){
        // Checkout performs its own auth check to return a custom error shape
        Route::post('/checkout', [OrderController::class, 'checkout']);

        // Other order routes require authentication via middleware
        Route::get('/', [OrderController::class, 'index'])->middleware('auth:api');
        Route::get('/{id}', [OrderController::class, 'show'])->middleware('auth:api');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->middleware('auth:api');
    });
});


