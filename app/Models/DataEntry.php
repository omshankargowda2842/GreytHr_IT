<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'employee_title',
        'project_title',
        'employee_email',
        'work_location',
        'project_manager',
        'access_network',
        'po_number',
        'hourly_paid',
        'mark_up',
        'hourly_max',
        'start_date',
        'sow_end_date',
        'background_check',
        'on_site_resource',
        'vaccination_status'
    ];
}
