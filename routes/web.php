<?php

use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index'])->name('home');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/payment/{order_number}', [CheckoutController::class, 'payment'])->name('payment.index');
Route::post('/payment/{order_number}/simulate', [CheckoutController::class, 'simulate'])->name('payment.simulate');
Route::get('/order/success/{order_number}', [CheckoutController::class, 'success'])->name('order.success');

// Admin Panel Routes
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminMenuController;

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminMenuController::class, 'index'])->name('admin.dashboard');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('admin.menu.store');
        Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('admin.menu.destroy');
    });
});
