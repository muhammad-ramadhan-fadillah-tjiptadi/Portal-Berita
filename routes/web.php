<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Categorie;

/*
|--------------------------------------------------------------------------
| PORTAL-BERITA - SISTEM PORTAL BERITA
|--------------------------------------------------------------------------
|
| File ini berisi semua route untuk aplikasi portal berita Portal-Berita.
| Aplikasi ini memiliki sistem manajemen konten lengkap dengan role user,
| manajemen artikel, sistem komentar, dan fitur administratif.
|
| FITUR UTAMA:
| 1. Sistem Autentikasi User (Login, Register, Logout)
| 2. Manajemen Artikel (Buat, Baca, Edit, Hapus, Publish)
| 3. Sistem Kategori & Subkategori
| 4. Sistem Komentar dengan Balasan
| 5. Sistem Tag untuk Artikel
| 6. Fitur Pencarian
| 7. Dashboard Admin & Manajemen
| 8. Profil User
| 9. Sistem Draft untuk Artikel
| 10. Soft Delete & Trash Management
|
*/

// =============================================================================
// ROUTE PUBLIK (Tanpa Perlu Login)
// =============================================================================

// Halaman utama - Menampilkan semua artikel yang sudah dipublish
Route::get('/', [PostController::class, 'index'])->name('home');

// Route publik untuk artikel
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

// Route untuk filter berdasarkan kategori dan subkategori
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('category.posts');
Route::get('/category/{category:slug}/subcategory/{subcategory}', [PostController::class, 'bySubCategory'])->name('category.subcategory.posts');

// Menampilkan artikel individual (harus di route paling bawah agar tidak konflik)
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show')
    ->where('post', '[\w\-]+');

// =============================================================================
// SISTEM KOMENTAR (Perlu Login)
// =============================================================================
Route::middleware('auth')->group(function () {
    // Operasi CRUD untuk komentar
    Route::post('/posts/{post:slug}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
    Route::get('/comments/{comment}/edit', [\App\Http\Controllers\CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

// =============================================================================
// ROUTE AUTENTIKASI (Bisa Diakses Publik)
// =============================================================================
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

// =============================================================================
// ROUTE ADMIN (Hanya untuk Role Admin)
// =============================================================================
Route::middleware('isAdmin')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin - Menampilkan statistik sistem
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        // Manajemen Kategori - CRUD lengkap dengan soft delete
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CategorieController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\CategorieController::class, 'export'])->name('export');
            Route::get('/create', [\App\Http\Controllers\CategorieController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\CategorieController::class, 'store'])->name('store');
            Route::get('/{categorie}/edit', [\App\Http\Controllers\CategorieController::class, 'edit'])->name('edit');
            Route::put('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'update'])->name('update');
            Route::delete('/{categorie}', [\App\Http\Controllers\CategorieController::class, 'destroy'])->name('destroy');
            // Manajemen Soft Delete (Sistem Trash)
            Route::get('/trash', [\App\Http\Controllers\CategorieController::class, 'trash'])->name('trash');
            Route::patch('/{categorie}/restore', [\App\Http\Controllers\CategorieController::class, 'restore'])->name('restore');
            Route::delete('/{categorie}/force-delete', [\App\Http\Controllers\CategorieController::class, 'deletePermanent'])->name('force-delete');
        });

        // Manajemen Subkategori - Independen dari kategori dengan CRUD lengkap
        Route::prefix('subcategories')->name('subcategories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SubCategorieController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\SubCategorieController::class, 'export'])->name('export');
            Route::get('/create', [\App\Http\Controllers\SubCategorieController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\SubCategorieController::class, 'store'])->name('store');
            Route::get('/{subcategory}/edit', [\App\Http\Controllers\SubCategorieController::class, 'edit'])->name('edit');
            Route::put('/{subcategory}', [\App\Http\Controllers\SubCategorieController::class, 'update'])->name('update');
            Route::delete('/{subcategory}', [\App\Http\Controllers\SubCategorieController::class, 'destroy'])->name('destroy');
            // Manajemen Trash untuk Subkategori
            Route::get('/trash', [\App\Http\Controllers\SubCategorieController::class, 'trash'])->name('trash');
            Route::patch('/{subcategory}/restore', [\App\Http\Controllers\SubCategorieController::class, 'restore'])->name('restore');
            Route::delete('/{subcategory}/force-delete', [\App\Http\Controllers\SubCategorieController::class, 'forceDelete'])->name('force-delete');
        });

        // Manajemen Comments - CRUD lengkap dengan soft delete
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\CommentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\CommentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\CommentController::class, 'adminStore'])->name('store');
            Route::get('/{comment}/edit', [\App\Http\Controllers\CommentController::class, 'edit'])->name('edit');
            Route::put('/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('update');
            Route::delete('/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('destroy');
            // Manajemen Trash untuk Comments
            Route::get('/trash', [\App\Http\Controllers\CommentController::class, 'trash'])->name('trash');
            Route::patch('/{comment}/restore', [\App\Http\Controllers\CommentController::class, 'restore'])->name('restore');
            Route::delete('/{comment}/force-delete', [\App\Http\Controllers\CommentController::class, 'forceDelete'])->name('force-delete');
        });

        // Manajemen Tags - CRUD lengkap dengan soft delete
        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', [\App\Http\Controllers\TagController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\TagController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\TagController::class, 'store'])->name('store');
            Route::get('/{tag}/edit', [\App\Http\Controllers\TagController::class, 'edit'])->name('edit');
            Route::put('/{tag}', [\App\Http\Controllers\TagController::class, 'update'])->name('update');
            Route::delete('/{tag}', [\App\Http\Controllers\TagController::class, 'destroy'])->name('destroy');
            // Manajemen Trash untuk Tags
            Route::get('/trash', [\App\Http\Controllers\TagController::class, 'trash'])->name('trash');
            Route::patch('/{tag}/restore', [\App\Http\Controllers\TagController::class, 'restore'])->name('restore');
            Route::delete('/{tag}/force-delete', [\App\Http\Controllers\TagController::class, 'forceDelete'])->name('force-delete');
        });

        // Manajemen Users - CRUD lengkap dengan soft delete
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
            // Manajemen Trash untuk Users
            Route::get('/trash', [\App\Http\Controllers\UserController::class, 'trash'])->name('trash');
            Route::patch('/{user}/restore', [\App\Http\Controllers\UserController::class, 'restore'])->name('restore');
            Route::delete('/{user}/force-delete', [\App\Http\Controllers\UserController::class, 'forceDelete'])->name('force-delete');
        });
    });
});

// =============================================================================
// ROUTE USER (Hanya untuk User yang Sudah Login)
// =============================================================================
Route::middleware('isUser')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        // Halaman utama user (redirect ke home)
        Route::get('/', function () {
            return view('home');
        })->name('home');

        // Sistem Manajemen Artikel - CRUD lengkap dengan workflow publishing
        Route::prefix('posts')->name('posts.')->group(function () {
            // Membuat artikel baru dengan pemilihan kategori
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::post('', [PostController::class, 'store'])->name('store');
            // Manajemen Draft - Simpan artikel sebagai draft sebelum dipublish
            Route::get('/drafts', [PostController::class, 'drafts'])->name('drafts');
            // Manajemen Artikel yang Dipublish - Lihat artikel user yang sudah dipublish
            Route::get('/my-articles', [PostController::class, 'myArticles'])->name('my-articles');
            // Manajemen Komentar - Lihat dan kelola komentar user
            Route::get('/my-comments', [\App\Http\Controllers\CommentController::class, 'myComments'])->name('my-comments');
            // Edit Artikel dengan manajemen tag
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('/{post}', [PostController::class, 'update'])->name('update');
            // Sistem Publishing - Ubah draft menjadi artikel dipublish
            Route::post('/{post}/publish', [PostController::class, 'publish'])->name('publish');
            // Hapus Artikel dengan dukungan soft delete
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        });
    });
});
