<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;
    use HasFactory;

    protected $table = 'vendors';
    protected $fillable = [
        'vendor_name',
        'vendor_id',
        'contact_name',
        'phone',
        'gst',
        'bank_name',
        'delete_vendor_reason',
        'account_number',
        'ifsc_code',
        'branch',
        'contact_email',
        'street',
        'city',
        'state',
        'pin_code',
        'description',
        'file_paths',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function vendorAssets()
    {
        return $this->hasMany(VendorAsset::class, 'vendor_id', 'vendor_id');
    }


}
