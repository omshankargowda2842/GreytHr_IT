<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;
    protected $fillable = [
        'location',
        'department',
        'reporting_manager',
        'total_experience',
        'join_date',
        'confirmation_initiated',
        'confirmation_due_date',
        'date', // Added a comma to separate elements in the array
        'time', // Added a comma to separate elements in the array
        'confirmation_initiated_date',
        'confirmation_initiated_time',
        'confirmation_due_date1',
        'join_date', // You have 'join_date' listed twice, you may want to remove one of them
    ];
}
