<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| TAG CONTROLLER - MANAJEMEN TAG ARTIKEL
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait tag artikel:
| - CRUD tag (Create, Read, Update, Delete)
| - Relasi many-to-many dengan artikel
| - Auto-creation saat user menambah tag baru
| - Soft delete support
|
| Note: Saat ini tag di-create otomatis melalui PostController
| Controller ini bisa dikembangkan untuk manajemen tag manual
|
*/

class TagController extends Controller
{
    /**
     * Menampilkan daftar semua tag
     * Fitur: List semua tag dengan statistik jumlah artikel
     */
    public function index()
    {
        $tags = Tag::withCount('posts')->latest()->paginate(20);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Menampilkan form untuk membuat tag baru
     * Fitur: Tambah tag manual untuk organisasi
     */
    public function create()
    {
        // TODO: Implementasi form create tag
        // View: admin.tags.create
    }

    /**
     * Menyimpan tag baru ke database
     * Fitur: Create tag dengan validasi
     */
    public function store(Request $request)
    {
        // TODO: Implementasi store tag
        // Validation: name required, unique
        // Auto-slug: Str::slug($request->name)
    }

    /**
     * Menampilkan detail tag
     * Fitur: Detail tag dengan list artikel terkait
     */
    public function show(Tag $tag)
    {
        // TODO: Implementasi show tag
        // Load: $tag->with('posts')->findOrFail($tag->id)
        // View: admin.tags.show
    }

    /**
     * Menampilkan form edit tag
     * Fitur: Edit tag yang sudah ada
     */
    public function edit(Tag $tag)
    {
        // TODO: Implementasi edit tag
        // View: admin.tags.edit
    }

    /**
     * Mengupdate data tag
     * Fitur: Update tag dengan validasi
     */
    public function update(Request $request, Tag $tag)
    {
        // TODO: Implementasi update tag
        // Validation: name required, unique (except current)
        // Update slug jika name berubah
    }

    /**
     * Menghapus tag (soft delete)
     * Fitur: Delete tag dengan soft delete
     */
    public function destroy(Tag $tag)
    {
        // TODO: Implementasi delete tag
        // Soft delete: $tag->delete()
        // Redirect: admin.tags.index
    }
}
