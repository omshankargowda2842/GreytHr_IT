<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'details',
        'performed_by',
        'request_type',
        'request_id',
        'impact',
        'opened_by',
        'priority',
        'state',
        'attachments',
    ];
}
