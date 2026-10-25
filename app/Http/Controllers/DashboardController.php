<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\ReliefRequest;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\JobRecruiter;
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
                'media_url' => $post->media_url,
                'media_type' => $post->media_type,
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

        // Active job openings and accredited recruiters for resident view
        $openJobs = JobOpening::with('recruiter')->where('status', 'Open')->latest()->get();
        $approvedRecruiters = JobRecruiter::where('status', 'Approved')->latest()->get();

        // Gather Admin & Staff Management Data if user has 'admin' or 'staff' role
        $adminData = null;
        if (in_array($user->role, ['admin', 'staff'])) {
            $adminData = [
                'stats' => [
                    'total_residents' => User::where('role', 'resident')->count(),
                    'total_staff' => User::whereIn('role', ['admin', 'staff'])->count(),
                    'pending_documents' => ServiceRequest::where('status', 'Pending')->count(),
                    'pending_relief' => ReliefRequest::where('status', 'Pending')->count(),
                    'total_jobs' => JobApplication::count(),
                    'total_openings' => JobOpening::where('status', 'Open')->count(),
                    'total_recruiters' => JobRecruiter::where('status', 'Approved')->count(),
                    'pending_recruiters' => JobRecruiter::where('status', 'Pending')->count(),
                    'total_scholarships' => ScholarshipApplication::count(),
                ],
                'staff_members' => User::whereIn('role', ['admin', 'staff'])->latest()->get(),
                'service_requests' => ServiceRequest::with('user')->latest()->get(),
                'relief_requests' => ReliefRequest::with('user')->latest()->get(),
                'job_applications' => JobApplication::with(['user', 'jobOpening'])->latest()->get(),
                'job_openings' => JobOpening::with(['recruiter', 'applications'])->latest()->get(),
                'job_recruiters' => JobRecruiter::withCount('jobOpenings')->latest()->get(),
                'approved_recruiters' => $approvedRecruiters,
                'scholarship_applications' => ScholarshipApplication::with('user')->latest()->get(),
                'residents' => User::where('role', 'resident')->latest()->take(100)->get(),
            ];
        }

        if ($request->wantsJson()) {
            return response()->json([
                'posts' => $posts,
                'userStats' => $userStats,
                'openJobs' => $openJobs,
                'approvedRecruiters' => $approvedRecruiters,
                'adminData' => $adminData,
            ]);
        }

        return view('dashboard', compact('user', 'posts', 'userStats', 'tab', 'openJobs', 'approvedRecruiters', 'adminData'));
    }
}
