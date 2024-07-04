<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpBankDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'bank_name',
        'bank_branch',
        'account_number',
        'ifsc_code',
        'bank_address',
];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
