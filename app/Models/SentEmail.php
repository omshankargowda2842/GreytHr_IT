<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;
    protected $fillable = ['to_email', 'cc_email', 'subject', 'file_path','status','scheduled_time'];
}
