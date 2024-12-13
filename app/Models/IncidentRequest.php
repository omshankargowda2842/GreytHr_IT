<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'snow_id',
        'category',
        'emp_id',
        'short_description',
        'description',
        'active_inc_notes',
        'active_ser_notes',
        'inc_assign_to',
        'ser_assign_to',
        'inc_pending_notes',
        'ser_pending_notes',
        'inc_inprogress_notes',
        'ser_inprogress_notes',
        'inc_completed_notes',
        'ser_completed_notes',
        'inc_cancel_notes',
        'ser_cancel_notes',
        'inc_customer_visible_notes',
        'ser_customer_visible_notes',
        'in_progress_since',
        'ser_progress_since',
        'total_in_progress_time',
        'total_ser_progress_time',
        'inc_end_date',
        'ser_end_date',
        'priority',
        'assigned_dept',
        'file_path',

        'status_code',
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'emp_id');// Update the foreign key as necessary
    }
 // HelpDesks Model

 // In HelpDesks model (HelpDesks.php)
// In HelpDesks model (HelpDesks.php)
public function status()
{
    return $this->belongsTo(StatusType::class, 'status_code', 'status_code');
}


    public function isImage()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['file_path']);
    }
    public function getImageUrlAttribute()
    {
        return $this->file_path ? 'data:image/jpeg;base64,' . base64_encode($this->file_path) : null;
    }

}
