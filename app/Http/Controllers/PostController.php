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
            'content' => ['nullable', 'string', 'max:3000'],
            'type' => ['nullable', 'string'],
            'visibility' => ['nullable', 'string', 'max:50'],
            'media' => ['nullable', 'file', 'max:15360'],
        ]);

        // Require at least content or an attached media file
        if (empty(trim((string)$request->input('content'))) && !$request->hasFile('media')) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a message or attach a photo/video.',
            ], 422);
        }

        $mediaUrl = null;
        $mediaType = null;

        if ($request->hasFile('media') && $request->file('media')->isValid()) {
            $file = $request->file('media');
            $mime = strtolower((string)($file->getMimeType() ?: ''));
            $ext = strtolower((string)($file->getClientOriginalExtension() ?: ''));

            $imageExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'heic', 'heif', 'svg'];
            $videoExts = ['mp4', 'mov', 'webm', 'ogg', 'mkv', '3gp', 'avi', 'wmv'];

            $isImage = str_starts_with($mime, 'image/') || in_array($ext, $imageExts);
            $isVideo = str_starts_with($mime, 'video/') || in_array($ext, $videoExts);

            if (!$isImage && !$isVideo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file format. Please upload an image (JPG, PNG, WebP) or video (MP4, MOV, WebM).',
                ], 422);
            }

            $uploadDir = public_path('uploads/posts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if ($isImage) {
                $mediaType = 'image';
                $filename = 'img_' . uniqid() . '_' . time() . '.webp';
                $targetPath = $uploadDir . '/' . $filename;
                $this->compressAndSaveImage($file, $targetPath);
                $mediaUrl = asset('uploads/posts/' . $filename);
            } else {
                $mediaType = 'video';
                $safeExt = in_array($ext, $videoExts) ? $ext : 'mp4';
                $filename = 'vid_' . uniqid() . '_' . time() . '.' . $safeExt;
                $file->move($uploadDir, $filename);
                $mediaUrl = asset('uploads/posts/' . $filename);
            }
        }

        $content = trim((string)($validated['content'] ?? ''));
        if (empty($content)) {
            $content = $mediaType === 'video' ? 'Shared a community video.' : 'Shared a community photo.';
        }

        $postType = in_array($validated['type'] ?? '', ['general', 'question', 'announcement', 'feedback'])
            ? $validated['type']
            : 'general';

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $content,
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
            'type' => $postType,
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
