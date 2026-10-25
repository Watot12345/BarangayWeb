<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AdminController;

// Lightweight keep-alive / health-check endpoints for uptime monitors (cron-job.org)
Route::get('/ping', function () {
    return response('OK', 200)->header('Content-Type', 'text/plain');
});
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// AI Assistant chat endpoint
Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

// Public Document & Recruiter registration submissions
Route::post('/services/request', [ServiceController::class, 'requestDocument'])->name('services.request');
Route::post('/recruiters/register', [ServiceController::class, 'registerRecruiter'])->name('recruiters.register');

// Authenticated resident portal
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Community feed interactions
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment'])->name('posts.comment');
    
    // Resident services
    Route::post('/services/relief', [ServiceController::class, 'storeRelief'])->name('services.relief');
    Route::post('/services/health', [ServiceController::class, 'storeHealth'])->name('services.health');
    Route::post('/services/job', [ServiceController::class, 'applyJob'])->name('services.job');
    Route::post('/services/scholarship', [ServiceController::class, 'applyScholarship'])->name('services.scholarship');

    // Admin & Staff operations
    Route::post('/admin/requests/{id}/status', [AdminController::class, 'updateServiceRequestStatus'])->name('admin.requests.status');
    Route::post('/admin/relief/{id}/status', [AdminController::class, 'updateReliefRequestStatus'])->name('admin.relief.status');
    Route::post('/admin/jobs/{id}/status', [AdminController::class, 'updateJobApplicationStatus'])->name('admin.jobs.status');
    Route::post('/admin/scholarships/{id}/status', [AdminController::class, 'updateScholarshipStatus'])->name('admin.scholarships.status');
    Route::post('/admin/broadcast', [AdminController::class, 'broadcastAnnouncement'])->name('admin.broadcast');

    // Admin Job Recruiters & Job Openings Management
    Route::post('/admin/recruiters', [AdminController::class, 'storeRecruiter'])->name('admin.recruiters.store');
    Route::post('/admin/recruiters/{id}/status', [AdminController::class, 'updateRecruiterStatus'])->name('admin.recruiters.status');
    Route::post('/admin/jobs/openings', [AdminController::class, 'storeJobOpening'])->name('admin.jobs.openings.store');
    Route::post('/admin/jobs/openings/{id}/status', [AdminController::class, 'updateJobOpeningStatus'])->name('admin.jobs.openings.status');
    Route::delete('/admin/jobs/openings/{id}', [AdminController::class, 'deleteJobOpening'])->name('admin.jobs.openings.delete');

    // Staff Registry & User Management
    Route::post('/admin/users', [AdminController::class, 'storeStaff'])->name('admin.users.store');
    Route::post('/admin/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
    Route::post('/admin/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.resetPassword');
});
