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
            'content' => $validated['content'],
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
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
                'media_url' => $post->media_url,
                'media_type' => $post->media_type,
                'time' => 'Just now',
                'likes' => 0,
                'liked' => false,
                'type' => $post->type,
                'visibility' => $post->visibility,
                'comments' => [],
            ],
        ]);
    }

    /**
     * Compress and downscale uploaded image to save bandwidth and storage.
     */
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

                // Preserve alpha for transparent images
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
