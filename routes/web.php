<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Categorie;

Route::get('/', [PostController::class, 'index'])->name('home');

// Public post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

// Category routes
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('category.posts');
Route::get('/category/{category:slug}/subcategory/{subcategory}', [PostController::class, 'bySubCategory'])->name('category.subcategory.posts');

// Show post (must be after all other post routes to avoid conflicts)
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show')
    ->where('post', '[\w\-]+');

// Comment routes
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post:slug}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [\App\Http\Controllers\CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

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
        // Admin Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        // Categories Management
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CategorieController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\CategorieController::class, 'export'])->name('export');
            Route::get('/create', [\App\Http\Controllers\CategorieController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\CategorieController::class, 'store'])->name('store');
            Route::get('/{categorie}/edit', [\App\Http\Controllers\CategorieController::class, 'edit'])->name('edit');
            Route::put('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'update'])->name('update');
            Route::delete('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'destroy'])->name('destroy');

            // Trash routes
            Route::get('/trash', [\App\Http\Controllers\CategorieController::class, 'trash'])->name('trash');
            Route::patch('/{categorie}/restore', [\App\Http\Controllers\CategorieController::class, 'restore'])->name('restore');
            Route::delete('/{categorie}/force-delete', [\App\Http\Controllers\CategorieController::class, 'deletePermanent'])->name('force-delete');
        });

        // Subcategories Management (independent from categories)
        Route::prefix('subcategories')->name('subcategories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SubCategorieController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\SubCategorieController::class, 'export'])->name('export');
            Route::get('/create', [\App\Http\Controllers\SubCategorieController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\SubCategorieController::class, 'store'])->name('store');
            Route::get('/{subcategory}/edit', [\App\Http\Controllers\SubCategorieController::class, 'edit'])->name('edit');
            Route::put('/{subcategory}', [\App\Http\Controllers\SubCategorieController::class, 'update'])->name('update');
            Route::delete('/{subcategory}', [\App\Http\Controllers\SubCategorieController::class, 'destroy'])->name('destroy');

            // Subcategories Trash routes
            Route::get('/trash', [\App\Http\Controllers\SubCategorieController::class, 'trash'])->name('trash');
            Route::patch('/{subcategory}/restore', [\App\Http\Controllers\SubCategorieController::class, 'restore'])->name('restore');
            Route::delete('/{subcategory}/force-delete', [\App\Http\Controllers\SubCategorieController::class, 'forceDelete'])->name('force-delete');
        });
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
            // My Articles
            Route::get('/my-articles', [PostController::class, 'myArticles'])->name('my-articles');
            // Edit/Update
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('/{post}', [PostController::class, 'update'])->name('update');
            // Publish
            Route::post('/{post}/publish', [PostController::class, 'publish'])->name('publish');
            // Delete
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        });
    });
});
