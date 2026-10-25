<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ReliefRequest;
use App\Models\JobApplication;
use App\Models\ScholarshipApplication;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized staff action.');
        }
    }

    public function updateServiceRequestStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Processing,Ready for Pick-up,Issued,Rejected',
        ]);

        $item = ServiceRequest::findOrFail($id);
        $item->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Document request status updated to {$item->status}",
            'item' => $item,
        ]);
    }

    public function updateReliefRequestStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Dispatched,Delivered,Completed',
        ]);

        $item = ReliefRequest::findOrFail($id);
        $item->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Relief request status updated to {$item->status}",
            'item' => $item,
        ]);
    }

    public function updateJobApplicationStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Submitted,Under Review,Interview Scheduled,Approved,Declined',
        ]);

        $item = JobApplication::findOrFail($id);
        $item->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Job application updated to {$item->status}",
            'item' => $item,
        ]);
    }

    public function updateScholarshipStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Submitted,Verified,Approved,Granted,Declined',
        ]);

        $item = ScholarshipApplication::findOrFail($id);
        $item->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Scholarship status updated to {$item->status}",
            'item' => $item,
        ]);
    }

    public function broadcastAnnouncement(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'content' => 'required|string|min:5|max:1500',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => '📢 [OFFICIAL BARANGAY ADVISORY] ' . $validated['content'],
            'type' => 'announcement',
            'visibility' => 'Public',
            'likes_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Official announcement broadcasted to community feed!',
            'post' => [
                'id' => $post->id,
                'author' => Auth::user()->name,
                'avatar' => Auth::user()->initials,
                'content' => $post->content,
                'time' => 'Just now',
                'likes' => 0,
                'liked' => false,
                'type' => 'announcement',
                'visibility' => 'Public',
                'comments' => [],
            ],
        ]);
    }
}
