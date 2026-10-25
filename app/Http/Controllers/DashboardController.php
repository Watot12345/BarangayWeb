<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\ReliefRequest;
use App\Models\JobApplication;
use App\Models\ScholarshipApplication;
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

        // Gather Admin & Staff Management Data if user has 'admin' role
        $adminData = null;
        if ($user->role === 'admin') {
            $adminData = [
                'stats' => [
                    'total_residents' => User::where('role', 'resident')->count(),
                    'pending_documents' => ServiceRequest::where('status', 'Pending')->count(),
                    'pending_relief' => ReliefRequest::where('status', 'Pending')->count(),
                    'total_jobs' => JobApplication::count(),
                    'total_scholarships' => ScholarshipApplication::count(),
                ],
                'service_requests' => ServiceRequest::with('user')->latest()->get(),
                'relief_requests' => ReliefRequest::with('user')->latest()->get(),
                'job_applications' => JobApplication::with('user')->latest()->get(),
                'scholarship_applications' => ScholarshipApplication::with('user')->latest()->get(),
                'residents' => User::where('role', 'resident')->latest()->take(50)->get(),
            ];
        }

        if ($request->wantsJson()) {
            return response()->json([
                'posts' => $posts,
                'userStats' => $userStats,
                'adminData' => $adminData,
            ]);
        }

        return view('dashboard', compact('user', 'posts', 'userStats', 'tab', 'adminData'));
    }
}
