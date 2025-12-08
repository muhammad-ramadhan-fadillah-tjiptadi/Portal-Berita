<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Categorie::withCount('posts')
            ->latest()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Create a new controller instance.
     */
    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Export categories to Excel
     */
    public function export()
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Nama kategori sudah terdaftar sebelumnya',
            'description.string' => 'Deskripsi harus berupa teks',
        ]);

        // Auto-generate slug dari nama kategori
        $validated['slug'] = Str::slug($validated['name']);
        $validated['description'] = $request->description ?? ''; // Ensure description is not null

        Categorie::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan !');
    }

    /**
     * Display the specified category.
     */
    public function show(Categorie $categorie)
    {
        // Not used in admin panel
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
