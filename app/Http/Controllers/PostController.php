<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user'])
            ->withCount('allComments')
            ->latest()
            ->paginate(15);

        return view('community.index', compact('posts'));
    }

    public function gameIndex(string $slug)
    {
        $posts = Post::where('game_slug', $slug)
            ->with(['user', 'comments'])
            ->withCount('allComments')
            ->latest()
            ->paginate(10);

        return view('community.game', compact('posts', 'slug'));
    }

    public function storePost(Request $request, string $slug)
    {
        $request->validate([
            'title'      => 'required|string|max:150',
            'body'       => 'required|string|max:5000',
            'game_name'  => 'required|string',
            'game_image' => 'nullable|string',
        ]);

        Post::create([
            'user_id'    => Auth::id(),
            'game_slug'  => $slug,
            'game_name'  => $request->game_name,
            'game_image' => $request->game_image,
            'title'      => $request->title,
            'body'       => $request->body,
        ]);

        return back()->with('success', 'Diskusi berhasil diposting!');
    }

    public function storeComment(Request $request, int $post_id)
    {
        $request->validate([
            'body'      => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post = Post::findOrFail($post_id);

        Comment::create([
            'user_id'   => Auth::id(),
            'post_id'   => $post->id,
            'parent_id' => $request->parent_id ?? null,
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroyPost(int $id)
    {
        $post = Post::findOrFail($id);
        abort_unless(Auth::id() === $post->user_id, 403);
        $post->delete();

        return back()->with('success', 'Diskusi berhasil dihapus.');
    }

    public function destroyComment(int $id)
    {
        $comment = Comment::findOrFail($id);
        abort_unless(Auth::id() === $comment->user_id, 403);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
