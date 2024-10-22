<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class IT extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    protected $table = 'i_t'; // Adjust the table name accordingly

    // ENUM role constants
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

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
        'role' => 'string', // Casting the role as string for ENUM type
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

    // Check if the user has a specific role
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

    // Generalized role check
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
