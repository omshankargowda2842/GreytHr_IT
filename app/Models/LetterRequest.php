<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterRequest extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $table="letter_requests";
    protected $fillable = [
        'emp_id',
        'letter_type',
        'priority',
        'reason',
        'status',
        // Add other necessary fields
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}