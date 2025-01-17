<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReceptionMethodController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ClientMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\UnAuthMiddleware;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::middleware(UnAuthMiddleware::class)->group(function () {
        Route::view('/signin', 'auth.signin')->name('signin');
        Route::post('/signin', [AuthController::class, 'signin'])->name('signin');

        Route::view('/signup', 'auth.signup')->name('signup');
        Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    });

    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('/signout', [AuthController::class, 'signout'])->name('signout');
    });
});

/**
 * Routes only accessible by authenticated users.
 */
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::view('/', 'index')->name('index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::patch('/notifications', [NotificationController::class, 'edit'])->name('notifications');
    Route::delete('/notifications', [NotificationController::class, 'destroy'])->name('notifications');

    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [UserController::class, 'edit'])->name('settings');

    Route::post('/settings/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods');
    Route::delete('/settings/payment-methods', [PaymentMethodController::class, 'delete'])->name('payment-methods');
    Route::patch('/settings/payment-methods', [PaymentMethodController::class, 'edit'])->name('payment-methods');
});

/**
 * Routes only accessible by sellers.
 */
Route::middleware(SellerMiddleware::class)->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->name('coupons');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons');

    Route::resource('products', ProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::post('/settings/reception-methods', [ReceptionMethodController::class, 'store'])->name('reception-methods');
    Route::delete('/settings/reception-methods', [ReceptionMethodController::class, 'destroy'])->name('reception-methods');
    Route::patch('/settings/reception-methods', [ReceptionMethodController::class, 'edit'])->name('reception-methods');
});

/**
 * Route only accessible by clients.
 */
Route::middleware(ClientMiddleware::class)->group(function () {
    Route::get('/cart', [ProductController::class, 'index'])->name('cart');
    Route::post('/cart', [ProductController::class, 'add'])->name('cart');
    Route::delete('/cart', [ProductController::class, 'remove'])->name('cart');
});

/**
 * Routes accessible both by guests and authenticated users.
 */
Route::resource('products', ProductController::class)->only(['index', 'show']);
