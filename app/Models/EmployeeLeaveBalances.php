<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveBalances extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'leave_type',
        'from_date',
        'to_date',
        'status',
        'leave_balance',
];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public static function getLeaveBalancePerYear($employeeId, $leaveType, $year)
    {
        $balance = self::where('emp_id', $employeeId)
        ->where('leave_type', $leaveType)
        ->whereYear('from_date', $year)
        ->first();

      return $balance ? $balance->leave_balance : 0;
    }
}
