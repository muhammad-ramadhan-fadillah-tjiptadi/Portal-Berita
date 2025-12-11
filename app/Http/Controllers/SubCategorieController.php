<?php

namespace App\Http\Controllers;

use App\Models\SubCategorie;
use App\Models\Categorie;
use App\Exports\SubcategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| SUBCATEGORIE CONTROLLER - MANAJEMEN SUBKATEGORI ARTIKEL
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait subkategori:
| - CRUD subkategori (Create, Read, Update, Delete)
| - Relasi dengan kategori induk
| - Export data subkategori ke Excel
| - Soft delete support
|
*/

class SubCategorieController extends Controller
{
    /**
     * Menampilkan daftar semua subkategori di dashboard admin
     * Fitur: Manajemen subkategori dengan info kategori induk
     */
    public function index()
    {
        // Query semua subkategori dengan relasi kategori:
        // 1. with('category') eager load relasi kategori induk
        //    untuk menghindari N+1 query problem
        // 2. latest() urutkan berdasarkan created_at terbaru
        // 3. get() execute query
        $subcategories = SubCategorie::with('category')->latest()->get();

        // Kirim data ke view admin.subcategories.index.blade.php
        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Menampilkan form untuk membuat subkategori baru
     * Fitur: Tambah subkategori dengan pemilihan kategori induk
     */
    public function create()
    {
        // Ambil semua kategori untuk dropdown pemilihan kategori induk
        $categories = Categorie::all();

        // Kirim data ke view admin.subcategories.create.blade.php
        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Menyimpan subkategori baru ke database
     * Fitur: Create subkategori dengan validasi dan relasi kategori
     */
    public function store(Request $request)
    {
        // VALIDASI INPUT SUBKATEGORI
        // Laravel Validation memastikan data subkategori valid
        $validated = $request->validate([
            'name' => 'required|string|max:255',                // Nama wajib, max 255 karakter
            'description' => 'nullable|string',                  // Deskripsi opsional
            'categorie_id' => 'required|exists:categories,id',   // Kategori induk wajib, harus ada
        ], [
            // Custom error messages dalam bahasa Indonesia
            'name.required' => 'Nama sub kategori wajib diisi',
            'name.string' => 'Nama sub kategori harus berupa teks',
            'name.max' => 'Nama sub kategori maksimal 255 karakter',
            'description.string' => 'Deskripsi harus berupa teks',
            'categorie_id.required' => 'Kategori induk wajib dipilih',
            'categorie_id.exists' => 'Kategori induk yang dipilih tidak valid',
        ]);

        // SIMPAN SUBKATEGORI KE DATABASE
        // SubCategorie::create() akan mass assignment dengan validated data
        SubCategorie::create([
            'name' => $validated['name'],                              // Nama subkategori
            'description' => $validated['description'] ?? null,         // Deskripsi (nullable)
            'category_id' => $validated['categorie_id'],                // ID kategori induk
        ]);

        // REDIRECT KE LIST SUBKATEGORI
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil ditambahkan !');
    }

    /**
     * Menampilkan form edit subkategori
     * Fitur: Edit subkategori dengan dropdown kategori induk
     */
    public function edit(SubCategorie $subcategory)
    {
        // Ambil semua kategori untuk dropdown pemilihan kategori induk
        $categories = Categorie::all();

        // Kirim data ke view admin.subcategories.edit.blade.php
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Mengupdate data subkategori yang ada
     * Fitur: Update subkategori dengan validasi
     */
    public function update(Request $request, SubCategorie $subcategory)
    {
        // VALIDASI INPUT UPDATE (sama seperti store)
        $validated = $request->validate([
            'name' => 'required|string|max:255',                // Nama wajib, max 255 karakter
            'description' => 'nullable|string',                  // Deskripsi opsional
            'categorie_id' => 'required|exists:categories,id',   // Kategori induk wajib, harus ada
        ], [
            // Custom error messages dalam bahasa Indonesia
            'name.required' => 'Nama sub kategori wajib diisi',
            'name.string' => 'Nama sub kategori harus berupa teks',
            'name.max' => 'Nama sub kategori maksimal 255 karakter',
            'description.string' => 'Deskripsi harus berupa teks',
            'categorie_id.required' => 'Kategori induk wajib dipilih',
            'categorie_id.exists' => 'Kategori induk yang dipilih tidak valid',
        ]);

        // UPDATE SUBKATEGORI DI DATABASE
        // Laravel akan otomatis handle field assignment dan update
        $subcategory->update($validated);

        // REDIRECT KE LIST SUBKATEGORI
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil diperbarui !');
    }

    /**
     * Menghapus subkategori (soft delete)
     * Fitur: Delete subkategori dengan soft delete protection
     */
    public function destroy(SubCategorie $subcategory)
    {
        // SOFT DELETE SUBKATEGORI
        // delete() akan soft delete karena trait SoftDeletes di model
        // Artinya:
        // - deleted_at akan diisi dengan timestamp
        // - Subkategori tidak akan muncul di query normal
        // - Data masih ada di database (bisa di-restore)
        $subcategory->delete();

        // REDIRECT KE LIST SUBKATEGORI
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil dihapus !');
    }

    /**
     * Menampilkan daftar subkategori yang dihapus (trash)
     * Fitur: Restore permanent delete management
     */
    public function trash()
    {
        // Query subkategori yang sudah dihapus:
        // 1. onlyTrashed() hanya ambil data yang deleted_at tidak null
        // 2. with('category') load relasi kategori induk
        // 3. latest() urutkan berdasarkan deleted_at terbaru
        // 4. paginate(10) batasi 10 data per halaman
        $subcategories = SubCategorie::onlyTrashed()->with('category')->latest()->paginate(10);

        // Kirim data ke view admin.subcategories.trash.blade.php
        return view('admin.subcategories.trash', compact('subcategories'));
    }

    /**
     * Memulihkan subkategori yang dihapus
     * Fitur: Restore soft deleted subcategories
     */
    public function restore($id)
    {
        // Cari subkategori yang sudah dihapus
        // onlyTrashed() + findOrFail() akan mencari di trash
        $subcategory = SubCategorie::onlyTrashed()->findOrFail($id);

        // RESTORE SUBKATEGORI
        // restore() akan menghapus deleted_at timestamp
        // Subkategori akan muncul kembali di query normal
        $subcategory->restore();

        // REDIRECT KE TRASH PAGE
        return redirect()->route('admin.subcategories.trash')
            ->with('success', 'Sub Kategori berhasil dipulihkan !');
    }

    /**
     * Menghapus subkategori secara permanen
     * Fitur: Permanent delete subcategories
     */
    public function forceDelete($id)
    {
        // Cari subkategori yang sudah dihapus
        // onlyTrashed() + findOrFail() akan mencari di trash
        $subcategory = SubCategorie::onlyTrashed()->findOrFail($id);

        // PERMANENT DELETE SUBKATEGORI
        // forceDelete() akan menghapus data secara permanen
        // Data tidak bisa di-restore lagi
        $subcategory->forceDelete();

        // REDIRECT KE TRASH PAGE
        return redirect()->route('admin.subcategories.trash')
            ->with('success', 'Sub Kategori berhasil dihapus permanen !');
    }

    /**
     * Export data subkategori ke file Excel
     * Fitur: Backup dan analisis data subkategori
     * Export subcategories to Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new SubcategoriesExport, 'subcategories_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
