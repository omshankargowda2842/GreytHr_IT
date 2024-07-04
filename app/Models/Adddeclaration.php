<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adddeclaration extends Model
{
    use HasFactory;


    protected $fillable = [
        'emp_id',
        '5_years_fixed_deposit',
        '5_years_deposit',
        'contribution_to_pension_fund',
        'deposit_in_nsc',
        'deposit_in_nss',
        'interest_on_nsc_reinvested',
        'equity',
        'life_insurance',
        'total'
    ];
}