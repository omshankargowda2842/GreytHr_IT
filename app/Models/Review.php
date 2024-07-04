<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [

        'applied_by ',

        'name',

        'closed',

        'remarks ',

        'no_of_days',

        'date ',

        'approve_reject',

        'approve_remarks',
        'shift',

        'first_in_time',

        'last_out_time',
        'reason',

        'accept',

        'accept_date',

        'accept_time',

        'submitted_date',

        'submitted_time',
    ];
}
