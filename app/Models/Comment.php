<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_id',
        'emp_id',
        'comment',
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}