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
        'from_date' => 'array',
        'to_date' => 'array',
        'leave_balance' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->leave_type = $model->leave_type ?: [];
            $model->leave_balance = $model->leave_balance ?: [];
            $model->from_date = $model->from_date ?: [];
            $model->to_date = $model->to_date ?: [];
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
        // Retrieve the record for the specific employee
        $balance = self::where('emp_id', $employeeId)
            ->first();
    
        if ($balance) {
            // Decode JSON data if it's a string
            $leaveTypes = is_string($balance->leave_type) ? json_decode($balance->leave_type, true) : $balance->leave_type;
            $leaveBalances = is_string($balance->leave_balance) ? json_decode($balance->leave_balance, true) : $balance->leave_balance;
            $fromDates = is_string($balance->from_date) ? json_decode($balance->from_date, true) : $balance->from_date;
            $toDates = is_string($balance->to_date) ? json_decode($balance->to_date, true) : $balance->to_date;
    
            // Ensure that JSON data is decoded correctly
            if (is_array($leaveTypes) && is_array($leaveBalances) && is_array($fromDates) && is_array($toDates)) {
                // Check if the leave type exists in the array
                if (in_array($leaveType, $leaveTypes)) {
                    // Find the index of the leave type
                    $index = array_search($leaveType, $leaveTypes);
    
                    // Check if the corresponding dates fall within the requested year
                    $fromDate = isset($fromDates[$index]) ? new \DateTime($fromDates[$index]) : null;
                    $toDate = isset($toDates[$index]) ? new \DateTime($toDates[$index]) : null;
    
                    if ($fromDate && $toDate) {
                        $fromYear = $fromDate->format('Y');
                        $toYear = $toDate->format('Y');
    
                        // Check if the leave type is within the requested year
                        if ($fromYear <= $year && $toYear >= $year) {
                            // Return the balance for the specified leave type
                            return isset($leaveBalances[$leaveType]) ? $leaveBalances[$leaveType] : 0;
                        }
                    }
                }
            }
        }
    
        // Return 0 if the leave type is not found or if no record exists
        return 0;
    }


}
