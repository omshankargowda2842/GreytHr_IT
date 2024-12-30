<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HelpDesks extends Model
{
    use HasFactory;
    protected $fillable=[
        'emp_id', 'category', 'subject', 'description', 'file_path', 'cc_to', 'priority','status_code','mail','mobile','distributor_name','selected_equipment','rejection_reason','active_comment','closed_notes','cancel_notes','req_end_date','pending_remarks','pending_notes','cat_file_paths','inprogress_notes','cat_progress_since','total_cat_progress_time','assign_to','customer_visible_notes'
     ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function request()
    {
        return $this->belongsTo(Request::class, 'emp_id');// Update the foreign key as necessary
    }

    public function isImage()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['file_path']);
    }
    public function getImageUrlAttribute()
    {
        return $this->file_path ? 'data:image/jpeg;base64,' . base64_encode($this->file_path) : null;
    }



    protected static function booted()
    {
        static::created(function ($helpDesk) {

            $employee = EmployeeDetails::where('emp_id', $helpDesk->emp_id)->first();

            if (!$employee) {
                Log::error("Employee not found for ID: {$helpDesk->employee_id}");
                return; // Prevent creating a notification if the employee is not found
            }

            $employeeName = "{$employee->first_name} {$employee->last_name}";

            $title = "Catalog Request Raised by {$employeeName}";
            $message = "Subject : {$helpDesk->category}";
            $redirect_url = 'itrequest?currentCatalogId=' . $helpDesk->id;



            // Check if any value is missing, log it if necessary
            if (!$title || !$message || !$redirect_url) {
                Log::error('Missing required notification data: title, message or redirect_url');
                return; // Prevent creating a notification if any data is missing
            }

            // Create the notification
            ticket_notifications::create([
                'title' => $title,
                'message' => $message,
                'redirect_url' => $redirect_url,
                'notifiable_id' => $helpDesk->id,
                'notifiable_type' => HelpDesks::class, // Use the HelpDesks model as the notifiable type
            ]);
        });
    }




}
