<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'created_by'
    ];


}
