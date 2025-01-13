<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\UnAuthMiddleware;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\UnAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::middleware(UnAuthMiddleware::class)->group(function () {
        Route::view('/signin', 'auth.signin')->name('signin');
        Route::post('/signin', [AuthController::class, 'signin'])->name('signin');

        Route::get('/signup', 'auth.signup')->name('signup');
        Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    });

    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('/signout', [AuthController::class, 'signout'])->name('signout');
    });
});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::patch('/notifications', [NotificationController::class, 'edit'])->name('notifications');
    Route::delete('/notifications', [NotificationController::class, 'destroy'])->name('notifications');

    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [UserController::class, 'edit'])->name('settings');
});

Route::middleware(SellerMiddleware::class)->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->name('coupons');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons');
});

Route::resource('products', ProductController::class);
