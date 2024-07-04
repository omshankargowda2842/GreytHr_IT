<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'father_first_name',
        'father_last_name',
        'mother_first_name',
        'mother_last_name',
        'father_dob',
        'mother_dob',
        'father_address',
        'mother_address',
        'father_city',
        'mother_city',
        'father_state',
        'mother_state',
        'father_country',
        'mother_country',
        'father_email',
        'mother_email',
        'father_phone',
        'mother_phone',
        'father_occupation',
        'mother_occupation',
        'father_image',
        'mother_image',
        'father_blood_group',
        'mother_blood_group',
        'father_nationality',
        'mother_nationality',
        'father_religion',
        'mother_religion',
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
