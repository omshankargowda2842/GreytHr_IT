<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyLeaveSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'month',
        'sick_leave_count',
        'casual_leave_count',
        // Add other necessary fields
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id');
    }
}
