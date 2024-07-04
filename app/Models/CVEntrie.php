<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CVEntrie extends Model
{
    use HasFactory;
    protected $casts = [
        'education' => 'array',
        'work_experience' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'date_of_birth',
        'image',
        'technical_skills',
        'summary',
        'languages',
        'education',
        'work_experience'
    ];

}
