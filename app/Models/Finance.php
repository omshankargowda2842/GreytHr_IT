<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Finance extends Authenticatable
{
    use Notifiable;

    use HasFactory;
    protected $primaryKey = 'fi_emp_id';
    public $incrementing = false;
    protected $table = 'finance';

    protected $fillable = [
        'fi_emp_id','image','emp_id',
        'employee_name', 'date_of_birth',
        'emergency_contact_number','password',
        'phone_number', 'email','is_active'
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
