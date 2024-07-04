<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliedJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'job_id',
        'job_title',
        'company_name',
        'application_status',
        'resume',
        'applied_to',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function job()
    {
        $this->data = json_decode($this->jsonData, true);

        return $this->belongsTo(Job::class, 'job_id', 'job_id');
    }
    public function com()
    {

        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
