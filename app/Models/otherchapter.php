<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherchapter extends Model
{
 
    use HasFactory;
    protected $fillable =[
        'emp_id',
        'intrest_on_housing',
        'intrest_on_loan',
        'contribution_to_pension_fund',
        'deposit_in_nsc',
        'deposit_in_nss',
        'interest_on_nsc_reinvested',
        'superannuation',
        'donation',
        'total'
    ];
}
