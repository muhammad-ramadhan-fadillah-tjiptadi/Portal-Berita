<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| CATEGORIE CONTROLLER - MANAJEMEN KATEGORI ARTIKEL
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait kategori artikel:
| - CRUD kategori (Create, Read, Update, Delete)
| - Export data kategori ke Excel
| - Soft delete support
| - Count posts per kategori
|
*/

class CategorieController extends Controller
{
    /**
     * Menampilkan daftar semua kategori di dashboard admin
     * Fitur: Manajemen kategori dengan statistik jumlah artikel
     */
    public function index()
    {
        // Query semua kategori dengan count artikel:
        // 1. withCount('posts') akan menambahkan field 'posts_count'
        //    berisi jumlah artikel untuk setiap kategori
        // 2. latest() urutkan berdasarkan created_at terbaru
        // 3. get() execute query
        $categories = Categorie::withCount('posts')
            ->latest()
            ->get();

        // Kirim data ke view admin.categories.index.blade.php
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru
     * Fitur: Tambah kategori baru untuk organisasi artikel
     */
    public function create()
    {
        // Tampilkan form create kategori
        return view('admin.categories.create');
    }

    /**
     * Export data kategori ke file Excel
     * Fitur: Backup dan analisis data kategori
     */
    public function export()
    {
        // Export data kategori menggunakan Laravel Excel
        // CategoriesExport class akan format data untuk Excel
        return Excel::download(new CategoriesExport, 'categories_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Menyimpan kategori baru ke database
     * Fitur: Create kategori dengan validasi dan auto-slug
     */
    public function store(Request $request)
    {
        // VALIDASI INPUT KATEGORI
        // Laravel Validation memastikan data kategori valid
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',    // Nama wajib, max 255, unik
            'description' => 'nullable|string',                              // Deskripsi opsional
        ], [
            // Custom error messages dalam bahasa Indonesia
            'name.required' => 'Nama kategori wajib diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Nama kategori sudah terdaftar sebelumnya',
            'description.string' => 'Deskripsi harus berupa teks',
        ]);

        // AUTO-GENERATE SLUG
        // Str::slug() akan mengubah nama kategori menjadi URL-friendly:
        // "Berita Teknologi" -> "berita-teknologi"
        $validated['slug'] = Str::slug($validated['name']);

        // ENSURE DESCRIPTION NOT NULL
        // Jika description kosong, set ke empty string
        $validated['description'] = $request->description ?? '';

        // SIMPAN KATEGORI KE DATABASE
        // Categorie::create() akan mass assignment dengan validated data
        Categorie::create($validated);

        // REDIRECT KE LIST KATEGORI
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan !');
    }

    /**
     * Menampilkan detail kategori (tidak digunakan)
     * Note: Method ini tidak digunakan di admin panel
     */
    public function show(Categorie $categorie)
    {
        // Return 404 karena fitur tidak diimplementasikan
        abort(404);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', ['category' => $categorie]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $categorie->id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Nama kategori sudah terdaftar sebelumnya',
            'description.string' => 'Deskripsi harus berupa teks',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['description'] = $request->description ?? ''; // Ensure description is not null
        $categorie->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui !');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Categorie $categorie)
    {
        if ($categorie->posts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kategori yang memiliki artikel');
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dipindahkan ke tempat sampah !');
    }

    /**
     * Display a listing of the trashed categories.
     */
    public function trash()
    {
        $trashedCategories = Categorie::onlyTrashed()->get();
        return view('admin.categories.trash', compact('trashedCategories'));
    }

    /**
     * Restore the specified category from trash.
     */
    public function restore($id)
    {
        $category = Categorie::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('admin.categories.trash')
            ->with('success', 'Kategori berhasil dikembalikan !');
    }

    /**
     * Permanently delete the specified category.
     */
    public function deletePermanent($id)
    {
        $category = Categorie::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->route('admin.categories.trash')
            ->with('success', 'Kategori berhasil dihapus permanen !');
    }
}
