<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tab = $request->query('tab', 'recent');

        $query = Post::with(['user', 'comments.user', 'likes']);

        if ($tab === 'popular') {
            $query->orderByDesc('likes_count')->latest();
        } elseif ($tab === 'questions') {
            $query->where('type', 'question')->latest();
        } else {
            $query->latest();
        }

        $posts = $query->get()->map(function ($post) use ($user) {
            return [
                'id' => $post->id,
                'author' => $post->user->name,
                'avatar' => $post->user->initials,
                'content' => $post->content,
                'time' => $post->created_at->diffForHumans(),
                'likes' => $post->likes_count,
                'liked' => $post->isLikedBy($user),
                'type' => $post->type,
                'visibility' => $post->visibility,
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'author' => $comment->user->name,
                        'avatar' => $comment->user->initials,
                        'text' => $comment->content,
                        'time' => $comment->created_at->diffForHumans(),
                    ];
                }),
            ];
        });

        $userStats = [
            'posts_count' => $user->posts()->count(),
            'relief_count' => $user->reliefRequests()->count(),
            'health_count' => $user->healthRecords()->count(),
            'online_count' => 156,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'posts' => $posts,
                'userStats' => $userStats,
            ]);
        }

        return view('dashboard', compact('user', 'posts', 'userStats', 'tab'));
    }
}
