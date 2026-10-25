<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostLike;
use App\Models\HealthRecord;
use App\Models\JobApplication;
use App\Models\JobRecruiter;
use App\Models\JobOpening;
use App\Models\ScholarshipApplication;
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

        // 2. Barangay Admin & Staff Registry
        $admin = User::updateOrCreate(
            ['email' => 'admin@sjdm.gov.ph'],
            [
                'name' => 'Barangay Captain / Admin',
                'password' => Hash::make('admin123'),
                'barangay_id' => 'ADMIN-0001',
                'phone' => '0918-987-6543',
                'birthdate' => '1985-01-01',
                'gender' => 'Male',
                'civil_status' => 'Married',
                'address' => 'Barangay Hall, San Agustin',
                'role' => 'admin',
                'designation' => 'Barangay Captain & Administrator',
                'is_active' => true,
            ]
        );

        $staffPeso = User::updateOrCreate(
            ['email' => 'peso.officer@sjdm.gov.ph'],
            [
                'name' => 'Jose Ramirez',
                'password' => Hash::make('staff123'),
                'barangay_id' => 'STAFF-0002',
                'phone' => '0917-888-2233',
                'birthdate' => '1988-04-12',
                'gender' => 'Male',
                'civil_status' => 'Single',
                'address' => 'Purok 2, San Agustin',
                'role' => 'staff',
                'designation' => 'PESO Officer & Recruiter Liaison',
                'is_active' => true,
            ]
        );

        $staffHealth = User::updateOrCreate(
            ['email' => 'health.desk@sjdm.gov.ph'],
            [
                'name' => 'Elena Bautista, RN',
                'password' => Hash::make('staff123'),
                'barangay_id' => 'STAFF-0003',
                'phone' => '0922-999-4455',
                'birthdate' => '1992-09-23',
                'gender' => 'Female',
                'civil_status' => 'Single',
                'address' => 'Purok 4, San Agustin',
                'role' => 'staff',
                'designation' => 'Clinic & Health Desk Coordinator',
                'is_active' => true,
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
            'details' => 'Family assistance kit for calamity preparedness, Purok 3.',
            'status' => 'Pending',
        ]);

        ReliefRequest::create([
            'user_id' => $roberto->id,
            'assistance_type' => 'Emergency Shelter',
            'details' => 'Tarpaulin kit request for roof repair due to strong winds, Purok 2.',
            'status' => 'Approved',
        ]);

        // 9. Sample Document Service Requests
        \App\Models\ServiceRequest::create([
            'user_id' => $maria->id,
            'service_type' => 'Barangay Clearance',
            'full_name' => 'Maria R. Santos',
            'contact_number' => '0917-123-4567',
            'purpose' => 'Employment requirement for BPO company',
            'status' => 'Pending',
        ]);

        \App\Models\ServiceRequest::create([
            'user_id' => $john->id,
            'service_type' => 'Certificate of Indigency',
            'full_name' => 'John D.',
            'contact_number' => '0922-111-2233',
            'purpose' => 'Hospital medical assistance discount',
            'status' => 'Ready for Pick-up',
        ]);

        // 10. Sample Job Recruiters
        $recruiter1 = JobRecruiter::firstOrCreate(
            ['email' => 'careers@robinsonssjdm.ph'],
            [
                'company_name' => 'Robinsons Supermarket SJDM',
                'contact_person' => 'Claire Mendoza (HR Manager)',
                'phone' => '0917-555-1200',
                'permit_number' => 'BP-SJDM-2026-0881',
                'industry' => 'Retail & Consumer Goods',
                'address' => 'Quirino Highway, San Jose del Monte, Bulacan',
                'description' => 'Official retail partner of Barangay San Agustin providing priority local employment.',
                'status' => 'Approved',
                'verified_at' => now(),
            ]
        );

        $recruiter2 = JobRecruiter::firstOrCreate(
            ['email' => 'operations@sanagustinsecurity.ph'],
            [
                'company_name' => 'San Agustin Security & Protection Services',
                'contact_person' => 'Col. Eduardo Ramos',
                'phone' => '0928-333-7744',
                'permit_number' => 'SEC-CS2019-4401',
                'industry' => 'Security & Public Safety',
                'address' => 'Purok 3, Barangay San Agustin, SJDM',
                'description' => 'Licensed security agency providing industrial and residential security personnel.',
                'status' => 'Approved',
                'verified_at' => now(),
            ]
        );

        $recruiter3 = JobRecruiter::firstOrCreate(
            ['email' => 'hr@apexsolutionsbulacan.com'],
            [
                'company_name' => 'Apex BPO & Digital Solutions',
                'contact_person' => 'Mark Villafuerte',
                'phone' => '0919-444-9988',
                'permit_number' => 'DTI-04829103',
                'industry' => 'Information Technology & BPO',
                'address' => 'City Center Tower, SJDM',
                'description' => 'Call center and tech support outsourcing center expanding in Bulacan.',
                'status' => 'Pending',
                'verified_at' => null,
            ]
        );

        // 11. Sample Job Openings
        $opening1 = JobOpening::firstOrCreate(
            ['title' => 'Barangay Auxiliary Tanod / Security Guard'],
            [
                'job_recruiter_id' => $recruiter2->id,
                'posted_by_user_id' => $admin->id,
                'company_name' => 'San Agustin Security & Protection Services',
                'employment_type' => 'Full-time',
                'salary_range' => '₱610 / day + Night Differential',
                'location' => 'Barangay San Agustin Compound',
                'vacancies' => 5,
                'description' => 'Patrol designated puroks, assist in night curfew monitoring, and support community safety operations.',
                'requirements' => 'Barangay resident, 21-45 years old, physically fit, high school graduate or equivalent.',
                'status' => 'Open',
            ]
        );

        $opening2 = JobOpening::firstOrCreate(
            ['title' => 'Supermarket Cashier & Stock Clerk'],
            [
                'job_recruiter_id' => $recruiter1->id,
                'posted_by_user_id' => $admin->id,
                'company_name' => 'Robinsons Supermarket SJDM',
                'employment_type' => 'Full-time',
                'salary_range' => '₱16,500 - ₱18,500 / month',
                'location' => 'Robinsons SJDM Branch (Near San Agustin)',
                'vacancies' => 8,
                'description' => 'Handle point-of-sale customer checkout, shelf restocking, customer assistance, and inventory tracking.',
                'requirements' => 'High school / Senior high graduate, customer-friendly, basic math skills.',
                'status' => 'Open',
            ]
        );

        $opening3 = JobOpening::firstOrCreate(
            ['title' => 'Community Health Aide (BHW)'],
            [
                'job_recruiter_id' => null,
                'posted_by_user_id' => $admin->id,
                'company_name' => 'Barangay San Agustin Health Center (PESO Desk)',
                'employment_type' => 'Contractual',
                'salary_range' => '₱14,000 / month + Honorarium',
                'location' => 'Main Health Center, San Agustin',
                'vacancies' => 2,
                'description' => 'Assist barangay doctors and nurses with patient vitals recording, immunization records, and medicine dispatches.',
                'requirements' => 'Resident of Barangay San Agustin, with basic medical/caregiver orientation.',
                'status' => 'Open',
            ]
        );

        // 12. Sample Job Applications
        JobApplication::create([
            'user_id' => $ana->id,
            'job_opening_id' => $opening3->id,
            'job_title' => 'Community Health Aide (BHW)',
            'status' => 'Submitted',
            'notes' => 'Resident of Purok 2 with TESDA Caregiving NCII certification.',
        ]);

        JobApplication::create([
            'user_id' => $john->id,
            'job_opening_id' => $opening1->id,
            'job_title' => 'Barangay Auxiliary Tanod / Security Guard',
            'status' => 'Interview Scheduled',
            'notes' => 'Active volunteer in Purok 4 with security guard license.',
        ]);

        // 13. Sample Scholarship Application
        ScholarshipApplication::create([
            'user_id' => $roberto->id,
            'status' => 'Submitted',
            'notes' => 'School: City College of SJDM | Course: BS Education - 2nd Year | GWA: 1.75',
        ]);
    }
}
