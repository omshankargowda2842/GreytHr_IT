<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAsset extends Model
{
    use HasFactory;
protected $table = 'employee_assets';
    protected $fillable = [
        'asset_id',
        'asset_tag',
        'status',
        'manufacturer',
        'asset_type',
        'asset_model',
        'asset_specification',
        'serial_number',
        'color',
        'current_owner',
        'previous_owner',
        'windows_version',
        'assign_date',
        'purchase_date',
        'invoice_no',
        'taxable_amount',
        'gst_central',
        'gst_state',
        'invoice_amount',
        'vendor',
        'other_assets',
        'sophos_antivirus',
        'vpn_creation',
        'teramind',
        'system_name',
        'system_upgradation',
        'screenshot_of_programms',
        'one_drive',
        'mac_address',
        'laptop_received',
        'laptop_received_date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
