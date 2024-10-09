<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class IT extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $table = 'i_t'; // Adjust the table name accordingly
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_SUPER_ADMIN = 2;
    protected $fillable = [
        'it_emp_id',
        'image',
        'employee_name',
        'date_of_birth',
        'emp_id',
        'phone_number',
        'email',
        'password',
        'is_active',
        'delete_itmember_reason',
        'active_comment',
        'inprogress_remarks',
        'role',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    public function com()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function empIt()
    {

        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value ? $value : null;
    }

    public function getImageAttribute($value)
    {
        return $value;
    }
    public function getImageUrlAttribute()
    {
        return 'data:image/jpeg;base64,' . base64_encode($this->attributes['image']);
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }
}
