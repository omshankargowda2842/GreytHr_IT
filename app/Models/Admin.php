<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $primaryKey = 'ad_emp_id';
    public $incrementing = false;
    protected $table = 'admin_employees';

    protected $fillable = [
        'ad_emp_id',
        'emp_id',
        'password',
        'email',
        'image',
        'employee_name',
        'date_of_birth',
        'emergency_contact_number',
        'phone_number'
    ];

    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function com()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
