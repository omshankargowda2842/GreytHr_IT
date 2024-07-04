<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITStatement extends Model
{
    
    use HasFactory;

    protected $table = 'it_statement';

    protected $fillable = [
        'montly_income_type',
        'total',
        'Jan_2023',
        'Feb_2023',
        'Mar_2023',
        'Apr_2023',
        'May_2023',
        'Jun_2023',
        'Jul_2023',
        'Aug_2023',
        'Sep_2023',
        'Oct_2023',
        'Nov_2023',
        'Dec_2023',
    ];
}

