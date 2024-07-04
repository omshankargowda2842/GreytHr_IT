<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwipeData extends Model
{
    use HasFactory;
    protected $table = 'swipedata';
    protected $fillable = ['emp_id', 'direction', 'DownloadDate'];
    public function employeeSwipes()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
