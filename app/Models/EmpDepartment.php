<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpDepartment extends Model
{
    use HasFactory;
    protected $fillable = [
        'dept_id',
        'department',
        'company_id',
        'created_at',
        'updated_at'
    ];


    //  protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         // Generate the dept_id
    //         $model->dept_id = $model->generateDeptId();
    //     });
    // }

    // private function generateDeptId()
    // {
    //     $department = $this->department;
    //     $prefix = strtoupper(substr($department, 0, 2)); // Use the first 2 characters of the department name

    //     // Get the last inserted dept_id with the same prefix
    //     $lastRecord = self::where('dept_id', 'like', $prefix . '-%')
    //         ->orderBy('dept_id', 'desc')
    //         ->first();

    //     $lastId = $lastRecord ? intval(substr($lastRecord->dept_id, 3)) : 0;

    //     return sprintf('%s-%05d', $prefix, $lastId + 1);
    // }
}

