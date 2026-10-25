<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AiChatController;

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

// Public Document request submission
Route::post('/services/request', [ServiceController::class, 'requestDocument'])->name('services.request');

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
});
