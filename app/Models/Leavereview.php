<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leavereview extends Model
{
    use HasFactory;
    protected $fillable = [
 
        'applied_by',
   
   
   
        'from_date',
   
   
   
        'to_date',
   
   
   
        'no_of_days',
   
   
   
        'applying_to',
   
   
      'reason',
     
        'cc_to',
   
   
   
        'approved',
   
   
   
        'submitted',
   
        ];
}
