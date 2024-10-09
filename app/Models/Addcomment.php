<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addcomment extends Model
{
    use HasFactory;
    protected $table = 'add_comments';
    protected $fillable = [
        'emp_id',
        'card_id',
        'addcomment',
        'hr_emp_id',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function hr()
    {
        return $this->belongsTo(HR::class, 'hr_emp_id', 'emp_id');
    }
    public function interacted()
    {
        return $this->belongsTo(Addcomment::class);
    }
    public function getImageUrlAttribute()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['image']);
    }
}
