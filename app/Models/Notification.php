<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notifications";
    protected $primaryKey = 'id';

    protected $fillable = [
        'emp_id',
        'task_name',
        'assignee',
        'leave_type',
        'leave_reason',
        'leave_status',
        'applying_to',
        'cc_to',
        'receiver_id',
        'body',
        'notification_type',
        'chatting_id',
        'message_read_at',
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
