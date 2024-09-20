<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAssignments extends Model
{
    use HasFactory;
    protected $table = 'asset_assignments';

    protected $fillable = [
        'asset_id',
        'emp_id',
        'manufacturer',
        'asset_type',
        'employee_name',
        'department',
        'is_active',
    ];


    public function vendor()
    {
        return $this->belongsTo(VendorAsset::class, 'vendor_id', 'vendor_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

}
