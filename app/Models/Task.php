<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'task_name',
        'assignee',
        'priority',
        'due_date',
        'tags',
        'followers',
        'subject',
        'description',
        'file_path',
        'status'
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
