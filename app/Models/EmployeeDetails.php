<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\LeaveRequest;
use App\Models\SwipeRecord;
use App\Models\Chating;

class EmployeeDetails extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasFactory;
    protected $primaryKey = 'emp_id';
    public $incrementing = false;
    protected $fillable = [
        'emp_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'email',
        'company_name',
        'company_email',
        'mobile_number',
        'alternate_mobile_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'hire_date',
        'employee_type',
        'department',
        'job_title',
        'manager_id',
        'report_to',
        'employee_status',
        'emergency_contact',
        'password',
        'image',
        'blood_group',
        'nationality',
        'religion',
        'marital_status',
        'spouse',
        'physically_challenge',
        'inter_emp',
        'job_location',
        'education',
        'experience',
        'pan_no',
        'adhar_no',
        'pf_no',
        'nick_name',
        'time_zone',
        'biography',
        'facebook',
        'twitter',
        'linked_in',
        'company_id',
        'is_starred',
        'status',
        'skill_set'
    ];
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'emp_id');
    }
    public function leaveApplies()
    {
        return $this->hasMany(LeaveRequest::class, 'emp_id', 'emp_id');
    }
    public function swipeRecords()
    {
        return $this->hasMany(SwipeRecord::class, 'emp_id', 'emp_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'emp_id', 'emp_id');
    }

    // Inside the EmployeeDetails model
    public function starredPeople()
    {
        return $this->hasMany(StarredPeople::class, 'emp_id', 'emp_id');
    }

    public function conversations()
    {
       
        return $this->hasMany(Chating::class,'sender_id')->orWhere('receiver_id',$this->emp_id)->whereNotDeleted();
       
    }
}
