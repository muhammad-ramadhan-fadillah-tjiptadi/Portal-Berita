<?php

namespace App\Http\Controllers;

use App\Models\SubCategorie;
use App\Models\Categorie;
use App\Exports\SubcategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategorieController extends Controller
{
    public function index()
    {
        $subcategories = SubCategorie::with('category')->latest()->paginate(10);
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        SubCategorie::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['categorie_id'],
        ]);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil ditambahkan !');
    }

    public function edit(SubCategorie $subcategory)
    {
        $categories = Categorie::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, SubCategorie $subcategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $subcategory->update($validated);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil diperbarui !');
    }

    public function destroy(SubCategorie $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Sub Kategori berhasil dihapus !');
    }

    public function trash()
    {
        $subcategories = SubCategorie::onlyTrashed()->with('category')->latest()->paginate(10);
        return view('admin.subcategories.trash', compact('subcategories'));
    }

    public function restore($id)
    {
        $subcategory = SubCategorie::onlyTrashed()->findOrFail($id);
        $subcategory->restore();

        return redirect()->route('admin.subcategories.trash')
            ->with('success', 'Sub Kategori berhasil dipulihkan !');
    }

    public function forceDelete($id)
    {
        $subcategory = SubCategorie::onlyTrashed()->findOrFail($id);
        $subcategory->forceDelete();

        return redirect()->route('admin.subcategories.trash')
            ->with('success', 'Sub Kategori berhasil dihapus permanen !');
    }

    /**
     * Export subcategories to Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new SubcategoriesExport, 'subcategories.xlsx');
    }
}
