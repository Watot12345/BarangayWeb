<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_residents' => User::where('role', 'resident')->count(),
            'total_posts' => Post::count(),
            'service_requests_count' => 1240,
            'hotlines_active' => 24,
        ];

        return view('home', compact('stats'));
    }
}
