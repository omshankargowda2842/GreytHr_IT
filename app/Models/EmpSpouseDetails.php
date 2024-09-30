<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSpouseDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'name',
        'gender',
        'qualification',
        'profession',
        'dob',
        'nationality',
        'bld_group',
        'adhar_no',
        'pan_no',
        'religion',
        'email',
        'address',
        'children',
        'image',
    ];

    // Assuming emp_id is not auto-incrementing or not an integer ID
    protected $keyType = 'string';

    // Assuming primary key name is not 'id'
    protected $primaryKey = 'emp_id';

    // Relationships
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id');
    }

}
