<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Define the many-to-many relationship between roles and permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    // Define the many-to-many relationship between roles and users
    public function users()
    {
        return $this->belongsToMany(IT::class, 'role_user', 'role_id', 'user_id');
    }

    // Assign permission to a role
    public function givePermissionTo($permission)
    {
        return $this->permissions()->attach($permission);
    }

    // Check if a role has a specific permission
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('name', $permission)->exists();
        }

        return $this->permissions()->where('name', $permission->name)->exists();
    }

    // Remove permission from a role
    public function removePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
}
