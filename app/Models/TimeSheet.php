<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class TimeSheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'start_date',
        'end_date',
        'time_sheet_type',
        'date_and_day_with_tasks',
        'submission_status',
        'approval_status_for_manager',
        'approval_status_for_hr',
        'reject_reason_for_manager',
        'resubmit_reason_for_manager',
        'reject_reason_for_hr',
        'resubmit_reason_for_hr',
    ];

    protected $casts = [
        'date_and_day_with_tasks' => 'array',
    ];
 
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}