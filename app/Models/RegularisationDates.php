<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularisationDates extends Model
{
    use HasFactory;
    protected $fillable = ['emp_id','regularisation_entries','status','approved_date','rejected_date','approved_by','rejected_by','is_withdraw','withdraw_date','regularisation_date','applied_date','applied_day','employee_remarks','approver_remarks'];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
