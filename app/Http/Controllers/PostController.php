<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Categorie;
use App\Models\SubCategorie;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| POST CONTROLLER - MANAJEMEN ARTIKEL
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait artikel:
| - Menampilkan artikel di halaman utama
| - Membuat, mengedit, menghapus artikel
| - Sistem publish dan draft
| - Manajemen tag artikel
| - Pencarian artikel
| - Filter berdasarkan kategori
|
*/

class PostController extends Controller
{
    /**
     * Menampilkan halaman utama dengan semua artikel yang sudah dipublish
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua kategori untuk dropdown filter
        $categories = Categorie::all();

        // Query artikel yang sudah dipublish:
        // 1. Filter status = 'published' (hanya artikel yang dipublish)
        // 2. with() untuk eager load relasi (menghindari N+1 query problem)
        //    - category: relasi ke tabel kategori
        //    - user: relasi ke tabel user (penulis)
        //    - tags: relasi ke tabel tags (many-to-many)
        // 3. latest() urutkan berdasarkan published_at terbaru
        // 4. paginate(6) batasi 6 artikel per halaman
        $posts = Post::where('status', 'published')
            ->with('category', 'user', 'tags')
            ->latest('published_at')
            ->paginate(6);

        // Kirim data ke view home.blade.php
        return view('home', compact('posts', 'categories'));
    }

    /**
     * Menampilkan artikel berdasarkan kategori tertentu
     *
     * @param Categorie $category
     * @return \Illuminate\View\View
     */
    public function byCategory(Categorie $category)
    {
        // Ambil semua kategori untuk navigasi
        $categories = Categorie::all();

        // Load relasi subcategories dari kategori yang dipilih
        // Menghindari query tambahan saat mengakses $category->subCategories di view
        $category->load('subCategories');

        // Query artikel berdasarkan kategori:
        // 1. $category->posts() gunakan relasi dari model Category
        // 2. Filter status = 'published' (hanya artikel yang dipublish)
        // 3. with() eager load relasi untuk optimasi query
        // 4. latest() urutkan berdasarkan published_at terbaru
        // 5. paginate(6) batasi 6 artikel per halaman
        $posts = $category->posts()
            ->where('status', 'published')
            ->with(['category', 'subCategory', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(6);

        // Kirim data ke view home.blade.php dengan filter kategori aktif
        return view('home', compact('posts', 'categories', 'category'));
    }

    /**
     * Menampilkan artikel berdasarkan subkategori tertentu
     *
     * @param Categorie $category
     * @param mixed $subcategory
     * @return \Illuminate\View\View
     */
    public function bySubCategory(Categorie $category, $subcategory)
    {
        // Ambil semua kategori untuk navigasi
        $categories = Categorie::all();

        // Cari subkategori berdasarkan ID dan pastikan milik kategori yang benar
        // firstOrFail() akan throw 404 jika tidak ditemukan
        $subcategory = SubCategorie::where('id', $subcategory)
            ->where('category_id', $category->id)
            ->firstOrFail();

        // Query artikel berdasarkan kategori dan subkategori:
        // 1. Filter berdasarkan category_id dan subcategory_id
        // 2. Filter status = 'published' (hanya artikel yang dipublish)
        // 3. with() eager load relasi untuk optimasi
        // 4. latest() urutkan berdasarkan published_at terbaru
        // 5. paginate(6) batasi 6 artikel per halaman
        $posts = Post::where('category_id', $category->id)
            ->where('subcategory_id', $subcategory->id)
            ->where('status', 'published')
            ->with(['category', 'subCategory', 'user', 'tags'])
            ->latest('published_at')
            ->paginate(6);

        // Kirim data ke view dengan filter kategori dan subkategori aktif
        return view('home', compact('posts', 'categories', 'category', 'subcategory'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Ambil semua kategori untuk dropdown pemilihan
        $categories = Categorie::all();

        // Cek apakah user sudah memilih kategori (step 1 dari 2-step form)
        if ($request->has('category_id')) {
            // Jika kategori dipilih, tampilkan form lengkap:
            // 1. Cari kategori beserta subcategories-nya
            // 2. with('subCategories') eager load untuk optimasi
            // 3. findOrFail() akan throw 404 jika kategori tidak ada
            $category = Categorie::with('subCategories')->findOrFail($request->category_id);
            $subcategories = $category->subCategories;

            // Tampilkan form lengkap dengan pilihan subkategori
            return view('posts.create', compact('categories', 'category', 'subcategories'));
        }

        // Jika belum memilih kategori, tampilkan form pemilihan kategori dulu
        return view('posts.create', compact('categories'));
    }

    /**
     * Menyimpan artikel baru ke database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // VALIDASI INPUT ARTIKEL
        // Menggunakan Laravel Validation untuk memastikan data valid:
        // - title: wajib, string, max 255 karakter
        // - content: wajib, string (tidak ada batas panjang)
        // - category_id: wajib, harus ada di tabel categories
        // - subcategory_id: wajib, harus ada di tabel sub_categories
        // - image: wajib, harus gambar, format tertentu, max 2MB
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Custom error messages dalam bahasa Indonesia
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

        // BUAT OBJEK POST BARU
        $post = new Post();

        // Isi field artikel dengan data yang sudah valid:
        // - title: judul artikel
        // - slug: URL-friendly version dari title (untuk SEO)
        // - content: isi artikel
        $post->title = $validated['title'];
        $post->slug = Str::slug($validated['title']);
        $post->content = $validated['content'];
        // - category_id: ID kategori
        $post->category_id = $validated['category_id'];
        // - subcategory_id: ID subkategori (nullable)
        $post->subcategory_id = $validated['subcategory_id'] ?? null;
        // - user_id: ID user yang sedang login
        $post->user_id = auth('web')->id();

        // TENTUKAN STATUS ARTIKEL
        // Default: 'draft' (belum dipublish)
        // User bisa memilih 'published' melalui tombol publish
        $post->status = $request->input('status', 'draft');

        // PROSES UPLOAD GAMBAR
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // 1. Ambil file yang diupload
            $image = $request->file('image');

            // 2. Buat nama file unik dengan timestamp untuk menghindari duplikat
            // Format: timestamp_originalname.jpg
            $imageName = time() . '_' . $image->getClientOriginalName();

            // 3. Simpan file ke storage/app/public/posts/
            // storeAs() akan otomatis handle file storage
            $path = $image->storeAs('posts', $imageName, 'public');

            // 4. Simpan path ke database
            $post->image = $path;
        }

        // SET TANGGAL PUBLISH
        // Hanya set published_at jika artikel dipublish
        // Draft tidak memiliki tanggal publish
        if ($post->status === 'published') {
            $post->published_at = now();
        }

        // SIMPAN ARTIKEL KE DATABASE
        $post->save();

        // PROSES TAGS (FITUR BARU)
        // Tags adalah fitur untuk mengkategorikan artikel dengan keyword
        // User input tags dipisahkan dengan koma (contoh: "teknologi, ai, machine learning")
        if ($request->has('tags')) {
            // 1. Pisahkan tags berdasarkan koma
            $tagNames = array_filter(explode(',', $request->input('tags')));
            $tagIds = [];

            // 2. Loop setiap tag name
            foreach ($tagNames as $tagName) {
                // 3. Clean tag name (hapus spasi di awal/akhir)
                $tagName = trim($tagName);

                // 4. Jika tag name tidak kosong
                if (!empty($tagName)) {
                    // 5. Cari tag di database, jika tidak ada buat baru
                    // firstOrCreate() akan:
                    // - Mencari tag berdasarkan name
                    // - Jika ada, return tag yang ada
                    // - Jika tidak ada, buat tag baru
                    $tag = Tag::firstOrCreate(['name' => $tagName]);

                    // 6. Simpan ID tag untuk sync nanti
                    $tagIds[] = $tag->id;
                }
            }

            // 7. Sync tags dengan artikel
            // sync() akan:
            // - Menghubungkan artikel dengan tags yang ada di $tagIds
            // - Menghapus hubungan dengan tags yang tidak ada di $tagIds
            // - Menggunakan pivot table 'post_tags'
            $post->tags()->sync($tagIds);
        }

        // REDIRECT BERDASARKAN STATUS ARTIKEL
        // Jika artikel dipublish -> redirect ke halaman utama
        // Jika artikel draft -> redirect ke halaman drafts
        $redirectRoute = $post->status === 'published' ? 'home' : 'user.posts.drafts';

        // Redirect dengan pesan sukses
        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil disimpan ' . ($post->status === 'published' ? 'dan dipublikasikan !' : 'sebagai draft !'));
    }

    /**
     * Menampilkan detail artikel
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        // SECURITY CHECK - Proteksi artikel draft
        // Hanya user yang login yang bisa melihat artikel draft
        // Artikel published bisa dilihat semua orang
        if ($post->status !== 'published' && !auth('web')->check()) {
            abort(404); // Return 404 Not Found
        }

        // LOAD RELASI DATA (Eager Loading untuk optimasi performance)
        // Menghindari N+1 query problem dengan memuat semua relasi yang dibutuhkan
        $post->load([
            // Load komentar dengan relasi user dan replies
            'comments' => function ($query) {
                // 1. Load relasi user untuk setiap komentar
                $query->with('user')
                    // 2. Load replies untuk setiap komentar
                    ->with(['replies' => function ($replyQuery) {
                        // 3. Load user untuk setiap reply
                        $replyQuery->with('user')
                            // 4. Hanya load replies yang tidak dihapus (soft delete)
                            ->whereNull('deleted_at');
                    }])
                    // 5. Urutkan komentar terbaru
                    ->latest()
                    // 6. Hanya load komentar yang tidak dihapus
                    ->whereNull('deleted_at');
            },
            // Load tags untuk artikel (untuk display tags)
            'tags'
        ]);

        // Ambil semua kategori untuk navigasi
        $categories = Categorie::all();

        // GET RELATED POSTS (Artikel terkait)
        // Menampilkan 3 artikel terkait untuk meningkatkan engagement
        $relatedPosts = Post::where('id', '!=', $post->id) // Exclude current post
            ->where('status', 'published') // Hanya artikel published
            ->with(['category', 'subCategory']) // Load relasi untuk optimasi
            ->latest('published_at') // Urutkan berdasarkan publish date
            ->take(3) // Batasi 3 artikel
            ->get(); // Execute query

        // Kirim data ke view posts.show.blade.php
        return view('posts.show', compact('post', 'categories', 'relatedPosts'));
    }

    /**
     * Menampilkan form edit artikel
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        // SECURITY CHECK - Hanya pemilik artikel yang bisa edit
        // Laravel Route Model Binding akan otomatis mencari post berdasarkan ID
        // Policy bisa ditambahkan untuk security lebih lanjut

        // Ambil semua kategori untuk dropdown
        $categories = Categorie::all();

        // Ambil subcategories berdasarkan kategori artikel saat ini
        // Jika artikel memiliki kategori, ambil subcategories-nya
        // Jika tidak, return empty collection
        $subcategories = $post->category ? $post->category->subCategories : collect();

        // Load tags untuk pre-fill form tags
        // Ini penting agar tags yang sudah ada ditampilkan di form edit
        $post->load('tags');

        // Kirim data ke view posts.edit.blade.php
        return view('posts.edit', compact('post', 'categories', 'subcategories'));
    }

    /**
     * Mengupdate artikel yang ada
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // SECURITY CHECK - Hanya pemilik artikel yang bisa update
        // Route Model Binding akan otomatis load post berdasarkan ID

        // VALIDASI INPUT ARTIKEL (sama seperti store, tapi image nullable)
        // Perbedaan dengan store: image tidak wajib (nullable)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id', // Bisa kosong saat edit
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Tidak wajib
        ], [
            // Custom error messages dalam bahasa Indonesia
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

        // HANDLE IMAGE UPDATE (Fitur Update Gambar)
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama jika ada
            // Storage::delete() akan menghapus file dari storage
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }

            // 2. Upload gambar baru
            // store() akan otomatis generate nama file unik
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        // UPDATE ARTIKEL DI DATABASE
        // Laravel akan otomatis handle field assignment dan update
        // Slug akan di-update otomatis jika title berubah (via model event)
        $post->update($validated);

        // HANDLE STATUS UPDATE (Fitur Toggle Publish/Draft)
        // Cek apakah user menekan tombol publish/draft
        if ($request->has('publish')) {
            $newStatus = $request->input('publish') == '1' ? 'published' : 'draft';

            // Update status dan published_at
            $post->update([
                'status' => $newStatus,
                'published_at' => $newStatus === 'published' ? now() : null,
            ]);
        }

        // HANDLE TAGS UPDATE (Fitur Update Tags)
        if ($request->has('tags')) {
            // Jika user mengisi tags, proses sama seperti di store()
            // 1. Pisahkan tags berdasarkan koma
            $tagNames = array_filter(explode(',', $request->input('tags')));
            $tagIds = [];

            // 2. Loop setiap tag name
            foreach ($tagNames as $tagName) {
                // 3. Clean tag name
                $tagName = trim($tagName);

                // 4. Jika tidak kosong, cari atau buat tag
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }

            // 5. Sync tags dengan artikel
            // sync() akan update relasi many-to-many
            $post->tags()->sync($tagIds);
        } else {
            // Jika user mengosongkan field tags, hapus semua tags
            // detach() akan menghapus semua relasi tags
            $post->tags()->detach();
        }

        // REDIRECT BERDASARKAN STATUS ARTIKEL
        // Sama seperti store: published -> my-articles, draft -> drafts
        // Gunakan status terbaru setelah update
        $currentStatus = $post->fresh()->status;
        $redirectRoute = $currentStatus === 'published' ? 'user.posts.my-articles' : 'user.posts.drafts';

        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil diperbarui !');
    }

    /**
     * Menghapus artikel (soft delete)
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // SECURITY CHECK - Hanya pemilik artikel yang bisa hapus
        // Bandingkan user_id artikel dengan ID user yang sedang login
        if ($post->user_id !== auth('web')->id()) {
            // Jika bukan pemilik, redirect back dengan error message
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
        }

        // CLEANUP - Hapus gambar terkait jika ada
        // Ini mencegah orphan files di storage
        if ($post->image) {
            // Hapus file dari storage/public/posts/
            Storage::delete('public/' . $post->image);
        }

        // SOFT DELETE ARTIKEL
        // delete() akan soft delete karena trait SoftDeletes di model
        // Artinya:
        // - deleted_at akan diisi dengan timestamp
        // - Artikel tidak akan muncul di query normal
        // - Data masih ada di database (bisa di-restore)
        $post->delete();

        // REDIRECT BERDASARKAN STATUS ARTIKEL
        // Jika artikel published -> redirect ke home
        // Jika artikel draft -> redirect ke drafts
        $redirectRoute = $post->status === 'published' ? 'home' : 'user.posts.drafts';

        return redirect()->route($redirectRoute)
            ->with('success', 'Artikel berhasil dihapus !');
    }

    /**
     * Menampilkan semua artikel yang sudah dipublish oleh user
     * Fitur: "Artikel Saya" di dashboard user
     */
    public function myArticles()
    {
        // Query artikel milik user yang sedang login:
        // 1. Filter berdasarkan user_id (hanya artikel user ini)
        // 2. Filter status = 'published' (hanya artikel yang dipublish)
        // 3. with() eager load relasi untuk optimasi
        // 4. latest() urutkan berdasarkan published_at terbaru
        // 5. paginate(10) batasi 10 artikel per halaman
        $articles = Post::where('user_id', auth('web')->id())
            ->where('status', 'published')
            ->with(['category', 'user', 'tags']) // Load tags juga
            ->latest('published_at')
            ->paginate(10);

        // Kirim data ke view posts.my-articles.blade.php
        return view('posts.my-articles', compact('articles'));
    }

    /**
     * Menampilkan artikel berdasarkan kata kunci
     * Fitur: Pencarian artikel di halaman utama
     */
    public function search(Request $request)
    {
        // AMBIL INPUT SEARCH
        // User input dari form pencarian di halaman utama
        $searchQuery = $request->search_post;
        $categories = Categorie::all();

        // VALIDASI SEARCH QUERY
        // Jika search query tidak kosong, lakukan pencarian
        if ($searchQuery != "") {
            // MULTI-FIELD SEARCH (Pencarian di multiple kolom)
            // Menggunakan LIKE untuk partial match (case-insensitive)
            $posts = Post::where('status', 'published')
                ->where(function ($query) use ($searchQuery) {
                    // 1. Cari di judul artikel
                    // LIKE '%keyword%' = mengandung keyword
                    $query->where('title', 'LIKE', '%' . $searchQuery . '%')
                        // 2. Cari di konten artikel
                        ->orWhere('content', 'LIKE', '%' . $searchQuery . '%')
                        // 3. Cari di nama kategori (via relationship)
                        // orWhereHas() untuk search di relasi
                        ->orWhereHas('category', function ($q) use ($searchQuery) {
                            $q->where('name', 'LIKE', '%' . $searchQuery . '%');
                        })
                        // 4. Cari di nama subkategori (via relationship)
                        ->orWhereHas('subCategory', function ($q) use ($searchQuery) {
                            $q->where('name', 'LIKE', '%' . $searchQuery . '%');
                        });
                })
                // Load relasi untuk optimasi performance
                ->with(['category', 'user', 'subCategory', 'tags'])
                // Urutkan berdasarkan published_at terbaru
                ->latest('published_at')
                // Batasi 6 artikel per halaman
                ->paginate(6);
        } else {
            // Jika search kosong, tampilkan semua artikel published
            // Sama seperti method index()
            $posts = Post::where('status', 'published')
                ->with(['category', 'user', 'subCategory', 'tags'])
                ->latest('published_at')
                ->paginate(6);
        }

        // Kirim data ke view home.blade.php dengan search results
        return view('home', compact('posts', 'categories'));
    }

    /**
     * Menampilkan semua artikel draft milik user yang sedang login
     * Fitur: "Draft Saya" di dashboard user
     */
    public function drafts()
    {
        // Query artikel draft milik user yang sedang login:
        // 1. Filter berdasarkan user_id (hanya artikel user ini)
        // 2. Filter status = 'draft' (hanya artikel yang belum dipublish)
        // 3. with() eager load relasi untuk optimasi
        // 4. latest() urutkan berdasarkan created_at terbaru (draft tidak punya published_at)
        // 5. paginate(10) batasi 10 artikel per halaman
        $drafts = Post::where('user_id', auth('web')->id())
            ->where('status', 'draft')
            ->with(['category', 'subCategory', 'tags'])
            ->latest('created_at')
            ->paginate(10);

        // Kirim data ke view posts.drafts.blade.php
        return view('posts.drafts', compact('drafts'));
    }

    /**
     * Memublikasikan artikel draft
     * Fitur: Ubah status artikel dari draft menjadi published
     */
    public function publish(Post $post)
    {
        // SECURITY CHECK - Hanya pemilik artikel yang bisa publish
        if ($post->user_id !== auth('web')->id()) {
            abort(403, 'Anda tidak memiliki izin untuk memublikasikan artikel ini.');
        }

        // Update status dan tanggal publish
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Redirect ke halaman my-articles dengan pesan sukses
        return redirect()->route('user.posts.my-articles')
            ->with('success', 'Artikel berhasil dipublikasikan !');
    }
}
