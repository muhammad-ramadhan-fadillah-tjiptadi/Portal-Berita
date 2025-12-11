<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('admin.tags.create');
    }

    /**
     * Menyimpan tag baru ke database
     * Fitur: Create tag dengan validasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil dibuat!');
    }

    /**
     * Menampilkan detail tag
     * Fitur: Detail tag dengan list artikel terkait
     */
    public function show(Tag $tag)
    {
        $tag->load('posts');
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Menampilkan form edit tag
     * Fitur: Edit tag yang sudah ada
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Mengupdate data tag
     * Fitur: Update tag dengan validasi
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil diperbarui!');
    }

    /**
     * Menghapus tag (soft delete)
     * Fitur: Delete tag dengan soft delete
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag berhasil dihapus!');
    }

    /**
     * Menampilkan tag yang dihapus (trash)
     * Fitur: Lihat tag yang dihapus untuk restore
     */
    public function trash()
    {
        $tags = Tag::onlyTrashed()->withCount('posts')->latest('deleted_at')->paginate(20);
        return view('admin.tags.trash', compact('tags'));
    }

    /**
     * Mengembalikan tag yang dihapus
     * Fitur: Restore tag dari trash
     */
    public function restore($id)
    {
        $tag = Tag::onlyTrashed()->findOrFail($id);
        $tag->restore();

        return redirect()->route('admin.tags.trash')
            ->with('success', 'Tag berhasil dikembalikan!');
    }

    /**
     * Menghapus tag permanen
     * Fitur: Force delete tag dari database
     */
    public function forceDelete($id)
    {
        $tag = Tag::onlyTrashed()->findOrFail($id);
        $tag->forceDelete();

        return redirect()->route('admin.tags.trash')
            ->with('success', 'Tag berhasil dihapus permanen!');
    }

    /**
     * Export tag ke Excel
     * Fitur: Download data tag dalam format Excel
     */
    public function export()
    {
        $tags = Tag::withCount('posts')->latest()->get();

        $filename = "tags_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen($filename, 'w+');

        // Header CSV
        fputcsv($handle, ['ID', 'Nama Tag', 'Slug', 'Jumlah Artikel', 'Dibuat', 'Diupdate']);

        // Data CSV
        foreach ($tags as $tag) {
            fputcsv($handle, [
                $tag->id,
                $tag->name,
                $tag->slug,
                $tag->posts_count,
                $tag->created_at->format('d-m-Y H:i'),
                $tag->updated_at->format('d-m-Y H:i')
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
