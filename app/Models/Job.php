<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $primaryKey = 'job_id'; 
    public $incrementing = false;
    protected $fillable = [
        'job_id',
        'title',
        'description',
        'location',
        'salary',
        'company_id',
        'company_name',
        'hr_name',
        'expire_date',
        'contact_email',
        'contact_phone',
        'vacancies',
        'education_requirement',
        'experience_requirement',
        'skills_required',
        'is_featured',
        'is_active',
        'application_link',
        'job_type',
        'responsibilities',
        'benefits',
        'application_instructions',
    ];
    public function appliedJob()
    {
        return $this->hasOne(AppliedJob::class, 'job_id', 'job_id');
    }
    public function com()
    {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }
}
