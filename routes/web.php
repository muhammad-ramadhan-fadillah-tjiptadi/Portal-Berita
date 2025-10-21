<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Categorie;

Route::get('/', [PostController::class, 'index'])->name('home');

// Category route
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('category.posts');

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

Route::middleware(['auth'])->group(function () {
    // Route untuk artikel
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // Route untuk draft artikel
    Route::get('/posts/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
});
