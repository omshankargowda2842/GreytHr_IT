<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VendorAsset extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'vendor_assets';
    protected $fillable = [
        'vendor_id',
        'asset_type',
        'asset_model',
        'asset_specification',
        'color',
        'version',
        'serial_number',
        'invoice_number',
        'taxable_amount',
        'invoice_amount',
        'gst_state',
        'gst_central',
        'manufacturer',
        'purchase_date',
        'file_paths',
    ];



    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}
