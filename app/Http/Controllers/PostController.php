<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:3000'],
            'type' => ['nullable', 'string', 'in:general,question,announcement,feedback'],
            'visibility' => ['nullable', 'string', 'max:50'],
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'type' => $validated['type'] ?? 'general',
            'visibility' => $validated['visibility'] ?? 'Public',
            'likes_count' => 0,
        ]);

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Post published successfully!',
            'post' => [
                'id' => $post->id,
                'author' => $user->name,
                'avatar' => $user->initials,
                'content' => $post->content,
                'time' => 'Just now',
                'likes' => 0,
                'liked' => false,
                'type' => $post->type,
                'visibility' => $post->visibility,
                'comments' => [],
            ],
        ]);
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        $existing = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        $likesCount = PostLike::where('post_id', $post->id)->count();
        $post->update(['likes_count' => $likesCount]);

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function addComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'author' => $user->name,
                'avatar' => $user->initials,
                'text' => $comment->content,
                'time' => 'Just now',
            ],
            'total_comments' => $post->comments()->count(),
        ]);
    }
}
