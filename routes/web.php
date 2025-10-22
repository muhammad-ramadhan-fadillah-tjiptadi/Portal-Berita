<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Categorie;

Route::get('/', [PostController::class, 'index'])->name('home');

// Public post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Category routes
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('category.posts');
Route::get('/category/{category:slug}/subcategory/{subcategory}', [PostController::class, 'bySubCategory'])->name('category.subcategory.posts');

// Show post (must be after all other post routes to avoid conflicts)
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show')
    ->where('post', '[\w\-]+');

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
        
        // Post CRUD routes
        Route::prefix('posts')->name('posts.')->group(function () {
            // Create
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::post('', [PostController::class, 'store'])->name('store');
            
            // Drafts
            Route::get('/drafts', [PostController::class, 'drafts'])->name('drafts');
            
            // Edit/Update
            Route::get('/{post:slug}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('/{post:slug}', [PostController::class, 'update'])->name('update');
            
            // Publish
            Route::post('/{post}/publish', [PostController::class, 'publish'])->name('publish');
            
            // Delete
            Route::delete('/{post:slug}', [PostController::class, 'destroy'])->name('destroy');
        });
    });
});
