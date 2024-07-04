<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $table = 'admins'; // Adjust the table name accordingly

    protected $fillable = [
        'admin_emp_id','image',
        'company_id', 'employee_name', 'designation', 'skills',
        'experience_years', 'education', 'certifications', 'date_of_birth',
        'address', 'phone_number', 'email',  'company_email','bio', 'linkedin_profile','password',
        'github_profile', 'salary', 'is_active'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function com()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
