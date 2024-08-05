<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SalaryRevision extends Model
{
    use HasFactory;
    // protected $table = 'salary_revisions'; // Set the table name if it's not the default "salary_revisions"

    // protected $primaryKey = 'emp_id'; // Set the primary key if it's not "id"

    // public $incrementing = false; // Set to false to indicate that "emp_id" is not auto-incrementing

    // protected $keyType = 'string'; // Set the data type for the primary key

    protected $fillable = [
        'emp_id',
        'company_id',
        'salary',
        'salary_month',
        'last_revision_period',
        'present_revision_period',
        'previous_monthly_ctc',
        'revised_monthly_ctc',
    ];

    protected $appends = ['basic', 'hra', 'medical', 'special', 'conveyance', 'pf'];

    public function getBasicAttribute()
    {
        return $this->calculateBasic();
    }

    public function getHraAttribute()
    {
        return $this->calculateHRA();
    }

    public function getMedicalAttribute()
    {
        return $this->calculateMedical();
    }

    public function getSpecialAttribute()
    {
        return $this->calculateSpecial();
    }

    public function getConveyanceAttribute()
    {
        return 1600; // Fixed amount for conveyance
    }

    public function calculateBasic()
    {
        return ($this->salary * 0.4); // 40% of the salary as Basic
    }

    public function calculateHRA()
    {
        return ($this->basic * 0.4); // 40% of basic as HRA
    }

    public function calculateMedical()
    {
        return 1250; // Fixed amount for Medical Allowance
    }

    public function calculateSpecial()
    {
        $remainingSalary = $this->salary - ($this->basic + $this->hra + $this->conveyance + $this->medical + $this->calculatePf());
        return max($remainingSalary, 0); // Fixed amount for Special Allowance
    }

    public function calculatePf()
    {
        $basic = $this->calculateBasic();
        if ($basic) {
            // Calculate PF as 12% of Basic
            return ($basic * 0.12);
        } else {
            // If Basic is not set, PF is 0
            return 0;
        }
    }
    public function calculateEsi()
    {
        $basic = $this->calculateBasic();
        if ($basic) {
            // Calculate PF as 12% of Basic
            return ($basic * 0.0075);
        } else {
            // If Basic is not set, PF is 0
            return 0;
        }
    }

    public function calculateProfTax()
    {
        return 150; // Fixed amount for Medical Allowance
    }
    public function calculateTotalDeductions()
    {
        return $this->calculatePf() + $this->calculateEsi() + $this->calculateProfTax();
    }

    public function getEmployeeByEmpId($emp_id)
    {
        return $this->where('emp_id', $emp_id)->first();
    }




    public function calculateTotalAllowance()
    {
        return $this->basic + $this->hra + $this->conveyance + $this->medical + $this->special;
    }

    // Override the booted method to perform operations when the model is retrieved from the database
    protected static function booted()
    {
        static::retrieved(function ($employee) {
            // Access the salary attribute here
            $employee->salary = $employee->getAttribute('salary');
        });
    }


    // Define the relationship to the Employee model
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
