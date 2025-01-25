<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReceptionMethodController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\ClientMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\UnAuthMiddleware;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication routes.
 */
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
 * Routes only accessible by sellers.
 */
Route::middleware(SellerMiddleware::class)->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::patch('/coupons', [CouponController::class, 'edit'])->name('coupons.update');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.create');
    Route::delete('/coupons', [CouponController::class, 'destroy'])->name('coupons.destroy');

    Route::post('/settings/reception-methods', [ReceptionMethodController::class, 'store'])->name('reception-methods');
    Route::delete('/settings/reception-methods', [ReceptionMethodController::class, 'delete'])->name('reception-methods');
    Route::patch('/settings/reception-methods', [ReceptionMethodController::class, 'edit'])->name('reception-methods');

    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

/**
 * Route only accessible by clients.
 */
Route::middleware(ClientMiddleware::class)->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'update'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart');
    Route::delete('/cart', [CartController::class, 'remove'])->name('cart');

    Route::post('/settings/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods');
    Route::delete('/settings/payment-methods', [PaymentMethodController::class, 'delete'])->name('payment-methods');
    Route::patch('/settings/payment-methods', [PaymentMethodController::class, 'edit'])->name('payment-methods');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/**
 * Routes only accessible by authenticated users.
 */
Route::middleware(AuthMiddleware::class)->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::patch('/notifications', [NotificationController::class, 'edit'])->name('notifications');
    Route::delete('/notifications', [NotificationController::class, 'destroy'])->name('notifications');

    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::patch('/settings', [UserController::class, 'edit'])->name('settings');

    Route::get('/coupons/{code}', [CouponController::class, 'show'])->name('coupons.show');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::view('/', 'index')->name('index');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
