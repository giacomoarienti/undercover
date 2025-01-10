<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth
Route::view('/signin', 'auth.signin')->name('signin');
Route::post('/signin', [AuthController::class, 'signin']);
Route::view('/signup', 'auth.signup')->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
