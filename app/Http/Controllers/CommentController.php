<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        $comment->save();

        return back()->with('success', 'Komentar berhasil ditambahkan !');
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
            $comment->forceDelete();
        } else {
            $comment->delete();
        }

        // Check if we're coming from the my-comments page
        if (str_contains(url()->previous(), 'my-comments')) {
            return back()->with('success', 'Komentar berhasil dihapus !');
        }

        // Otherwise, redirect back to the post
        return back()->with('success', 'Komentar berhasil dihapus !');
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
            ->latest()
            ->paginate(10);

        return view('comments.my-comments', compact('comments'));
    }
}
