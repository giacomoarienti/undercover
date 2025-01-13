<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\Auth;
use App\Http\Middleware\UnAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {
    Route::middleware(UnAuth::class)->group(function () {
        Route::view('/signin', 'auth.signin')->name('signin');
        Route::post('/signin', [AuthController::class, 'signin'])->name('signin');

        Route::view('/signup', 'auth.signup')->name('signup');
        Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    });

    Route::middleware(Auth::class)->group(function () {
        Route::get('/signout', [AuthController::class, 'signout'])->name('signout');
    });
});


Route::name('client.')->middleware(Auth::class)->group(function () {
    Route::view('/home', 'client.index')->name('index');
});

Route::name('seller.')->middleware(Auth::class)->group(function () {
    Route::view('/', 'seller.index')->name('index');
});

Route::resource('products', ProductController::class);
