<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;
    protected $table = 'email_logs';
    protected $fillable = [
        'subject', 'scheduled_at', 'to', 'cc', 'files', 'scheduled_status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'to' => 'json',
        'cc' => 'json',
        'files' => 'json',
    ];
}
