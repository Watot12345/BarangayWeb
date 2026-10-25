<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Registered Job Recruiters / Partner Companies
        Schema::create('job_recruiters', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('permit_number')->nullable(); // DTI / SEC / Mayor's Permit
            $table->string('industry')->default('General Services');
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 2. Job Openings posted by Staff or verified Recruiters
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_recruiter_id')->nullable()->constrained('job_recruiters')->nullOnDelete();
            $table->foreignId('posted_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('company_name');
            $table->string('employment_type')->default('Full-time'); // Full-time, Part-time, Contractual, Emergency
            $table->string('salary_range')->nullable();
            $table->string('location')->default('San Agustin, SJDM');
            $table->unsignedInteger('vacancies')->default(1);
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('status')->default('Open'); // Open, Filled, Closed
            $table->timestamps();
        });

        // 3. Enhance job_applications to link to job_openings
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('job_opening_id')->nullable()->after('user_id')->constrained('job_openings')->nullOnDelete();
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['job_opening_id']);
            $table->dropColumn(['job_opening_id', 'notes']);
        });
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('job_recruiters');
    }
};
