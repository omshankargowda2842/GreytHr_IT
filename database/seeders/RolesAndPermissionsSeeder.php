<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);
        $superAdminRole = Role::create(['name' => 'super_admin']);

        // Create permissions
        $createPermission = Permission::create(['name' => 'create']);
        $editPermission = Permission::create(['name' => 'edit']);
        $deletePermission = Permission::create(['name' => 'delete']);

        // Assign permissions to roles
        $userRole->permissions()->attach([$editPermission->id]);
        $adminRole->permissions()->attach([$createPermission->id, $editPermission->id]);
        $superAdminRole->permissions()->attach(Permission::all());
    }
}
