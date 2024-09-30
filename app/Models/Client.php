<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $primaryKey = 'client_id'; // Assuming 'client_id' is the primary key

    protected $fillable = [
        'client_id',
        'client_name',
        'hr_name',
        'client_address1',
        'client_address2',
        'client_registration_date',
        'client_logo',
        'password',
        'contact_email',
        'contact_phone',
    ];
}
