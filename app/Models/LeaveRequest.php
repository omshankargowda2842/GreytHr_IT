<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeDetails;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_applications';
    protected $fillable = [
        'emp_id',
        'category_type',
        'leave_type',
        'from_date',
        'from_session',
        'to_session',
        'to_date',
        'applying_to',
        'cc_to',
        'contact_details','cancel_status',
        'reason',
        'leave_cancel_reason',
        'is_read',
        'file_paths',
        'action_by'
    ];

    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    // Corrected relationship method name
    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id');
    }
    public function calculateLeaveDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {

            try {
                $startDate = Carbon::parse($fromDate);
                $endDate = Carbon::parse($toDate);
    
                // Check if the start or end date is a weekend
                if ($startDate->isWeekend() || $endDate->isWeekend()) {
                    return 'Error: Selected dates fall on a weekend. Please choose weekdays.';
                }

                // Check if the start and end sessions are different on the same day
                if ($startDate->isSameDay($endDate)) {
                    if (self::getSessionNumber($fromSession) !== self::getSessionNumber($toSession)) {
                        return 1;
                    } elseif (self::getSessionNumber($fromSession) == self::getSessionNumber($toSession)) {
                        return 0.5;
                    } else {
                        return 0;
                    }
                }
    
                $totalDays = 0;
    
                while ($startDate->lte($endDate)) {
                    if ($leaveType == 'Sick Leave') {
                        $totalDays += 1;
                    } else {
                        if ($startDate->isWeekday()) {
                            $totalDays += 1;
                        }
                    }
                    // Move to the next day
                    $startDate->addDay();
                }

                // Deduct weekends based on the session numbers
                if ($this->getSessionNumber($fromSession) > 1) {
                    $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
                }
                if ($this->getSessionNumber($toSession) < 2) {
                    $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
                }
                // Adjust for half days
                if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                    // If start and end sessions are the same, check if the session is not 1
                    if ($this->getSessionNumber($fromSession) !== 1) {
                        $totalDays += 0.5; // Add half a day
                    } else {
                        $totalDays += 0.5;
                    }
                } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                    if ($this->getSessionNumber($fromSession) !== 1) {
                        $totalDays += 1; // Add half a day
                    }
                } else {
                    $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
                }
    
                return $totalDays;
            } catch (\Exception $e) {
                return 'Error: ' . $e->getMessage();
            }
    }
    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }
}
