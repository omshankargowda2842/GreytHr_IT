<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'user_id'; 
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'user_type',
        'company_id',
        'company_name',
        'company_logo',
        'image',
        'city',
        'state',
        'country',
        'experience_status',
        'available_to_join',
        'profile_summary',
        'technical_skills',
        'education',
        'current_industry',
        'role_category',
        'desired_job_type',
        'preferred_shift',
        'expected_salary',
        'department',
        'job_role',
        'desired_employment_type',
        'preferred_work_location',
        'date_of_birth',
        'religion',
        'differently_abled',
        'career_break',
        'languages',
        'full_name',
        'email',
        'password',
        'mobile_no',
        'work_status',
        'address',
        'resume'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'technical_skills' => 'array',
        'education' => 'array',
        'languages' => 'array',
    ];
    
    
}
