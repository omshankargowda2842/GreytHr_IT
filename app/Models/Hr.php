<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hr extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $primaryKey = 'hr_emp_id';
    public $incrementing = false;
    protected $table = 'hr_employees';

    protected $fillable = [
        'hr_emp_id','emp_id',
        'password','email','image','employee_name','date_of_birth','emergency_contact_number',
        'phone_number'
    ];

    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'hr_emp_id', 'emp_id');
    }

}
