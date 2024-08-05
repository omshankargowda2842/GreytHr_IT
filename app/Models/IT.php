<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class IT extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $table = 'i_t'; // Adjust the table name accordingly

    protected $fillable = [
        'it_emp_id','image','emp_id',
         'employee_name',
        'date_of_birth',
        'phone_number', 'email','password','is_active'
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
