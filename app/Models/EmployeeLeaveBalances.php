<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveBalances extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'emp_id',
        'leave_type',
        'from_date',
        'to_date',
        'status',
        'leave_balance',
    ];

    // Cast attributes to JSON
    protected $casts = [
        'leave_type' => 'array',
        'leave_balance' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->leave_type = $model->leave_type ?: [];
            $model->leave_balance = $model->leave_balance ?: [];
        });
    }
    /**
     * Get the employee associated with the leave balance.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    /**
     * Get the leave balance for a given year, leave type, and employee.
     *
     * @param string $employeeId
     * @param string $leaveType
     * @param int $year
     * @return int
     */
    public static function getLeaveBalancePerYear($employeeId, $leaveType, $year)
    {
        
        // Retrieve the record for the specific employee and year
        $balance = self::where('emp_id', $employeeId)
            ->whereYear('from_date', '<=', $year) // Ensure the year is within the range
            ->whereYear('to_date', '>=', $year)
            ->first();


        if ($balance) {
            // Decode the JSON leave_balance column
            $leaveBalances = is_string($balance->leave_balance) ? json_decode($balance->leave_balance, true) : $balance->leave_balance;

            if (is_array($leaveBalances) && isset($leaveBalances[$leaveType])) {
                // Return the balance for the specified leave type
                return $leaveBalances[$leaveType];
            }
        }

        // Return 0 if the leave type is not found or if no record exists
        return 0;
    }



}
