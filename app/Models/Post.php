<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeDetails;

class Post extends Model
{
    use HasFactory;

  

    protected $fillable = [
        'hr_emp_id', 'admin_emp_id', 'emp_id', 'category', 'description', 'file_path', 
        'manager_id', 'mime_type', 'file_name', 'status'
    ];

    /**
     * Check if the file is an image and return it as a base64 encoded string.
     */
    public function isImage()
    {
        return $this->file_path ? 'data:image/jpeg;base64,' . base64_encode($this->file_path) : null;
    }

    /**
     * Relationship to get the manager details.
     */


    /**
     * Relationship to get the employee details.
     */
   
    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    
    public function managerDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'manager_id', 'emp_id');
    }
    

    /**
     * Accessor to get the image URL as a base64 encoded string.
     */
    public function getImageUrlAttribute()
    {
        return $this->file_path ? 'data:image/jpeg;base64,' . base64_encode($this->file_path) : null;
    }

}
