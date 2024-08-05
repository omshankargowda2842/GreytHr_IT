<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use HasFactory;
    protected $fillable = ['hr_emp_id','admin_emp_id','category', 'description', 'attachment'];

}
