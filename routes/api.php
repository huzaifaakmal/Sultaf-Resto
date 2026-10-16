<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentGatewayController;
use App\Http\Controllers\Api\WaiterOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API routes (prefix /api, JWT bearer tokens)
|--------------------------------------------------------------------------
| Scopes per role are defined in App\Services\JwtAuthService.
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('jwt.scope')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::middleware('jwt.scope:orders:read')->prefix('waiter')->group(function () {
    Route::get('/tables', [WaiterOrderController::class, 'tables']);
    Route::get('/orders/{id}', [WaiterOrderController::class, 'show']);
});

Route::post('/waiter/orders', [WaiterOrderController::class, 'store'])->middleware('jwt.scope:orders:write');

Route::post('/payments/intent', [PaymentGatewayController::class, 'createIntent'])->middleware('jwt.scope:payments:write');

// Called by the payment provider, authenticated by an HMAC signature instead of a JWT.
Route::post('/payments/webhook', [PaymentGatewayController::class, 'handleWebhook']);
