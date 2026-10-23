<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostLike;
use App\Models\HealthRecord;
use App\Models\JobApplication;
use App\Models\ReliefRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Primary Resident (Maria R. Santos)
        $maria = User::updateOrCreate(
            ['email' => 'maria@example.com'],
            [
                'name' => 'Maria R. Santos',
                'password' => Hash::make('password'),
                'barangay_id' => 'BRGY-8421',
                'phone' => '0917-123-4567',
                'birthdate' => '1990-05-14',
                'gender' => 'Female',
                'civil_status' => 'Married',
                'address' => '123 San Agustin St., Purok 3',
                'role' => 'resident',
            ]
        );

        // 2. Barangay Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@sjdm.gov.ph'],
            [
                'name' => 'Barangay Admin',
                'password' => Hash::make('admin123'),
                'barangay_id' => 'BRGY-0001',
                'phone' => '0918-987-6543',
                'birthdate' => '1985-01-01',
                'gender' => 'Other',
                'civil_status' => 'Single',
                'address' => 'Barangay Hall, San Agustin',
                'role' => 'admin',
            ]
        );

        // 3. Neighbors
        $john = User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John D.',
                'password' => Hash::make('password'),
                'barangay_id' => 'BRGY-3312',
                'phone' => '0922-111-2233',
                'address' => 'Purok 1, San Agustin',
                'role' => 'resident',
            ]
        );

        $ana = User::updateOrCreate(
            ['email' => 'ana@example.com'],
            [
                'name' => 'Ana C.',
                'password' => Hash::make('password'),
                'barangay_id' => 'BRGY-7741',
                'phone' => '0933-444-5566',
                'address' => 'Purok 4, San Agustin',
                'role' => 'resident',
            ]
        );

        $roberto = User::updateOrCreate(
            ['email' => 'roberto@example.com'],
            [
                'name' => 'Roberto M.',
                'password' => Hash::make('password'),
                'barangay_id' => 'BRGY-9014',
                'phone' => '0944-777-8899',
                'address' => 'Purok 2, San Agustin',
                'role' => 'resident',
            ]
        );

        // 4. Sample Posts
        $post1 = Post::create([
            'user_id' => $maria->id,
            'content' => 'Good morning neighbors! Does anyone know where we can get the latest vaccination schedule for seniors? My mother needs her booster shot. Thank you! 🙏',
            'type' => 'question',
            'visibility' => 'Public',
            'likes_count' => 12,
        ]);

        $post2 = Post::create([
            'user_id' => $admin->id,
            'content' => '📢 ANNOUNCEMENT: Free medical mission this Saturday, March 22 at the Barangay Hall. Free check-ups, dental, and medicine distribution. Please bring your Barangay ID!',
            'type' => 'announcement',
            'visibility' => 'Public',
            'likes_count' => 45,
        ]);

        $post3 = Post::create([
            'user_id' => $roberto->id,
            'content' => "Feedback: The new streetlights on San Agustin St. are great! But can we also add one near the basketball court? It's very dark at night. Thanks to the barangay for listening!",
            'type' => 'feedback',
            'visibility' => 'Public',
            'likes_count' => 28,
        ]);

        // 5. Comments
        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $john->id,
            'content' => 'Try the Barangay Health Center, Monday to Friday 9am-12pm.',
        ]);

        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $ana->id,
            'content' => 'I saw a post earlier — this Friday at the covered court!',
        ]);

        Comment::create([
            'post_id' => $post2->id,
            'user_id' => $roberto->id,
            'content' => 'Thank you po!',
        ]);

        // 6. Post likes
        PostLike::create(['post_id' => $post1->id, 'user_id' => $john->id]);
        PostLike::create(['post_id' => $post1->id, 'user_id' => $ana->id]);
        PostLike::create(['post_id' => $post2->id, 'user_id' => $maria->id]);

        // 7. Sample Health Record for Maria
        HealthRecord::create([
            'user_id' => $maria->id,
            'blood_pressure' => '120/80',
            'notes' => 'Routine monthly health check-up. Feeling healthy.',
        ]);

        // 8. Sample Relief Request
        ReliefRequest::create([
            'user_id' => $maria->id,
            'assistance_type' => 'Food Pack',
            'details' => 'Family assistance kit for calamity preparedness.',
            'status' => 'Approved',
        ]);
    }
}
