<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'client_id',
        'project_name',
        'task_name',
        'assignee',
        'priority',
        'due_date',
        'reopened_date',
        'tags',
        'followers',
        'subject',
        'description',
        'file_path',
        'file_name',
        'mime_type',
        'status'
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }
}
