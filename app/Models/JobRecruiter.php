<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobRecruiter extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'permit_number',
        'industry',
        'address',
        'description',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class);
    }
}
