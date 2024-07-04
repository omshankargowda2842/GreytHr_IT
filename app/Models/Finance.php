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
        'fi_emp_id','image',
        'company_id', 'employee_name', 'date_of_birth','role', 'salary', 'hire_date',
        'department', 'work_location', 'manager_name', 'bank_name',
        'account_number', 'routing_number', 'beneficiary_name', 'beneficiary_relationship',
        'emergency_contact_name', 'emergency_contact_number', 'address','password',
        'phone_number', 'email', 'company_email', 'is_active'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function com()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
