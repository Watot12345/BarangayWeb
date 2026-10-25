<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

    public function test_admin_can_view_admin_console_on_dashboard()
    {
        $admin = User::create([
            'barangay_id' => 'ADMIN-001',
            'name' => 'Barangay Captain / Admin',
            'email' => 'admin@sjdm.gov.ph',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Staff Admin Portal');
        $response->assertSee('Clearance Requests');
    }

    public function test_admin_can_update_service_request_status()
    {
        $admin = User::create([
            'barangay_id' => 'ADMIN-001',
            'name' => 'Admin User',
            'email' => 'admin@sjdm.gov.ph',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $resident = User::create([
            'barangay_id' => 'BRGY-001',
            'name' => 'Resident Maria',
            'email' => 'maria@example.com',
            'role' => 'resident',
            'password' => bcrypt('password'),
        ]);

        $req = \App\Models\ServiceRequest::create([
            'user_id' => $resident->id,
            'service_type' => 'Barangay Clearance',
            'purpose' => 'Employment requirement',
            'status' => 'Pending Review',
        ]);

        $response = $this->actingAs($admin)->postJson("/admin/requests/{$req->id}/status", [
            'status' => 'Ready for Pick-up',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertEquals('Ready for Pick-up', $req->fresh()->status);
    }

    public function test_resident_cannot_access_admin_endpoints()
    {
        $resident = User::create([
            'barangay_id' => 'BRGY-001',
            'name' => 'Resident Maria',
            'email' => 'maria@example.com',
            'role' => 'resident',
            'password' => bcrypt('password'),
        ]);

        $req = \App\Models\ServiceRequest::create([
            'user_id' => $resident->id,
            'service_type' => 'Barangay Clearance',
            'purpose' => 'Employment requirement',
            'status' => 'Pending Review',
        ]);

        $response = $this->actingAs($resident)->postJson("/admin/requests/{$req->id}/status", [
            'status' => 'Issued',
        ]);

        $response->assertStatus(403);
    }

    public function test_public_recruiter_can_submit_registration()
    {
        $response = $this->postJson('/recruiters/register', [
            'company_name' => 'Metro Express Logistics',
            'contact_person' => 'David Tan',
            'email' => 'david@metroexpress.ph',
            'phone' => '0917-222-3344',
            'industry' => 'Logistics & Warehousing',
            'description' => 'Delivery rider and warehouse clerk recruitment.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('job_recruiters', [
            'company_name' => 'Metro Express Logistics',
            'status' => 'Pending',
        ]);
    }

    public function test_staff_can_post_job_opening()
    {
        $staff = User::create([
            'barangay_id' => 'STAFF-100',
            'name' => 'PESO Officer Jose',
            'email' => 'jose.staff@sjdm.gov.ph',
            'role' => 'staff',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($staff)->postJson('/admin/jobs/openings', [
            'title' => 'Warehouse Package Sorter',
            'company_name' => 'Apex Logistics SJDM',
            'employment_type' => 'Full-time',
            'salary_range' => '₱18,000 / month',
            'location' => 'San Agustin Industrial Park',
            'vacancies' => 4,
            'description' => 'Sort and organize parcel deliveries for local distribution.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('job_openings', [
            'title' => 'Warehouse Package Sorter',
            'status' => 'Open',
        ]);
    }

    public function test_superadmin_can_manage_staff_registry()
    {
        $admin = User::create([
            'barangay_id' => 'ADMIN-999',
            'name' => 'Head Administrator',
            'email' => 'headadmin@sjdm.gov.ph',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // 1. Create new staff member
        $createResponse = $this->actingAs($admin)->postJson('/admin/users', [
            'name' => 'New Desk Officer',
            'email' => 'desk@sjdm.gov.ph',
            'barangay_id' => 'STAFF-999',
            'role' => 'staff',
            'designation' => 'Public Assistance Desk',
            'password' => 'secret123',
        ]);

        $createResponse->assertStatus(200);
        $createResponse->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'email' => 'desk@sjdm.gov.ph',
            'role' => 'staff',
            'designation' => 'Public Assistance Desk',
        ]);

        $newStaff = User::where('email', 'desk@sjdm.gov.ph')->first();

        // 2. Toggle active status
        $toggleResponse = $this->actingAs($admin)->postJson("/admin/users/{$newStaff->id}/toggle-status");
        $toggleResponse->assertStatus(200);
        $this->assertFalse($newStaff->fresh()->is_active);

        // 3. Reset password
        $resetResponse = $this->actingAs($admin)->postJson("/admin/users/{$newStaff->id}/reset-password", [
            'password' => 'newpassword456',
        ]);
        $resetResponse->assertStatus(200);
        $resetResponse->assertJson(['success' => true]);
    }

    public function test_resident_can_create_post_with_image()
    {
        $user = User::create([
            'barangay_id' => 'RES-101',
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'role' => 'resident',
            'password' => bcrypt('password123'),
        ]);

        $image = UploadedFile::fake()->image('neighborhood_clean.jpg', 800, 600);

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Look at our clean purok after bayanihan!',
            'type' => 'general',
            'media' => $image,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Look at our clean purok after bayanihan!',
            'media_type' => 'image',
        ]);

        $post = Post::where('user_id', $user->id)->first();
        $this->assertNotNull($post->media_url);
    }

    public function test_resident_can_create_post_with_video()
    {
        $user = User::create([
            'barangay_id' => 'RES-102',
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'role' => 'resident',
            'password' => bcrypt('password123'),
        ]);

        $video = UploadedFile::fake()->create('bayanihan_clip.mp4', 3000, 'video/mp4'); // 3MB

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Short clip of the barangay basketball clinic!',
            'type' => 'general',
            'media' => $video,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'media_type' => 'video',
        ]);
    }

    public function test_post_rejects_oversized_video()
    {
        $user = User::create([
            'barangay_id' => 'RES-103',
            'name' => 'Pedro Penduko',
            'email' => 'pedro@example.com',
            'role' => 'resident',
            'password' => bcrypt('password123'),
        ]);

        $oversizedVideo = UploadedFile::fake()->create('huge_movie.mp4', 20000, 'video/mp4'); // 20MB (> 15MB)

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Too large video clip',
            'type' => 'general',
            'media' => $oversizedVideo,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }

    public function test_resident_can_request_clearance()
    {
        $user = User::create([
            'barangay_id' => 'RES-104',
            'name' => 'Elena Adarna',
            'email' => 'elena@example.com',
            'role' => 'resident',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->postJson('/services/request', [
            'service_type' => 'Barangay Clearance',
            'full_name' => 'Elena Adarna',
            'contact_number' => '0917-123-4567',
            'purpose' => 'Employment requirement',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('service_requests', [
            'user_id' => $user->id,
            'service_type' => 'Barangay Clearance',
            'full_name' => 'Elena Adarna',
            'status' => 'Pending',
        ]);
    }
}
