<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'company_id',
        'company_name',
        'ceo_name',
        'company_type',
        'company_industry',
        'company_description',
        'company_registration_date',
        'company_present_address',
        'company_perminent_address',
        'company_registration_no',
        'company_logo',
        'password',
        'contact_email',
        'contact_phone',
        'company_website',
        // Add other fields that you want to be fillable here
    ];
}



