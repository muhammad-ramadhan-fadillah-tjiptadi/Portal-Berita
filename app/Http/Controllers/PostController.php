<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Categorie;
use App\Models\SubCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $redirectRoute = $post->status === 'published' ? 'home' : 'posts.drafts';
        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil disimpan ' . ($post->status === 'published' ? 'dan dipublikasikan' : 'sebagai draft'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Display a listing of draft posts.
     */
    public function drafts()
    {
        $drafts = Post::where('status', 'draft')
            ->where('user_id', auth('web')->id())
            ->with(['category', 'user'])
            ->latest()
            ->paginate(10);

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
            ->with('success', 'Artikel berhasil dipublikasikan!');
    }

    public function destroy(Post $post)
    {
        //
    }
}
