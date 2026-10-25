<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOpening extends Model
{
    protected $fillable = [
        'job_recruiter_id',
        'posted_by_user_id',
        'title',
        'company_name',
        'employment_type',
        'salary_range',
        'location',
        'vacancies',
        'description',
        'requirements',
        'status',
    ];

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(JobRecruiter::class, 'job_recruiter_id');
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
