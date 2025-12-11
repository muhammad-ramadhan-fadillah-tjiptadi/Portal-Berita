<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Exports\CommentsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| COMMENT CONTROLLER - MANAJEMEN KOMENTAR ARTIKEL
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait komentar:
| - Menambah komentar ke artikel
| - Membalas komentar (nested comments)
| - Soft delete komentar
| - Validasi dan security
|
*/

class CommentController extends Controller
{
    /**
     * Menambah komentar baru ke artikel
     * Fitur: Comment system untuk interaksi pembaca
     */
    public function store(Request $request, Post $post)
    {
        // VALIDASI KONTEN KOMENTAR
        // Pastikan komentar valid sebelum disimpan
        $request->validate([
            'content' => 'required|string|max:1000',  // Konten wajib, max 1000 karakter
        ], [
            'content.required' => 'Komentar tidak boleh kosong',
            'content.max' => 'Komentar maksimal 1000 karakter',
        ]);

        // BUAT OBJEK KOMENTAR BARU
        // Menggunakan new Comment() untuk explicit object creation
        $comment = new Comment([
            // Konten komentar yang sudah divalidasi
            'content' => $request->content,

            // ID user yang sedang login (penulis komentar)
            // Auth::id() akan return ID user yang authenticated
            'user_id' => Auth::id(),

            // ID artikel yang dikomentari
            // $post->id dari Route Model Binding
            'post_id' => $post->id,
        ]);

        // SIMPAN KOMENTAR KE DATABASE
        $comment->save();

        // REDIRECT KEMBALI KE HALAMAN ARTIKEL
        // back() akan redirect ke halaman sebelumnya (detail artikel)
        return back()->with('success', 'Komentar berhasil ditambahkan !');
    }

    /**
     * Membalas komentar yang ada (nested comment)
     * Fitur: Reply system untuk diskusi bertingkat
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
        ]);

        $reply->save();

        return back()->with('success', 'Balasan berhasil ditambahkan !');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // If already soft-deleted, force delete
        if ($comment->trashed()) {
            // Force delete all replies first
            $comment->replies()->withTrashed()->forceDelete();
            $comment->forceDelete();
        } else {
            // Soft delete all replies
            $comment->replies()->delete();
            $comment->delete();
        }

        // Check if we're coming from the my-comments page
        if (str_contains(url()->previous(), 'my-comments')) {
            return back()->with('success', 'Komentar dan balasannya berhasil dihapus !');
        }

        // Otherwise, redirect back to the post
        return back()->with('success', 'Komentar dan balasannya berhasil dihapus !');
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Store the previous URL in the session for redirecting back after update
        if (!str_contains(url()->previous(), 'comments/' . $comment->id . '/edit')) {
            session(['comment_redirect' => url()->previous()]);
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        // If the comment was soft-deleted, restore it
        if ($comment->trashed()) {
            $comment->restore();
        }

        // Redirect back to the stored URL or to the post
        $redirectUrl = session('comment_redirect', route('posts.show', $comment->post));
        session()->forget('comment_redirect');

        return redirect($redirectUrl)->with('success', 'Komentar berhasil diperbarui !');
    }

    /**
     * Display all comments made by the authenticated user
     */
    public function myComments()
    {
        $comments = Comment::where('user_id', Auth::id())
            ->with(['post' => function ($query) {
                $query->with(['category', 'subCategory']);
            }])
            ->whereNull('deleted_at') // Hanya tampilkan komentar yang belum dihapus
            ->latest()
            ->paginate(10);

        return view('comments.my-comments', compact('comments'));
    }

    /**
     * Display all comments for admin management
     */
    public function index()
    {
        $comments = Comment::with(['post', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Show form for creating new comment (admin)
     */
    public function create()
    {
        $posts = Post::where('status', 'published')->get();
        return view('admin.comments.create', compact('posts'));
    }

    /**
     * Store new comment (admin)
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Comment::create($request->all());

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dibuat!');
    }

    /**
     * Show form for editing comment (admin)
     */
    public function adminEdit($id)
    {
        $comment = Comment::findOrFail($id);
        $posts = Post::where('status', 'published')->get();
        return view('admin.comments.edit', compact('comment', 'posts'));
    }

    /**
     * Update comment (admin)
     */
    public function adminUpdate(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:1000',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment->update($request->all());

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil diperbarui!');
    }

    /**
     * Delete comment (admin)
     */
    public function adminDestroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Show trashed comments for admin
     */
    public function trash()
    {
        $comments = Comment::onlyTrashed()
            ->with(['post', 'user'])
            ->latest('deleted_at')
            ->paginate(20);

        return view('admin.comments.trash', compact('comments'));
    }

    /**
     * Restore deleted comment
     */
    public function restore($id)
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();

        return redirect()->route('admin.comments.trash')
            ->with('success', 'Komentar berhasil dikembalikan!');
    }

    /**
     * Force delete comment permanently
     */
    public function forceDelete($id)
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();

        return redirect()->route('admin.comments.trash')
            ->with('success', 'Komentar berhasil dihapus permanen!');
    }

    /**
     * Export comments to Excel using Yajra
     */
    public function export()
    {
        return Excel::download(new CommentsExport, 'comments_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
