<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\RewardsController;
use App\Http\Controllers\Dapur\DapurDashboardController;
use App\Http\Controllers\Dapur\KitchenDisplayController;
use App\Http\Controllers\Dapur\MenuAvailabilityController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\PembayaranController;
use App\Http\Controllers\Kasir\PesananController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Shared\LocaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Sultaf Restaurant
|--------------------------------------------------------------------------
| Urutan: publik & pelanggan, lalu per peran (admin, owner, kasir, dapur).
*/

Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Keranjang
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{menu}', [CartController::class, 'add'])->name('add');
    Route::post('/decrease/{menu}', [CartController::class, 'decrease'])->name('decrease');
    Route::delete('/remove/{menu}', [CartController::class, 'remove'])->name('remove');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Checkout & akun pelanggan
Route::middleware('auth')->group(function () {
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', fn () => redirect()->route('checkout.details'))->name('index');
        Route::get('/details', [CheckoutController::class, 'details'])->name('details');
        Route::post('/details', [CheckoutController::class, 'storeDetails'])->name('details.store');
        Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
        Route::post('/payment', [CheckoutController::class, 'storePayment'])->name('payment.store');
        Route::get('/finish/{transaksi}', [CheckoutController::class, 'finish'])->name('finish');
    });

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{transaksi}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// ---- Admin ----
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [AdminMenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');
});

// ---- Owner ----
Route::middleware(['auth', 'role:owner,admin'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
});

// ---- Kasir ----
Route::middleware(['auth', 'role:kasir,admin'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pos', [PosController::class, 'index'])->name('pos');
    Route::post('/pos/add/{menu}', [PosController::class, 'addItem'])->name('pos.add');
    Route::post('/pos/decrease/{menu}', [PosController::class, 'decreaseItem'])->name('pos.decrease');
    Route::delete('/pos/remove/{menu}', [PosController::class, 'removeItem'])->name('pos.remove');
    Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{transaksi}', [PosController::class, 'receipt'])->name('pos.receipt');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/{transaksi}/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::post('/pembayaran/{transaksi}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::patch('/pesanan/{transaksi}', [PesananController::class, 'updateStatus'])->name('pesanan.update');
});

// ---- Dapur ----
Route::middleware(['auth', 'role:dapur,admin'])->prefix('dapur')->name('dapur.')->group(function () {
    Route::get('/dashboard', [DapurDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pesanan', [KitchenDisplayController::class, 'index'])->name('pesanan');
    Route::patch('/pesanan/{transaksi}', [KitchenDisplayController::class, 'updateStatus'])->name('pesanan.update');

    Route::get('/menu', [MenuAvailabilityController::class, 'index'])->name('menu.index');
    Route::patch('/menu/{menu}/toggle', [MenuAvailabilityController::class, 'toggle'])->name('menu.toggle');
});
