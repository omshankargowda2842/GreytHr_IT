<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Message extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable=[
        'body',
        'file_path',
        'file_name',
        'mime_type',
        'sender_id',
        'receiver_id',
        'chating_id',
        'read_at',
        'receiver_deleted_at',
        'sender_deleted_at',
    ];


    protected $dates=['read_at','receiver_deleted_at','sender_deleted_at'];


    /* relationship */

    public function conversation()
    {
        return $this->belongsTo(Chating::class);
    }


    public function isRead():bool
    {

         return $this->read_at != null;
    }
    public function isImage()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['file_path']);
    }

    public function getImageUrlAttribute()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['file_path']);
    }
    public function employeeDetails()
{
    return $this->hasOne(EmployeeDetails::class, 'emp_id', 'emp_id'); // Adjust the foreign key as necessary
}

}
