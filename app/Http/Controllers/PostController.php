<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Categorie;
use App\Models\SubCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        $posts = Post::where('status', 'published')
            ->with('category', 'user')
            ->latest('published_at')
            ->paginate(6);

        return view('home', compact('posts', 'categories'));
    }

    /**
     * Display posts by category.
     */
    public function byCategory(Categorie $category)
    {
        $categories = Categorie::all();
        $category->load('subCategories'); // Eager load subcategories

        $posts = $category->posts()
            ->where('status', 'published')
            ->with(['category', 'subCategory']) // Eager load both category and subcategory
            ->latest('published_at')
            ->paginate(6);

        return view('home', compact('posts', 'categories', 'category'));
    }

    /**
     * Display posts by subcategory.
     */
    public function bySubCategory(Categorie $category, $subcategory)
    {
        $categories = Categorie::all();
        $subcategory = SubCategorie::where('id', $subcategory)
            ->where('category_id', $category->id)
            ->firstOrFail();

        $posts = Post::where('category_id', $category->id)
            ->where('subcategory_id', $subcategory->id)
            ->where('status', 'published')
            ->with(['category', 'subCategory'])
            ->latest('published_at')
            ->paginate(6);

        return view('home', compact('posts', 'categories', 'category', 'subcategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Categorie::all();

        if ($request->has('category_id')) {
            $category = Categorie::with('subCategories')->findOrFail($request->category_id);
            $subcategories = $category->subCategories;
            return view('posts.create', compact('categories', 'subcategories'));
        }

        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Judul artikel harus diisi.',
            'title.string' => 'Judul artikel harus berupa teks.',
            'title.max' => 'Judul artikel tidak boleh lebih dari 255 karakter.',
            'content.required' => 'Isi artikel harus diisi.',
            'content.string' => 'Isi artikel harus berupa teks.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'subcategory_id.required' => 'Sub kategori harus dipilih.',
            'subcategory_id.exists' => 'Sub kategori yang dipilih tidak valid.',
            'image.required' => 'Gambar utama harus diunggah.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->slug = Str::slug($validated['title']);
        $post->content = $validated['content'];
        $post->category_id = $validated['category_id'];
        $post->subcategory_id = $validated['subcategory_id'] ?? null;
        $post->user_id = auth('web')->id();

        // Handle publish status
        $post->status = $request->has('publish') && $request->input('publish') == '1' ? 'published' : 'draft';

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('posts', $imageName, 'public');
            $post->image = $path;
        }

        // Set published_at only if the post is being published
        if ($post->status === 'published') {
            $post->published_at = now();
        }

        $post->save();

        $redirectRoute = $post->status === 'published' ? 'home' : 'user.posts.drafts';
        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil disimpan ' . ($post->status === 'published' ? 'dan dipublikasikan !' : 'sebagai draft !'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if ($post->status !== 'published' && !auth('web')->check()) {
            abort(404);
        }

        $categories = Categorie::all();
        // Get 3 latest published posts excluding the current one
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('status', 'published')
            ->with(['category', 'subCategory'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'categories', 'relatedPosts'));
    }

    public function edit(Post $post)
    {
        $categories = Categorie::all();
        $subcategories = $post->category ? $post->category->subCategories : collect();

        return view('posts.edit', compact('post', 'categories', 'subcategories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Judul artikel harus diisi.',
            'title.string' => 'Judul artikel harus berupa teks.',
            'title.max' => 'Judul artikel tidak boleh lebih dari 255 karakter.',
            'content.required' => 'Isi artikel harus diisi.',
            'content.string' => 'Isi artikel harus berupa teks.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'subcategory_id.exists' => 'Sub kategori yang dipilih tidak valid.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        // Update the post with validated data
        $post->update($validated);

        // Redirect back to drafts list with success message
        return redirect()->route('user.posts.drafts')
            ->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Display a listing of draft posts.
     */
    public function drafts()
    {
        // Debug information
        Log::info('Drafts accessed by user ID: ' . (auth('web')->id() ?? 'Guest'));
        Log::info('Auth check: ' . (auth('web')->check() ? 'Authenticated' : 'Not authenticated'));

        $drafts = Post::where('status', 'draft')
            ->where('user_id', auth('web')->id())
            ->with(['category', 'user'])
            ->latest()
            ->paginate(10);

        Log::info('Found ' . $drafts->count() . ' drafts');

        return view('posts.drafts', compact('drafts'));
    }

    /**
     * Publish the specified draft.
     */
    public function publish(Post $post)
    {
        if ($post->user_id !== auth('web')->id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mempublikasikan artikel ini.');
        }

        $post->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return redirect()->route('home')
            ->with('success', 'Artikel berhasil dipublikasikan !');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== auth('web')->id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }

        // Delete the associated image if it exists
        if ($post->image) {
            Storage::delete('public/' . $post->image);
        }

        // Soft delete the post
        $post->delete();

        // Redirect to the appropriate page based on the post status
        $redirectRoute = $post->status === 'published' ? 'home' : 'posts.drafts';
        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil dihapus !.');
    }

    /**
     * Search posts by title, content, category, or subcategory
     */
    public function search(Request $request)
    {
        // Ambil input dari form search
        $searchQuery = $request->search_post;
        $categories = Categorie::all();

        // Cek jika input search tidak kosong
        if ($searchQuery != "") {
            // LIKE : mencari kata yang mengandung teks tertentu
            $posts = Post::where('status', 'published')
                ->where(function ($query) use ($searchQuery) {
                    // Cari dari title dan content
                    $query->where('title', 'LIKE', '%' . $searchQuery . '%')
                        ->orWhere('content', 'LIKE', '%' . $searchQuery . '%')
                        // Cari dari nama kategori melalui relationship
                        ->orWhereHas('category', function ($q) use ($searchQuery) {
                            $q->where('name', 'LIKE', '%' . $searchQuery . '%');
                        })
                        // Cari dari nama sub kategori melalui relationship
                        ->orWhereHas('subCategory', function ($q) use ($searchQuery) {
                            $q->where('name', 'LIKE', '%' . $searchQuery . '%');
                        });
                })
                ->with(['category', 'user', 'subCategory'])
                ->latest('published_at')
                ->paginate(6);
        } else {
            // Jika search kosong, tampilkan semua posts yang published
            $posts = Post::where('status', 'published')
                ->with(['category', 'user', 'subCategory'])
                ->latest('published_at')
                ->paginate(6);
        }

        return view('home', compact('posts', 'categories'));
    }
}
