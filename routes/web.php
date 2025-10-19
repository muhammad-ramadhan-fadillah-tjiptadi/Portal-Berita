<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');

Route::get('/signup', function () {
    return view('auth.sign');
})->name('signup');

Route::post('/signup', [UserController::class, 'register'])->name('signup.send_data');

Route::post('/auth', [UserController::class, 'authentication'])->name('auth');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('isAdmin')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});

Route::middleware('isUser')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', function () {
            return view('home');
        })->name('home');
    });
});
