<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ReliefRequest;
use App\Models\JobApplication;
use App\Models\JobRecruiter;
use App\Models\JobOpening;
use App\Models\ScholarshipApplication;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function authorizeAdmin()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized staff action.');
        }
    }

    private function authorizeSuperAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized administrator action. Super admin role required.');
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
            'media' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,gif,mp4,mov,webm', 'max:15360'],
        ]);

        $mediaUrl = null;
        $mediaType = null;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $file = $request->file('media');
            $mime = $file->getMimeType();
            $uploadDir = public_path('uploads/posts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (str_starts_with($mime, 'image/')) {
                $mediaType = 'image';
                $filename = 'img_' . uniqid() . '_' . time() . '.webp';
                $targetPath = $uploadDir . '/' . $filename;
                $this->compressAndSaveImage($file, $targetPath);
                $mediaUrl = asset('uploads/posts/' . $filename);
            } elseif (str_starts_with($mime, 'video/')) {
                $mediaType = 'video';
                $ext = $file->getClientOriginalExtension() ?: 'mp4';
                $filename = 'vid_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $filename);
                $mediaUrl = asset('uploads/posts/' . $filename);
            }
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => '📢 [OFFICIAL BARANGAY ADVISORY] ' . $validated['content'],
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
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
                'media_url' => $post->media_url,
                'media_type' => $post->media_type,
                'time' => 'Just now',
                'likes' => 0,
                'liked' => false,
                'type' => 'announcement',
                'visibility' => 'Public',
                'comments' => [],
            ],
        ]);
    }

    protected function compressAndSaveImage($file, string $targetPath): void
    {
        $mime = $file->getMimeType();
        $source = null;

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $source = @imagecreatefromjpeg($file->getRealPath());
        } elseif ($mime === 'image/png') {
            $source = @imagecreatefrompng($file->getRealPath());
        } elseif ($mime === 'image/webp') {
            $source = @imagecreatefromwebp($file->getRealPath());
        }

        if ($source) {
            $origWidth = imagesx($source);
            $origHeight = imagesy($source);
            $maxDimension = 1280;

            if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
                $ratio = min($maxDimension / $origWidth, $maxDimension / $origHeight);
                $newWidth = (int)($origWidth * $ratio);
                $newHeight = (int)($origHeight * $ratio);
                $canvas = imagecreatetruecolor($newWidth, $newHeight);

                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);

                imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($source);
                $source = $canvas;
            }

            if (function_exists('imagewebp')) {
                imagewebp($source, $targetPath, 80);
            } else {
                imagejpeg($source, $targetPath, 80);
            }
            imagedestroy($source);
        } else {
            $file->move(dirname($targetPath), basename($targetPath));
        }
    }

    public function storeRecruiter(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'company_name' => 'required|string|max:200',
            'contact_person' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:50',
            'permit_number' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:300',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|string|in:Pending,Approved,Rejected',
        ]);

        $recruiter = JobRecruiter::create([
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'permit_number' => $validated['permit_number'] ?? null,
            'industry' => $validated['industry'] ?? 'General Services',
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'verified_at' => $validated['status'] === 'Approved' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Job recruiter '{$recruiter->company_name}' registered successfully!",
            'recruiter' => $recruiter,
        ]);
    }

    public function updateRecruiterStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Pending,Approved,Rejected',
        ]);

        $recruiter = JobRecruiter::findOrFail($id);
        $recruiter->status = $validated['status'];
        if ($validated['status'] === 'Approved' && !$recruiter->verified_at) {
            $recruiter->verified_at = now();
        }
        $recruiter->save();

        return response()->json([
            'success' => true,
            'message' => "Recruiter '{$recruiter->company_name}' updated to {$recruiter->status}",
            'recruiter' => $recruiter,
        ]);
    }

    public function storeJobOpening(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'job_recruiter_id' => 'nullable|integer|exists:job_recruiters,id',
            'company_name' => 'nullable|string|max:200',
            'employment_type' => 'required|string|in:Full-time,Part-time,Contractual,Emergency,Freelance',
            'salary_range' => 'nullable|string|max:150',
            'location' => 'nullable|string|max:200',
            'vacancies' => 'nullable|integer|min:1|max:500',
            'description' => 'required|string|min:10|max:3000',
            'requirements' => 'nullable|string|max:2000',
            'status' => 'nullable|string|in:Open,Filled,Closed',
        ]);

        $companyName = $validated['company_name'] ?? null;
        if (!empty($validated['job_recruiter_id'])) {
            $recruiter = JobRecruiter::find($validated['job_recruiter_id']);
            if ($recruiter && empty($companyName)) {
                $companyName = $recruiter->company_name;
            }
        }
        if (empty($companyName)) {
            $companyName = 'Barangay San Agustin (PESO Desk)';
        }

        $opening = JobOpening::create([
            'job_recruiter_id' => $validated['job_recruiter_id'] ?? null,
            'posted_by_user_id' => Auth::id(),
            'title' => $validated['title'],
            'company_name' => $companyName,
            'employment_type' => $validated['employment_type'],
            'salary_range' => $validated['salary_range'] ?? 'Competitive / Based on Experience',
            'location' => $validated['location'] ?? 'Barangay San Agustin, SJDM',
            'vacancies' => $validated['vacancies'] ?? 1,
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'status' => $validated['status'] ?? 'Open',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Job opening '{$opening->title}' published successfully!",
            'opening' => $opening,
        ]);
    }

    public function updateJobOpeningStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|string|in:Open,Filled,Closed',
        ]);

        $opening = JobOpening::findOrFail($id);
        $opening->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => "Job opening '{$opening->title}' status set to {$opening->status}",
            'opening' => $opening,
        ]);
    }

    public function deleteJobOpening(Request $request, $id)
    {
        $this->authorizeAdmin();

        $opening = JobOpening::findOrFail($id);
        $title = $opening->title;
        $opening->delete();

        return response()->json([
            'success' => true,
            'message' => "Job opening '{$title}' removed successfully",
        ]);
    }

    public function storeStaff(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'barangay_id' => 'required|string|max:50|unique:users,barangay_id',
            'role' => 'required|string|in:staff,admin',
            'designation' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'barangay_id' => $validated['barangay_id'],
            'role' => $validated['role'],
            'designation' => $validated['designation'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Staff member '{$user->name}' ({$user->designation}) registered successfully!",
            'user' => $user,
        ]);
    }

    public function updateUserRole(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'role' => 'required|string|in:resident,staff,admin',
            'designation' => 'nullable|string|max:150',
        ]);

        $user = User::findOrFail($id);

        // Prevent self-demotion if the current super admin
        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot demote your own administrator account.',
            ], 422);
        }

        $user->role = $validated['role'];
        if (isset($validated['designation'])) {
            $user->designation = $validated['designation'];
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "User '{$user->name}' role updated to " . strtoupper($user->role),
            'user' => $user,
        ]);
    }

    public function toggleUserStatus(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.',
            ], 422);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'Active' : 'Deactivated';

        return response()->json([
            'success' => true,
            'message' => "User '{$user->name}' status is now {$statusText}",
            'is_active' => $user->is_active,
        ]);
    }

    public function resetUserPassword(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Password for '{$user->name}' has been successfully reset.",
        ]);
    }
}
