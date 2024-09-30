<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDepartmentHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'dept_id',
        'sub_dept_id',
        'start_date',
        'end_date',
        'manager',
        'dept_head',
        'job_role',
    ];

}
