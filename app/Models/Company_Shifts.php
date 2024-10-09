<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company_Shifts extends Model
{
    use HasFactory;
    protected $table = "company_shifts";
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'shift_name',
        'shift_start_time',
        'shift_end_time',

    ];

}
