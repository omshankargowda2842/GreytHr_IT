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
        'hr_emp_id',
        'comment',
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function hr()
    {
        return $this->belongsTo(EmployeeDetails::class, 'hr_emp_id', 'emp_id');
    }
    public function interacted()
    {
        return $this->belongsTo(Comment::class);
    }

    // Accessor to calculate interacted_count
    public function getInteractedCountAttribute()
    {
        return $this->interacted->count();
    }
    public function getImageUrlAttribute()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['image']);
    }
}