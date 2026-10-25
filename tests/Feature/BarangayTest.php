<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangayTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_register()
    {
        $response = $this->postJson('/register', [
            'barangay_id' => 'BRGY-9999',
            'name' => 'Cardo Dalisay',
            'email' => 'cardo@example.com',
            'phone' => '0917-888-9999',
            'password' => 'secret123',
            'address' => 'Purok 5',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'email' => 'cardo@example.com',
            'barangay_id' => 'BRGY-9999',
        ]);
    }

    public function test_resident_can_login()
    {
        $user = User::create([
            'barangay_id' => 'BRGY-1122',
            'name' => 'Demo Resident',
            'email' => 'resident@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/login', [
            'email' => 'resident@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_resident_can_view_dashboard()
    {
        $user = User::create([
            'barangay_id' => 'BRGY-1122',
            'name' => 'Demo Resident',
            'email' => 'resident@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Demo Resident');
    }

    public function test_resident_can_create_post_and_toggle_like()
    {
        $user = User::create([
            'barangay_id' => 'BRGY-1122',
            'name' => 'Demo Resident',
            'email' => 'resident@example.com',
            'password' => bcrypt('password123'),
        ]);

        $postResponse = $this->actingAs($user)->postJson('/posts', [
            'content' => 'Hello Barangay San Agustin neighbors!',
            'type' => 'general',
            'visibility' => 'Public',
        ]);

        $postResponse->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'content' => 'Hello Barangay San Agustin neighbors!',
            'user_id' => $user->id,
        ]);

        $post = Post::first();

        // Like post
        $likeResponse = $this->actingAs($user)->postJson("/posts/{$post->id}/like");
        $likeResponse->assertStatus(200);
        $likeResponse->assertJson(['liked' => true, 'likes_count' => 1]);
    }

    public function test_ai_chat_responds()
    {
        $response = $this->postJson('/ai/chat', [
            'message' => 'How can I get a barangay clearance?',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['reply', 'source']);
    }

    public function test_ping_and_health_endpoints()
    {
        $ping = $this->get('/ping');
        $ping->assertStatus(200);
        $ping->assertSee('OK');

        $health = $this->getJson('/health');
        $health->assertStatus(200);
        $health->assertJson(['status' => 'ok']);
    }
}
