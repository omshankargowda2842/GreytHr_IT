<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class IT extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    protected $table = 'it_employees'; // Adjust the table name accordingly
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
        'status',
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
    // public function getImageUrlAttribute()
    // {
    //     return 'data:image/jpeg;base64,' . base64_encode($this->attributes['image']);
    // }

    // Check if the user has a specific role
    // public function isUser()
    // {
    //     return $this->role === self::ROLE_USER;
    // }

    // public function isAdmin()
    // {
    //     return $this->role === self::ROLE_ADMIN;
    // }

    // public function isSuperAdmin()
    // {
    //     return $this->role === self::ROLE_SUPER_ADMIN;
    // }

    // Generalized role check
    public function hasRole($role)
    {
        return $this->role === $role;
    }




    // Define the many-to-many relationship between users and roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    // Check if the user has a specific role
    // public function hasRole($role)
    // {
    //     if (is_string($role)) {
    //         return $this->roles()->where('name', $role)->exists();
    //     }

    //     return $this->roles()->where('name', $role->name)->exists();
    // }

    // public function hasRole($role)
    // {
    //     $roleId = is_string($role) ? Role::where('name', $role)->value('id') : $role->id;
    //     return DB::table('role_user')
    //         ->where('user_id', $this->it_emp_id) // Use it_emp_id for the user_id
    //         ->where('role_id', $roleId)
    //         ->exists();
    // }

    // Assign a role to the user
    // public function assignRole($role)
    // {
    //     return $this->roles()->attach($role);
    // }

    public function assignRole($role)
    {
        $roleId = is_string($role) ? Role::where('name', $role)->value('id') : $role;

        return DB::table('role_user')->insert([
            'user_id' => $this->it_emp_id, // Use it_emp_id for the user_id
            'role_id' => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Remove a role from the user
    // public function removeRole($role)
    // {
    //     return $this->roles()->detach($role);
    // }
    public function removeRole($role)
    {
        $roleId = is_string($role) ? Role::where('name', $role)->value('id') : $role;

        return DB::table('role_user')
            ->where('user_id', $this->it_emp_id) // Use it_emp_id for the user_id
            ->where('role_id', $roleId)
            ->delete();
    }
}

