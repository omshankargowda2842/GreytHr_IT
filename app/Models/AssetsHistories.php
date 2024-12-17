<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AssetsHistories extends Model
{
    use HasFactory;
    protected $table = 'assets_histories';
    protected $fillable = [
        'vendor_id',
        'asset_type',
        'asset_model',
        'asset_specification',
        'color',
        'delete_asset_reason',
        'version',
        'serial_number',
        'invoice_number',
        'taxable_amount',
        'invoice_amount',
        'gst_ig',
        'gst_state',
        'gst_central',
        'barcode',
        'manufacturer',
        'purchase_date',
        'warranty_expire_date',
        'end_of_life',
        'file_paths',
        'status',
        'is_active',
        'asset_id',
        'created_by',
        'action'
    ];

    public static function getAssetDetails($assetId)
    {
        return static::join('vendors', 'assets_histories.vendor_id', '=', 'vendors.id')
            ->join('asset_types_tables', 'assets_histories.asset_type', '=', 'asset_types_tables.id')
            ->where('assets_histories.asset_id', $assetId)
            ->select('assets_histories.*', 'vendors.vendor_name', 'asset_types_tables.asset_name')
            ->first(); // or get() based on your requirement
    }
    public static function getEmployeeName($empId)
    {
        $employee = DB::table('employee_details')->where('emp_id', $empId)->first(['first_name', 'last_name']);

        if ($employee) {
            return ucwords($employee->first_name) . ' ' . ucwords($employee->last_name);
        }

        return 'Unknown Employee'; // Default value if no record is found
    }

    public static function getVendorName($vendor_id)
    {
        $vendor = DB::table('vendors')->where('vendor_id', $vendor_id)->first();

        return $vendor->vendor_name;
    }
    public static function getAssetType($asset_type)
    {
        $asset_name = DB::table('asset_types_tables')->where('id', $asset_type)->first(['asset_names']);
        return $asset_name->asset_names;
    }
}
