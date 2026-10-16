<?php

<<<<<<< HEAD
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
/*
|--------------------------------------------------------------------------
| Sultaf Restaurant Complete Routes
|--------------------------------------------------------------------------
*/

// Language Switcher Route
Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', function () {
    return view('menu.index');
})->name('menu.index');

// Guest Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
// Admin Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Menu Management Routes
    Route::get('/admin/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/admin/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/admin/menu', [MenuController::class, 'index'])->name('admin.menu.index');

    // ... other dashboard routes ...
});
    // Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Role-Based Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Owner Dashboard
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');

    // Kasir Dashboard / POS
    Route::get('/kasir/dashboard', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');

    // Dapur (Kitchen) Dashboard
    Route::get('/dapur/dashboard', function () {
        return view('dapur.dashboard');
    })->name('dapur.dashboard');
});
=======
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
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
