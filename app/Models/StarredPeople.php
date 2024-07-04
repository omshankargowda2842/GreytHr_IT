<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarredPeople extends Model
{
    use HasFactory;
    protected $table = "starred_peoples";
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'emp_id',
        'company_id',
        'name',
        'people_id',
        'profile',
        'contact_details',
        'category',
        'location',
        'joining_date',
        'date_of_birth',
        'starred_status'
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
