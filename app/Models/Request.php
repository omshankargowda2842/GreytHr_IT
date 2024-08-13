<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable = ['emp_id','category', 'request'];
    public function helpDesks()
    {
        return $this->hasMany(HelpDesks::class);
    }
}
