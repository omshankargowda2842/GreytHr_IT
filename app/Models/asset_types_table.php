<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asset_types_table extends Model
{
    use HasFactory;
    protected $table = 'asset_types_tables';
    protected $fillable = [
        'asset_names',
    ];

}
